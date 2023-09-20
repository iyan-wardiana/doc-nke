<?php
    // _global function
        $this->db->select('Display_Rows,decFormat,CompDesc');
        $resGlobal = $this->db->get('tglobalsetting')->result();
        foreach($resGlobal as $row) :
            $Display_Rows   = $row->Display_Rows;
            $decFormat    = $row->decFormat;
            $CompDesc     = $row->CompDesc;
        endforeach;
        $decFormat  = 2;

        date_default_timezone_set("Asia/Jakarta");

        $myPRJCODE  = '';

        $this->load->view('template/head');

        $appName 	= $this->session->userdata('appName');
		$vers     	= $this->session->userdata['vers'];
		$FlagUSER 	= $this->session->userdata['FlagUSER'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$Emp_ID 	= $this->session->userdata['Emp_ID'];
		$appBody    = $this->session->userdata['appBody'];

        //$this->load->view('template/topbar');
        //$this->load->view('template/sidebar');

        $LOGHIST  = 1;

        $WEEK_BEF1  = mktime(0, 0, 0, date("m"), date("d")-31, date("Y"));
        $WEEK_BEF = date("Y-m-d", $WEEK_BEF1);
        $WEEK_NEXT1 = mktime(0, 0, 0, date("m"), date("d")+2, date("Y"));
        $WEEK_NEXT  = date("Y-m-d", $WEEK_NEXT1);
        $THISD    = date('d');
        $THISM    = date('m');
        $THISY    = date('Y');

    // CHECK LOG ON
        $TOTE       = 0;
        $TOTOL      = 0;
        $TOTOF      = 0;
        $sqlTLE     = "SELECT COUNT(*) AS TOTENTER
                        FROM tbl_trail_tracker
                        WHERE
                          DAY(TTR_DATE) = '$THISD' AND MONTH(TTR_DATE) = '$THISM' AND YEAR(TTR_DATE) = '$THISY' 
                          AND TTR_CATEG = 'ENTER'
                        GROUP BY
                        DATE(TTR_DATE)";
        $resTLE     = $this->db->query($sqlTLE)->result();
        foreach($resTLE as $rowTLE) :
            $TOTE   = $rowTLE->TOTENTER;
        endforeach;
        $sqlTOL     = "SELECT COUNT(*) AS TOTOL FROM tbl_employee WHERE OLStat = 1";
        $resTOL     = $this->db->query($sqlTOL)->result();
        foreach($resTOL as $rowTOL) :
            $TOTOL  = $rowTOL->TOTOL;
        endforeach;
        $sqlTOF     = "SELECT COUNT(*) AS TOTOF FROM tbl_employee WHERE OLStat = 0";
        $resTOF     = $this->db->query($sqlTOF)->result();
        foreach($resTOF as $rowTOF) :
            $TOTOF  = $rowTOF->TOTOF;
        endforeach;
        $TOTE   = $TOTOL + $TOTOF;

    // Menampilkan Grafik Project Plan Akumulate
        $jumTotPlanAkuma  = '';
        $GraphicTitleText = 3;

    // SET PROJECT
		$selPRJCODE   = "AllPRJ";
		if(isset($_POST['submitPRJ']))
		{
			$selPRJCODE = $_POST['selPRJCODE'];
		}
		
		$myPRJCODE    = '';
		if($selPRJCODE == "AllPRJ")
		{
			$myrow    = 0;
			$sql      = "SELECT A.proj_Code, B.PRJNAME
						  FROM tbl_employee_proj A
						  LEFT JOIN tbl_project B ON B.PRJCODE = A.proj_Code
						  WHERE A.Emp_ID = '$Emp_ID'";
			$result   = $this->db->query($sql)->result();
			foreach($result as $row) :
				$myrow    = $myrow + 1;
				$PRJCODED   = $row->proj_Code;
				$PRJNAMEX   = $row->PRJNAME;
				if($myrow == 1)
				{
					$myPRJCODE  = "'$PRJCODED'";
					$myPRJCODE1 = "'$PRJCODED'";
				}
				if($myrow > 1)
				{
					$myPRJCODE1 = "$myPRJCODE1, '$PRJCODED'";
					$myPRJCODE  = "$myPRJCODE1";
				}   
			endforeach;
			$myPRJCODEX = "'$PRJCODED'";
		}
		else
		{
			$myPRJCODE  = "'$selPRJCODE'";
			$myPRJCODEX = "'$selPRJCODE'";
			$PRJNAMEX = '';
			$sqlPRJ   = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = $myPRJCODE";
			$resPRJ   = $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAMEX   = $rowPRJ->PRJNAME;
			endforeach;
		}

    // SET DEFAULT VALUE
      	// ----------------------- REQUEST / PR
			$REQ_NEW  = 0;
			$REQ_CON  = 0;
			$REQ_APP  = 0;
			$REQ_REV  = 0;
			$REQ_REJ  = 0;
			$REQ_CLS  = 0;
			  	$TOT_REQ_NEW  = 0;
			  	$TOT_REQ_CON  = 0;
			  	$TOT_REQ_APP  = 0;
			  	$TOT_REQ_REV  = 0;
			  	$TOT_REQ_REJ  = 0;
          		$TOT_REQ_CLS  = 0;
      	// ----------------------- PURCHASE ORDER / PO
			$PO_NEW   = 0;
			$PO_CON   = 0;
			$PO_APP   = 0;
			$PO_REV   = 0;
			$PO_REJ   = 0;
			$PO_CLS   = 0;
				$TOT_PO_NEW = 0;
				$TOT_PO_CON = 0;
				$TOT_PO_APP = 0;
				$TOT_PO_REV = 0;
				$TOT_PO_REJ = 0;
				$TOT_PO_CLS = 0;
      	// ----------------------- ITEM RECEIPT / IR
			$IR_NEW   = 0;
			$IR_CON   = 0;
			$IR_APP   = 0;
			$IR_REV   = 0;
			$IR_REJ   = 0;
			$IR_CLS   = 0;
				$TOT_IR_NEW = 0;
				$TOT_IR_CON = 0;
				$TOT_IR_APP = 0;
				$TOT_IR_REV = 0;
				$TOT_IR_REJ = 0;
          		$TOT_IR_CLS = 0;
     	 // ----------------------- PURCHASE INVOICE / FAKTUR
			$PINV_NEW = 0;
			$PINV_CON = 0;  // OUTSTANDING PAYMENT
			$PINV_HP  = 0;
			$PINV_FP  = 0;
			$PINV_REJ = 0;
				$TOT_PINV_NEW = 0;
				$TOT_PINV_CON = 0;
				$TOT_PINV_HP  = 0;
				$TOT_PINV_FP  = 0;
				$TOT_PINV_REJ = 0;
      	// ----------------------- INVOICE / FAKTUR
			$INV_NEW  = 0;
			$INV_CON  = 0;
			$INV_APP  = 0;
			$INV_REV  = 0;
			$INV_REJ  = 0;
			$INV_CLS  = 0;
				$TOT_INV_NEW  = 0;
				$TOT_INV_CON  = 0;
				$TOT_INV_APP  = 0;
				$TOT_INV_REV  = 0;
				$TOT_INV_REJ  = 0;
				$TOT_INV_CLS  = 0;
				$TOT_INV_FP   = 0;
				$TOT_INV_NP   = 0;
      	// ----------------------- BANK PAYMENT / CB
			$CB_NEW   = 0;
			$CB_CON   = 0;
			$CB_APP   = 0;
			$CB_REV   = 0;
			$CB_REJ   = 0;
			$CB_CLS   = 0;
				$TOT_CB_NEW = 0;
				$TOT_CB_CON = 0;
				$TOT_CB_APP = 0;
				$TOT_CB_REV = 0;
				$TOT_CB_REJ = 0;
				$TOT_CB_CLS = 0;
      	// ----------------------- OFFERING LETTER / OFF
			$OFF_NEW   = 0;
			$OFF_CON   = 0;
			$OFF_APP   = 0;
			$OFF_REV   = 0;
			$OFF_REJ   = 0;
			$OFF_CLS   = 0;
				$TOT_OFF_NEW = 0;
				$TOT_OFF_CON = 0;
				$TOT_OFF_APP = 0;
				$TOT_OFF_REV = 0;
				$TOT_OFF_REJ = 0;
				$TOT_OFF_CLS = 0;
		// ----------------------- SALES ORDER / SO
			$SO_NEW  = 0;
			$SO_CON  = 0;
			$SO_APP  = 0;
			$SO_REV  = 0;
			$SO_REJ  = 0;
			$SO_CLS  = 0;
				$TOT_SO_NEW  = 0;
				$TOT_SO_CON  = 0;
				$TOT_SO_APP  = 0;
				$TOT_SO_REV  = 0;
				$TOT_SO_REJ  = 0;
				$TOT_SO_CLS  = 0;
		// ----------------------- WORK ORDER / JO / SPK
			$WO_NEW  = 0;
			$WO_CON  = 0;
			$WO_APP  = 0;
			$WO_REV  = 0;
			$WO_REJ  = 0;
			$WO_CLS  = 0;
				$TOT_WO_NEW  = 0;
				$TOT_WO_CON  = 0;
				$TOT_WO_APP  = 0;
				$TOT_WO_REV  = 0;
				$TOT_WO_REJ  = 0;
				$TOT_WO_CLS  = 0;
		// ----------------------- MATERIAL REQUEST / MR
			$MR_NEW  = 0;
			$MR_CON  = 0;
			$MR_APP  = 0;
			$MR_REV  = 0;
			$MR_REJ  = 0;
			$MR_CLS  = 0;
				$TOT_MR_NEW  = 0;
				$TOT_MR_CON  = 0;
				$TOT_MR_APP  = 0;
				$TOT_MR_REV  = 0;
				$TOT_MR_REJ  = 0;
				$TOT_MR_CLS  = 0;
		// ----------------------- SHIPMENT NOTE / SN
			$SN_NEW  = 0;
			$SN_CON  = 0;
			$SN_APP  = 0;
			$SN_REV  = 0;
			$SN_REJ  = 0;
			$SN_CLS  = 0;
				$TOT_SN_NEW  = 0;
				$TOT_SN_CON  = 0;
				$TOT_SN_APP  = 0;
				$TOT_SN_REV  = 0;
				$TOT_SN_REJ  = 0;
				$TOT_SN_CLS  = 0;

      	// ----------------------- CHART
			$GRF_LOGHIST  	= 0;
			$GRF_SALES     	= 0;
			$GRF_PURCH     	= 0;
			$GRF_BP			= 0;
			$GRF_GOALC		= 0;

    	// GET QTY FOR EACH STATUS
			if($selPRJCODE == "AllPRJ")
			{
				$sqlAllData = "SELECT
									SUM(TOT_REQ) AS TOT_REQ, SUM(TOT_REQ_N) AS TOT_REQ_NEW, SUM(TOT_REQ_C) AS TOT_REQ_CON,
										SUM(TOT_REQ_A) AS TOT_REQ_APP, SUM(TOT_REQ_R) AS TOT_REQ_REV, SUM(TOT_REQ_RJ) AS TOT_REQ_REJ,
										SUM(TOT_REQ_CL) AS TOT_REQ_CLS,
									SUM(TOT_PO) AS TOT_PO, SUM(TOT_PO_N) AS TOT_PO_NEW, SUM(TOT_PO_C) AS TOT_PO_CON, SUM(TOT_PO_A) AS TOT_PO_APP,
										SUM(TOT_PO_R) AS TOT_PO_REV, SUM(TOT_PO_RJ) AS TOT_PO_REJ, SUM(TOT_PO_CL) AS TOT_PO_CLS,
									SUM(TOT_IR) AS TOT_IR, SUM(TOT_IR_N) AS TOT_IR_NEW, SUM(TOT_IR_C) AS TOT_IR_CON, SUM(TOT_IR_A) AS TOT_IR_APP,
										SUM(TOT_IR_R) AS TOT_IR_REV, SUM(TOT_IR_RJ) AS TOT_IR_REJ, SUM(TOT_IR_CL) AS TOT_IR_CLS,
									SUM(TOT_PINV) AS TOT_PINV, SUM(TOT_PINV_N) AS TOT_PINV_NEW, SUM(TOT_PINV_C) AS TOT_PINV_CON,
										SUM(TOT_PINV_HP) AS TOT_PINV_HP, SUM(TOT_PINV_FP) AS TOT_PINV_FP, SUM(TOT_PINV_R) AS TOT_PINV_REJ,
									SUM(TOT_INV) AS TOT_INV, SUM(TOT_INV_FP) AS TOT_INV_FP, SUM(TOT_INV_NP) AS TOT_INV_NP,
									SUM(TOT_CB) AS TOT_CB, SUM(TOT_CB_N) AS TOT_CB_NEW, SUM(TOT_CB_C) AS TOT_CB_CON, SUM(TOT_CB_A) AS TOT_CB_APP,
										SUM(TOT_CB_R) AS TOT_CB_REV, SUM(TOT_CB_RJ) AS TOT_CB_REJ, SUM(TOT_CB_CL) AS TOT_CB_CLS,
									SUM(TOT_OFF) AS TOT_OFF, SUM(TOT_OFF_N) AS TOT_OFF_NEW, SUM(TOT_OFF_C) AS TOT_OFF_CON, SUM(TOT_OFF_A) AS TOT_OFF_APP,
										SUM(TOT_OFF_R) AS TOT_OFF_REV, SUM(TOT_OFF_RJ) AS TOT_OFF_REJ, SUM(TOT_OFF_CL) AS TOT_OFF_CLS,
									SUM(TOT_SO) AS TOT_SO, SUM(TOT_SO_N) AS TOT_SO_NEW, SUM(TOT_SO_C) AS TOT_SO_CON, SUM(TOT_SO_A) AS TOT_SO_APP,
										SUM(TOT_SO_R) AS TOT_SO_REV, SUM(TOT_SO_RJ) AS TOT_SO_REJ, SUM(TOT_SO_CL) AS TOT_SO_CLS,
									SUM(TOT_WO) AS TOT_WO, SUM(TOT_WO_N) AS TOT_WO_NEW, SUM(TOT_WO_C) AS TOT_WO_CON, SUM(TOT_WO_A) AS TOT_WO_APP,
										SUM(TOT_WO_R) AS TOT_WO_REV, SUM(TOT_WO_RJ) AS TOT_WO_REJ, SUM(TOT_WO_CL) AS TOT_WO_CLS,
									SUM(TOT_MR) AS TOT_MR, SUM(TOT_MR_N) AS TOT_MR_NEW, SUM(TOT_MR_C) AS TOT_MR_CON, SUM(TOT_MR_A) AS TOT_MR_APP,
										SUM(TOT_MR_R) AS TOT_MR_REV, SUM(TOT_MR_RJ) AS TOT_MR_REJ, SUM(TOT_MR_CL) AS TOT_MR_CLS,
									SUM(TOT_SN) AS TOT_SN, SUM(TOT_SN_N) AS TOT_SN_NEW, SUM(TOT_SN_C) AS TOT_SN_CON, SUM(TOT_SN_A) AS TOT_SN_APP,
										SUM(TOT_SN_R) AS TOT_SN_REV, SUM(TOT_SN_RJ) AS TOT_SN_REJ, SUM(TOT_SN_CL) AS TOT_SN_CLS
								FROM tbl_dash_transac WHERE PRJ_CODE IN ($myPRJCODE)";
				$resAllData   = $this->db->query($sqlAllData)->result();
				foreach($resAllData as $rowAllD) :
					// PR
						$TOT_REQ    	= $rowAllD->TOT_REQ;
						$TOT_REQ_NEW	= $rowAllD->TOT_REQ_NEW;
						$TOT_REQ_CON  	= $rowAllD->TOT_REQ_CON;
						$TOT_REQ_APP  	= $rowAllD->TOT_REQ_APP;
						$TOT_REQ_REV  	= $rowAllD->TOT_REQ_REV;
						$TOT_REQ_REJ  	= $rowAllD->TOT_REQ_REJ;
						$TOT_REQ_CLS  	= $rowAllD->TOT_REQ_CLS;  // CLOSE
					// PO
						$TOT_PO     	= $rowAllD->TOT_PO;
						$TOT_PO_NEW  	= $rowAllD->TOT_PO_NEW;
						$TOT_PO_CON   	= $rowAllD->TOT_PO_CON;
						$TOT_PO_APP   	= $rowAllD->TOT_PO_APP;
						$TOT_PO_REV   	= $rowAllD->TOT_PO_REV;
						$TOT_PO_REJ   	= $rowAllD->TOT_PO_REJ;
						$TOT_PO_CLS  	= $rowAllD->TOT_PO_CLS;
					// IR
						$TOT_IR     	= $rowAllD->TOT_IR;
						$TOT_IR_NEW   	= $rowAllD->TOT_IR_NEW;
						$TOT_IR_CON   	= $rowAllD->TOT_IR_CON;
						$TOT_IR_APP   	= $rowAllD->TOT_IR_APP;
						$TOT_IR_REV   	= $rowAllD->TOT_IR_REV;
						$TOT_IR_REJ   	= $rowAllD->TOT_IR_REJ;
						$TOT_IR_CLS   	= $rowAllD->TOT_IR_CLS;
					// PINV
						$TOT_PINV   	= $rowAllD->TOT_PINV;
						$TOT_PINV_NEW 	= $rowAllD->TOT_PINV_NEW;
						$TOT_PINV_CON 	= $rowAllD->TOT_PINV_CON;
						$TOT_PINV_HP  	= $rowAllD->TOT_PINV_HP;
						$TOT_PINV_FP  	= $rowAllD->TOT_PINV_FP;
						$TOT_PINV_REJ 	= $rowAllD->TOT_PINV_REJ;
						$TOT_INV    	= $rowAllD->TOT_INV;
						$TOT_INV_FP   	= $rowAllD->TOT_INV_FP;
						$TOT_INV_NP   	= $rowAllD->TOT_INV_NP;
					// CB
						$TOT_CB     	= $rowAllD->TOT_CB;
						$TOT_CB_NEW   	= $rowAllD->TOT_CB_NEW;
						$TOT_CB_CON   	= $rowAllD->TOT_CB_CON;
						$TOT_CB_APP   	= $rowAllD->TOT_CB_APP;
						$TOT_CB_REV   	= $rowAllD->TOT_CB_REV;
						$TOT_CB_REJ   	= $rowAllD->TOT_CB_REJ;
						$TOT_CB_CLS   	= $rowAllD->TOT_CB_CLS;
					// OFF LETTER
						$TOT_OFF     	= $rowAllD->TOT_OFF;
						$TOT_OFF_NEW   	= $rowAllD->TOT_OFF_NEW;
						$TOT_OFF_CON   	= $rowAllD->TOT_OFF_CON;
						$TOT_OFF_APP   	= $rowAllD->TOT_OFF_APP;
						$TOT_OFF_REV   	= $rowAllD->TOT_OFF_REV;
						$TOT_OFF_REJ   	= $rowAllD->TOT_OFF_REJ;
						$TOT_OFF_CLS   	= $rowAllD->TOT_OFF_CLS;
					// SO
						$TOT_SO     	= $rowAllD->TOT_SO;
						$TOT_SO_NEW   	= $rowAllD->TOT_SO_NEW;
						$TOT_SO_CON   	= $rowAllD->TOT_SO_CON;
						$TOT_SO_APP   	= $rowAllD->TOT_SO_APP;
						$TOT_SO_REV   	= $rowAllD->TOT_SO_REV;
						$TOT_SO_REJ   	= $rowAllD->TOT_SO_REJ;
						$TOT_SO_CLS   	= $rowAllD->TOT_SO_CLS;
					// WO
						$TOT_WO     	= $rowAllD->TOT_WO;
						$TOT_WO_NEW   	= $rowAllD->TOT_WO_NEW;
						$TOT_WO_CON   	= $rowAllD->TOT_WO_CON;
						$TOT_WO_APP   	= $rowAllD->TOT_WO_APP;
						$TOT_WO_REV   	= $rowAllD->TOT_WO_REV;
						$TOT_WO_REJ   	= $rowAllD->TOT_WO_REJ;
						$TOT_WO_CLS   	= $rowAllD->TOT_WO_CLS;
					// MR
						$TOT_MR     	= $rowAllD->TOT_MR;
						$TOT_MR_NEW   	= $rowAllD->TOT_MR_NEW;
						$TOT_MR_CON   	= $rowAllD->TOT_MR_CON;
						$TOT_MR_APP   	= $rowAllD->TOT_MR_APP;
						$TOT_MR_REV   	= $rowAllD->TOT_MR_REV;
						$TOT_MR_REJ   	= $rowAllD->TOT_MR_REJ;
						$TOT_MR_CLS   	= $rowAllD->TOT_MR_CLS;
					// SN
						$TOT_SN     	= $rowAllD->TOT_SN;
						$TOT_SN_NEW   	= $rowAllD->TOT_SN_NEW;
						$TOT_SN_CON   	= $rowAllD->TOT_SN_CON;
						$TOT_SN_APP   	= $rowAllD->TOT_SN_APP;
						$TOT_SN_REV   	= $rowAllD->TOT_SN_REV;
						$TOT_SN_REJ   	= $rowAllD->TOT_SN_REJ;
						$TOT_SN_CLS   	= $rowAllD->TOT_SN_CLS;
				endforeach;
      		}
      		else
			{
				$sqlAllData   	= "SELECT * FROM tbl_dash_transac WHERE PRJ_CODE = $myPRJCODE";
				$resAllData   	= $this->db->query($sqlAllData)->result();
				foreach($resAllData as $rowAllD) :
					// PR
						$TOT_REQ    	= $rowAllD->TOT_REQ;
						$TOT_REQ_NEW	= $rowAllD->TOT_REQ_NEW;
						$TOT_REQ_CON  	= $rowAllD->TOT_REQ_CON;
						$TOT_REQ_APP  	= $rowAllD->TOT_REQ_APP;
						$TOT_REQ_REV  	= $rowAllD->TOT_REQ_REV;
						$TOT_REQ_REJ  	= $rowAllD->TOT_REQ_REJ;
						$TOT_REQ_CLS  	= $rowAllD->TOT_REQ_CLS;  // CLOSE
					// PO
						$TOT_PO     	= $rowAllD->TOT_PO;
						$TOT_PO_NEW  	= $rowAllD->TOT_PO_NEW;
						$TOT_PO_CON   	= $rowAllD->TOT_PO_CON;
						$TOT_PO_APP   	= $rowAllD->TOT_PO_APP;
						$TOT_PO_REV   	= $rowAllD->TOT_PO_REV;
						$TOT_PO_REJ   	= $rowAllD->TOT_PO_REJ;
						$TOT_PO_CLS  	= $rowAllD->TOT_PO_CLS;
					// IR
						$TOT_IR     	= $rowAllD->TOT_IR;
						$TOT_IR_NEW   	= $rowAllD->TOT_IR_NEW;
						$TOT_IR_CON   	= $rowAllD->TOT_IR_CON;
						$TOT_IR_APP   	= $rowAllD->TOT_IR_APP;
						$TOT_IR_REV   	= $rowAllD->TOT_IR_REV;
						$TOT_IR_REJ   	= $rowAllD->TOT_IR_REJ;
						$TOT_IR_CLS   	= $rowAllD->TOT_IR_CLS;
					// PINV
						$TOT_PINV   	= $rowAllD->TOT_PINV;
						$TOT_PINV_NEW 	= $rowAllD->TOT_PINV_NEW;
						$TOT_PINV_CON 	= $rowAllD->TOT_PINV_CON;
						$TOT_PINV_HP  	= $rowAllD->TOT_PINV_HP;
						$TOT_PINV_FP  	= $rowAllD->TOT_PINV_FP;
						$TOT_PINV_REJ 	= $rowAllD->TOT_PINV_REJ;
						$TOT_INV    	= $rowAllD->TOT_INV;
						$TOT_INV_FP   	= $rowAllD->TOT_INV_FP;
						$TOT_INV_NP   	= $rowAllD->TOT_INV_NP;
					// CB
						$TOT_CB     	= $rowAllD->TOT_CB;
						$TOT_CB_NEW   	= $rowAllD->TOT_CB_NEW;
						$TOT_CB_CON   	= $rowAllD->TOT_CB_CON;
						$TOT_CB_APP   	= $rowAllD->TOT_CB_APP;
						$TOT_CB_REV   	= $rowAllD->TOT_CB_REV;
						$TOT_CB_REJ   	= $rowAllD->TOT_CB_REJ;
						$TOT_CB_CLS   	= $rowAllD->TOT_CB_CLS;
					// OFF LETTER
						$TOT_OFF     	= $rowAllD->TOT_OFF;
						$TOT_OFF_NEW   	= $rowAllD->TOT_OFF_NEW;
						$TOT_OFF_CON   	= $rowAllD->TOT_OFF_CON;
						$TOT_OFF_APP   	= $rowAllD->TOT_OFF_APP;
						$TOT_OFF_REV   	= $rowAllD->TOT_OFF_REV;
						$TOT_OFF_REJ   	= $rowAllD->TOT_OFF_REJ;
						$TOT_OFF_CLS   	= $rowAllD->TOT_OFF_CLS;
					// SO
						$TOT_SO     	= $rowAllD->TOT_SO;
						$TOT_SO_NEW   	= $rowAllD->TOT_SO_NEW;
						$TOT_SO_CON   	= $rowAllD->TOT_SO_CON;
						$TOT_SO_APP   	= $rowAllD->TOT_SO_APP;
						$TOT_SO_REV   	= $rowAllD->TOT_SO_REV;
						$TOT_SO_REJ   	= $rowAllD->TOT_SO_REJ;
						$TOT_SO_CLS   	= $rowAllD->TOT_SO_CLS;
					// WO
						$TOT_WO     	= $rowAllD->TOT_WO;
						$TOT_WO_NEW   	= $rowAllD->TOT_WO_NEW;
						$TOT_WO_CON   	= $rowAllD->TOT_WO_CON;
						$TOT_WO_APP   	= $rowAllD->TOT_WO_APP;
						$TOT_WO_REV   	= $rowAllD->TOT_WO_REV;
						$TOT_WO_REJ   	= $rowAllD->TOT_WO_REJ;
						$TOT_WO_CLS   	= $rowAllD->TOT_WO_CLS;
					// MR
						$TOT_MR     	= $rowAllD->TOT_MR;
						$TOT_MR_NEW   	= $rowAllD->TOT_MR_NEW;
						$TOT_MR_CON   	= $rowAllD->TOT_MR_CON;
						$TOT_MR_APP   	= $rowAllD->TOT_MR_APP;
						$TOT_MR_REV   	= $rowAllD->TOT_MR_REV;
						$TOT_MR_REJ   	= $rowAllD->TOT_MR_REJ;
						$TOT_MR_CLS   	= $rowAllD->TOT_MR_CLS;
					// SN
						$TOT_SN     	= $rowAllD->TOT_SN;
						$TOT_SN_NEW   	= $rowAllD->TOT_SN_NEW;
						$TOT_SN_CON   	= $rowAllD->TOT_SN_CON;
						$TOT_SN_APP   	= $rowAllD->TOT_SN_APP;
						$TOT_SN_REV   	= $rowAllD->TOT_SN_REV;
						$TOT_SN_REJ   	= $rowAllD->TOT_SN_REJ;
						$TOT_SN_CLS   	= $rowAllD->TOT_SN_CLS;
				endforeach;
			}

    	// GET HR DASH SETTING
			$EMP_TOT    	= 0;
			$EMP_ACT   	 	= 0;
			$EMP_NACT   	= 0;
			$EMP_NEW    	= 0;
			$GRP_EMP_TOT  	= 0;
			$GRP_EMP_GEND 	= 0;
			$GRP_EMP_AGE  	= 0;
			$GRP_EMP_POS  	= 0;
			
			$DHR_TOTEMP   	= 0;
			$DHR_TOTACT  	= 0;
			$DHR_TOTNACT  	= 0;
			$DHR_TOTM   	= 0;
			$DHR_TOTF   	= 0;
			$DHR_TOTNEW   	= 0;
			$DHR_TOT30    	= 0;
			$DHR_TOT40    	= 0;
			$DHR_TOT50    	= 0;
			$DHR_TOTBOD   	= 0;
			$DHR_TOTGM    	= 0;
			$DHR_TOTMNG   	= 0;
			$DHR_TOTKEPU  	= 0;
			$DHR_TOTPM    	= 0;
			$DHR_TOTKU    	= 0;
			$DHR_TOTSM    	= 0;
			$DHR_TOTSPEC  	= 0;
			$DHR_TOTSTF   	= 0;
			$DHR_TOTNSTF  	= 0;

		$sqlAllDBHR   = "SELECT * FROM tbl_dash_hr";
		$resAllDBHR   = $this->db->query($sqlAllDBHR)->result();
		foreach($resAllDBHR as $rowDBHR) :
			$DHR_TOTEMP   = $rowDBHR->DHR_TOTEMP;
			$DHR_TOTACT   = $rowDBHR->DHR_TOTACT;
			$DHR_TOTNACT  = $rowDBHR->DHR_TOTNACT;
			$DHR_TOTM     = $rowDBHR->DHR_TOTM;
			$DHR_TOTF     = $rowDBHR->DHR_TOTF;
			$DHR_TOTNEW   = $rowDBHR->DHR_TOTNEW;
			$DHR_TOT30    = $rowDBHR->DHR_TOT30;
			$DHR_TOT40    = $rowDBHR->DHR_TOT40;
			$DHR_TOT50    = $rowDBHR->DHR_TOT50;
			$DHR_TOTBOD   = $rowDBHR->DHR_TOTBOD;
			$DHR_TOTGM    = $rowDBHR->DHR_TOTGM;
			$DHR_TOTMNG   = $rowDBHR->DHR_TOTMNG;
			$DHR_TOTKEPU  = $rowDBHR->DHR_TOTKEPU;
			$DHR_TOTPM    = $rowDBHR->DHR_TOTPM;
			$DHR_TOTKU    = $rowDBHR->DHR_TOTKU;
			$DHR_TOTSM    = $rowDBHR->DHR_TOTSM;
			$DHR_TOTSPEC  = $rowDBHR->DHR_TOTSPEC;
			$DHR_TOTSTF   = $rowDBHR->DHR_TOTSTF;
			$DHR_TOTNSTF  = $rowDBHR->DHR_TOTNSTF;
      	endforeach;

      	$sqlDBHR  = "SELECT DS_TYPE AS DS_TYPEHR FROM tbl_dash_sett_hr_emp WHERE EMP_ID = '$Emp_ID'";
      	$resDBHR  = $this->db->query($sqlDBHR)->result();
      	foreach($resDBHR as $rowDBHR) :
			$DS_TYPEHR  = $rowDBHR->DS_TYPEHR;
			if($DS_TYPEHR == "EMP_TOT")
			{
			  $EMP_TOT  = 1;
			  if($DHR_TOTEMP == '')
				$DHR_TOTEMP = 0;
			}
			elseif($DS_TYPEHR == "EMP_ACT")
			{
			  $EMP_ACT  = 1;
			  if($DHR_TOTACT == '')
				$DHR_TOTACT = 0;
			}
			elseif($DS_TYPEHR == "EMP_NACT")
			{
			  $EMP_NACT = 1;
			  if($DHR_TOTNACT == '')
				$DHR_TOTNACT = 0;
			}
			elseif($DS_TYPEHR == "EMP_NEW")
			{
			  $EMP_NEW  = 1;
			  if($DHR_TOTNEW == '')
				$DHR_TOTNEW = 0;
			}
			elseif($DS_TYPEHR == "GRP_EMP_TOT")
			{
			  $GRP_EMP_TOT  = 1;
			  $TOT_GRP_EMP  = $DHR_TOTACT + $DHR_TOTNACT;
			}
			elseif($DS_TYPEHR == "GRP_EMP_GEND")
			{
			  $GRP_EMP_GEND = 1;
			  $TOT_GRP_EMP_GEN= $DHR_TOTM + $DHR_TOTM;
			}
			elseif($DS_TYPEHR == "GRP_EMP_AGE")
			{
			  $GRP_EMP_AGE  = 1;
			  $TOT_GRP_EMP_AGE= $DHR_TOT30 + $DHR_TOT40 + $DHR_TOT50;
			}
			elseif($DS_TYPEHR == "GRP_EMP_POS")
			{
			  $GRP_EMP_POS  = 1;
			  $TOT_GRP_EMP_POS= $DHR_TOTBOD + $DHR_TOTGM + $DHR_TOTMNG + $DHR_TOTKEPU + $DHR_TOTPM + $DHR_TOTKU + $DHR_TOTSM + $DHR_TOTSPEC + $DHR_TOTSTF + $DHR_TOTNSTF;
			}
      	endforeach;

    // GET EMP SETT DS_TYPE
      	$sqlDBoard  = "SELECT DS_TYPE FROM tbl_dash_sett_emp WHERE EMP_ID = '$Emp_ID'";
      	$resDBoard  = $this->db->query($sqlDBoard)->result();
      	foreach($resDBoard as $rowDB) :
	        $DS_TYPE  = $rowDB->DS_TYPE;
	        
	        if($DS_TYPE == "REQ_NEW")
	        {
	          	$REQ_NEW  = 1;
	          	if($TOT_REQ_NEW == '')
	            	$TOT_REQ_NEW = 0;
	        }
	        elseif($DS_TYPE == "REQ_CON")
	        {
	          	$REQ_CON  = 1;
	          	if($TOT_REQ_CON == '')
	            	$TOT_REQ_CON = 0;
	        }
	        elseif($DS_TYPE == "REQ_APP")
	        {
	          	$REQ_APP  = 1;
	          	if($TOT_REQ_APP == '')
	            	$TOT_REQ_APP = 0;
	        }
	        elseif($DS_TYPE == "REQ_CLS")
	        {
	          	$REQ_CLS  = 1;
	          	if($TOT_REQ_CLS == '')
	            	$TOT_REQ_CLS = 0;
	        }
	        elseif($DS_TYPE == "PO_NEW")
	        {
	          	$PO_NEW = 1;
	          	if($TOT_PO_NEW == '')
	            	$TOT_PO_NEW = 0;
	        }
	        elseif($DS_TYPE == "PO_CON")
	        {
	          	$PO_CON = 1;
	          	if($TOT_PO_CON == '')
	            	$TOT_PO_CON = 0;
	        }
	        elseif($DS_TYPE == "PO_APP")
	        {
	         	$PO_APP = 1;
	          	if($TOT_PO_APP == '')
	            	$TOT_PO_APP = 0;
	        }
	        elseif($DS_TYPE == "PO_CLS")
	        {
	          	$PO_CLS = 1;
	          	if($TOT_PO_CLS == '')
	            	$TOT_PO_CLS = 0;
	        }
	        elseif($DS_TYPE == "IR_NEW")
	        {
	          	$IR_NEW = 1;
	          	if($TOT_IR_NEW == '')
	            	$TOT_IR_NEW = 0;
	        }
	        elseif($DS_TYPE == "IR_CON")
	        {
	          	$IR_CON = 1;
	          	if($TOT_IR_CON == '')
	            	$TOT_IR_CON = 0;
	        }
	        elseif($DS_TYPE == "IR_APP")
	        {
	         	$IR_APP = 1;
	          	if($TOT_IR_APP == '')
	            	$TOT_IR_APP = 0;
	        }
	        elseif($DS_TYPE == "IR_CLS")
	        {
	          	$IR_CLS = 1;
	          	if($TOT_IR_CLS == '')
	            	$TOT_IR_CLS = 0;
	        }
	        elseif($DS_TYPE == "PINV_NEW")
	        {
	          	$PINV_NEW = 1;
	          	if($TOT_PINV_NEW == '')
	            	$TOT_PINV_NEW = 0;
	        }
	        elseif($DS_TYPE == "PINV_CON")
	        {
	          	$PINV_CON = 1;
	          	if($TOT_PINV_CON == '')
	            	$TOT_PINV_CON = 0;
	        }
	        elseif($DS_TYPE == "PINV_HP")
	        {
	          	$PINV_HP  = 1;
	          	if($TOT_PINV_HP == '')
	            	$TOT_PINV_HP = 0;
	        }
	        elseif($DS_TYPE == "PINV_FP")
	        {
	          	$PINV_FP  = 1;
	          	if($TOT_PINV_FP == '')
	            	$TOT_PINV_FP = 0;
	        }
	        elseif($DS_TYPE == "PINV_REJ")
	        {
	          	$PINV_REJ = 1;
	          	if($TOT_PINV_REJ == '')
	            	$TOT_PINV_REJ = 0;
	        }
	        elseif($DS_TYPE == "CB_NEW")
	        {
	          	$CB_NEW = 1;
	          	if($TOT_CB_NEW == '')
	            	$TOT_CB_NEW = 0;
	        }
	        elseif($DS_TYPE == "CB_CON")
	        {
	          	$CB_CON = 1;
	          	if($TOT_CB_CON == '')
	            	$TOT_CB_CON = 0;
	        }
	        elseif($DS_TYPE == "CB_APP")
	        {
	         	$CB_APP = 1;
	          	if($TOT_CB_APP == '')
	            	$TOT_CB_APP = 0;
	        }
	        elseif($DS_TYPE == "CB_CLS")
	        {
	          	$CB_CLS = 1;
	          	if($TOT_CB_CLS == '')
	            	$TOT_CB_CLS = 0;
	        }
	        elseif($DS_TYPE == "OFF_NEW")
	        {
	          	$OFF_NEW = 1;
	          	if($TOT_OFF_NEW == '')
	            	$TOT_OFF_NEW = 0;
	        }
	        elseif($DS_TYPE == "OFF_CON")
	        {
	          	$OFF_CON = 1;
	          	if($TOT_OFF_CON == '')
	            	$TOT_OFF_CON = 0;
	        }
	        elseif($DS_TYPE == "OFF_APP")
	        {
	         	$OFF_APP = 1;
	          	if($TOT_OFF_APP == '')
	            	$TOT_OFF_APP = 0;
	        }
	        elseif($DS_TYPE == "OFF_CLS")
	        {
	          	$OFF_CLS = 1;
	          	if($TOT_OFF_CLS == '')
	            	$TOT_OFF_CLS = 0;
	        }
	        elseif($DS_TYPE == "SO_NEW")
	        {
	          	$SO_NEW = 1;
	          	if($TOT_SO_NEW == '')
	            	$TOT_SO_NEW = 0;
	        }
	        elseif($DS_TYPE == "SO_CON")
	        {
	          	$SO_CON = 1;
	          	if($TOT_SO_CON == '')
	            	$TOT_SO_CON = 0;
	        }
	        elseif($DS_TYPE == "SO_APP")
	        {
	         	$SO_APP = 1;
	          	if($TOT_SO_APP == '')
	            	$TOT_SO_APP = 0;
	        }
	        elseif($DS_TYPE == "SO_CLS")
	        {
	          	$SO_CLS = 1;
	          	if($TOT_SO_CLS == '')
	            	$TOT_SO_CLS = 0;
	        }
	        elseif($DS_TYPE == "GRF_LOGHIST")
	        {
				$sqlLogH  = "SELECT LCONC_NEVER, LCONC_SOMET, LCONC_OFTEN, LCONC_FANTASTIC FROM tbl_login_concl";
				$resLogH  = $this->db->query($sqlLogH)->result();
				foreach($resLogH as $rowLH) :
					$LCONC_NEVER  = $rowLH->LCONC_NEVER;
					$LCONC_SOMET  = $rowLH->LCONC_SOMET;
					$LCONC_OFTEN  = $rowLH->LCONC_OFTEN;
					$LCONC_FANT   = $rowLH->LCONC_FANTASTIC;
				endforeach;     
				$GRF_LOGHIST  = 1;
	        }
	        elseif($DS_TYPE == "GRF_SALES")
	        {
				$GRF_SALES	= 1;
	        }
	        elseif($DS_TYPE == "GRF_PURCH")
	        {
	        	$GRF_PURCH    = 1;
	        }
	        elseif($DS_TYPE == "GRF_BP")
	        {
	        	$GRF_BP    	= 1;
	        }
	        elseif($DS_TYPE == "GRF_GOALC")
	        {
	        	$GRF_GOALC	= 1;
	        }
      	endforeach;
	    
    $showAllOUT = site_url('__180c2f/prjlist/?id='.$this->url_encryption_helper->encode_url($appName));
    $showAboutC = site_url('__180c2f/aboutcomp/?id='.$this->url_encryption_helper->encode_url($appName));
    $GraphicTitleText = 3;
?>
<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <title>AdminLTE 2 | Dashboard</title>
	    <!-- Tell the browser to be responsive to screen width -->
	    <?php
		    $vers     = $this->session->userdata['vers'];

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk  = $rowcss->cssjs_lnk;
	            ?>
	                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
	            <?php
	        endforeach;

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk1  = $rowcss->cssjs_lnk;
	            ?>
	                <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
	            <?php
        	endforeach;
		?>

		<!-- Google Font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>

	<script>
		$(document).ready(function(){
		$(".preloader").fadeOut();
		})

		function chooseProject(thisVal)
		{
			sssss	= thisVal.value;
			projCode = document.getElementById('selPRJCODE').value;
			document.frmsrch.submitPRJ.click();
		}
	</script>
	<style type="text/css">
		.preloader {
		  position: fixed;
		  top: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  z-index: 9999;
		  background-color: #fff;
		}
		.preloader .loading {
		  position: absolute;
		  left: 50%;
		  top: 50%;
		  transform: translate(-50%,-50%);
		  font: 14px arial;
		}
		canvas{
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
	</style>
	<?php
		// Top Bar
  			$this->load->view('template/topbar');

		// Left side column. contains the logo and sidebar
  			$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];	
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$Translate	= $rowTransl->LangTransl;
			if($TranslCode == 'Dashboard')$Dashboard = $Translate;
			if($TranslCode == 'DailyNews')$DailyNews = $Translate;
			if($TranslCode == 'Supply')$Supply = $Translate;
			if($TranslCode == 'WIP')$WIP = $Translate;
			if($TranslCode == 'Waste')$Waste = $Translate;
			if($TranslCode == 'Sales')$Sales = $Translate;
			if($TranslCode == 'SalesOrder')$SalesOrder = $Translate;
			if($TranslCode == 'Production')$Production = $Translate;
		endforeach;
		
		$sql001   	= "SELECT browsername FROM browser";
		$res001 	= $this->db->query($sql001)->result();
		foreach($res001 as $row001) :
				$browsername 	= $row001->browsername;
			$trendbrowser	= $browsername;    
			$sql002   	= "SELECT total FROM browser WHERE browsername = '$trendbrowser'";
			$res002 	= $this->db->query($sql002)->result();
			foreach($res002 as $row002) :
				$total 	= $row002->total;
			endforeach;
		endforeach;
		
		$thisDay	= (int)date('d');
		$modDay		= $thisDay % 6;
		if($modDay == 0)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading.gif";
		}
		elseif($modDay == 1)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif";
		}
		elseif($modDay == 2)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading2.gif";
		}
		elseif($modDay == 3)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading3.gif";
		}
		elseif($modDay == 4)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading4.gif";
		}
		elseif($modDay == 5)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading5.gif";
		}

		if($LangID == 'IND')
		{
			$transL1	= "Mengaktfikan 'Fixed Layout'. Anda tidak dapat menggunakan 'Fixed Layout' dan 'Boxed Layout' bersama-sama.";
			$transL2	= "Mengaktifkan 'Boxed Layout'.";
			$transL3	= "Sembunyikan menu utama sisi kiri.";
			$transL4	= "Penjualan Confirmed vs Approved";
			$transL5	= "Aktifitas";
		}
		else
		{
			$transL1	= "Activate the 'Fixed Layout'. You can't use 'Fixed Layout' and 'Boxed Layout' together.";
			$transL2	= "Activate the boxed layout";
			$transL3	= "Toggle the left sidebar's state.";
			$transL4	= "Sales Graph";
			$transL5	= "Activity Graph";
		}
	?>
	<body class="<?php echo $appBody; ?>" onLoad="StartTimers();" onmousemove="ResetTimers();">
		<form name="formLang" id="formLang" method="post" style="display:none">
		    <input type="text" name="LangID" id="LangID" value="<?php echo $LangID; ?>">
		    <input type="submit" name="submit1" id="submit1" value="OK">
		</form>	
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
			        <?php echo $Dashboard; ?>
			        <small>home</small>
					<?php
						$getCount		= "tbl_employee_proj WHERE Emp_ID = '$Emp_ID'";
						$resGetCount	= $this->db->count_all($getCount);
					?>
			    </h1>
			</section>

		    <section class="content">
				<div class="row">
		            <?php if($REQ_NEW == 1) { if($TOT_REQ_NEW > 20) { $COL_REQ_NEW = "red"; } else if($TOT_REQ_NEW > 10) { $COL_REQ_NEW = "red"; } else { $COL_REQ_NEW = "red";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_REQ_NEW; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_REQ_NEW; ?></h3>
		                            New -<br />Requisition
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-bag"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($REQ_CON == 1) { if($TOT_REQ_CON > 20) { $COL_REQ_CON = "yellow"; } else if($TOT_REQ_CON > 10) { $COL_REQ_CON = "yellow"; } else { $COL_REQ_CON = "yellow";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_REQ_CON; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_REQ_CON; ?></h3>
		                            Outstanding Approve -<br />Requisition
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-bag"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($REQ_APP == 1) { if($TOT_REQ_APP > 20) { $COL_REQ_APP = "green"; } else if($TOT_REQ_APP > 10) { $COL_REQ_APP = "green"; } else { $COL_REQ_APP = "green";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_REQ_APP; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_REQ_APP; ?></h3>
		                            Approve -<br />Requisition
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-bag"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($REQ_CLS == 1) { if($TOT_REQ_CLS > 20) { $COL_REQ_CLS = "primary"; } else if($TOT_REQ_CLS > 10) { $COL_REQ_CLS = "primary"; } else { $COL_REQ_CLS = "primary";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_REQ_CLS; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_REQ_CLS; ?></h3>
		                            Close -<br />Requisition
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-bag"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($PO_NEW == 1) { if($TOT_PO_NEW > 10) { $COL_PO_NEW = "red"; } else if($TOT_PO_NEW > 0) { $COL_PO_NEW = "red"; } else { $COL_PO_NEW = "red";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_PO_NEW; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_PO_NEW; ?></h3>
		                            New -<br />Purchase Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-ios-cart-outline"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($PO_CON == 1) { if($TOT_PO_CON > 10) { $COL_PO_CON = "yellow"; } else if($TOT_PO_CON > 0) { $COL_PO_CON = "yellow"; } else { $COL_PO_CON = "yellow";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_PO_CON; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_PO_CON; ?></h3>
		                            Outstanding Approve -<br />Purchase Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-ios-cart-outline"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($PO_APP == 1) { if($TOT_PO_APP > 10) { $COL_PO_APP = "green"; } else if($TOT_PO_APP > 0) { $COL_PO_APP = "green"; } else { $COL_PO_APP = "green";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_PO_APP; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_PO_APP; ?></h3>
		                            Approve -<br />Purchase Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-ios-cart-outline"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($PO_CLS == 1) { if($TOT_PO_CLS > 10) { $COL_PO_CLS = "primary"; } else if($TOT_PO_CLS > 0) { $COL_PO_CLS = "primary"; } else { $COL_PO_CLS = "primary";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_PO_CLS; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_PO_CLS; ?></h3>
		                            Close -<br />Purchase Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-ios-cart-outline"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($IR_NEW == 1) { if($TOT_IR_NEW > 10) { $COL_IR_NEW = "red"; } else if($TOT_IR_NEW > 0) { $COL_IR_NEW = "red"; } else { $COL_IR_NEW = "red";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_IR_NEW; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_IR_NEW; ?></h3>
		                            New -<br />Item Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($IR_CON == 1) { if($TOT_IR_CON > 10) { $COL_IR_CON = "yellow"; } else if($TOT_IR_CON > 0) { $COL_IR_CON = "yellow"; } else { $COL_IR_CON = "yellow";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_IR_CON; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_IR_CON; ?></h3>
		                            Outstanding Approve -<br />Item Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($IR_APP == 1) { if($TOT_IR_APP > 10) { $COL_IR_APP = "green"; } else if($TOT_IR_APP > 0) { $COL_IR_APP = "green"; } else { $COL_IR_APP = "green";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_IR_APP; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_IR_APP; ?></h3>
		                            Approve -<br />Item Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($IR_CLS == 1) { if($TOT_IR_CLS > 10) { $COL_IR_CLS = "primary"; } else if($TOT_IR_CLS > 0) { $COL_IR_CLS = "primary"; } else { $COL_IR_CLS = "primary";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_IR_CLS; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_IR_CLS; ?></h3>
		                            Close -<br />Item Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($PINV_NEW == 1) { if($TOT_PINV_NEW > 10) { $COL_PINV_NEW = "red"; } else if($TOT_PINV_NEW > 0) { $COL_PINV_NEW = "red"; } else { $COL_PINV_NEW = "red";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_PINV_NEW; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_PINV_NEW; ?></h3>
		                            New -<br />Purchase Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion wand"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($PINV_CON == 1) { if($TOT_PINV_CON > 10) { $COL_PINV_CON = "yellow"; } else if($TOT_PINV_CON > 0) { $COL_PINV_CON = "yellow"; } else { $COL_PINV_CON = "yellow";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_PINV_CON; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_PINV_CON; ?></h3>
		                            Outstanding -<br />Purchase Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($PINV_HP == 1) { if($TOT_PINV_HP > 10){ $COL_PINV_HP = "green"; } else if($TOT_PINV_HP > 0) { $COL_PINV_HP = "green"; } else { $COL_PINV_HP = "green";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_PINV_HP; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_PINV_HP; ?></h3>
		                            Half Paid -<br />Purchase Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } if($PINV_FP == 1) { if($TOT_PINV_FP > 10){ $COL_PINV_FP = "primary"; } else if($TOT_PINV_FP > 0) { $COL_PINV_FP = "primary"; } else { $COL_PINV_FP = "primary";} ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-<?php echo $COL_PINV_FP; ?>">
		                        <div class="inner">
		                            <h3><?php echo $TOT_PINV_FP; ?></h3>
		                            Full Paid -<br />Purchase Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>                
		            <?php /* dashboard1_190514 Line 2160 - 2215; */ ?>
		            <?php } $EMP_TOT = 1; if($EMP_TOT == 1) { ?>
		                <div class="col-lg-3 col-xs-6">
		                    <div class="small-box bg-aqua">
		                        <div class="inner">
		                            <h3><?php echo $DHR_TOTACT; ?></h3>
		                            Employe -<br />Total Employee
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-contacts"></i>
		                        </div>
		                        <a href="<?php echo $showAllOUT; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		                    </div>
		                </div>
		            <?php } ?>
		        </div>
		      	<div class="row">
			        <section class="col-lg-7 connectedSortable">
			        	<?php if($GRF_SALES == 1) { ?>
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs pull-right">
									<li style="display: none;" class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
									<li style="display: none;"><a href="#sales-chart" data-toggle="tab">Donut</a></li>
									<li class="pull-left header"><i class="fa fa-opencart"></i><?php echo $transL4; ?></li>
								</ul>
								<div class="tab-content no-padding">
									<div class="chart tab-pane active" id="revenue-chart1" style="position: relative; height: 300px;"></div>
									<div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
								</div>
							</div>
						<?php } ?>

			        	<div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
		                    <div class="info-box bg-aqua">
		                        <span class="info-box-icon"><i class="ion ion-cube"></i></span>
		                        <div class="info-box-content">
		                            <span class="info-box-text"><?php echo $Supply; ?></span>
		                            <span class="info-box-number">41,410</span>
		                            
		                            <div class="progress">
		                                <div class="progress-bar" style="width: 70%"></div>
		                            </div>
		                            <span class="progress-description">
		                            70% Increase in 30 Days
		                            </span>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
		                    <div class="info-box bg-yellow">
		                        <span class="info-box-icon"><i class="ion ion-aperture"></i></span>
		                        <div class="info-box-content">
		                            <span class="info-box-text"><?php echo $WIP; ?></span>
		                            <span class="info-box-number">41,410</span>
		                            
		                            <div class="progress">
		                                <div class="progress-bar" style="width: 70%"></div>
		                            </div>
		                            <span class="progress-description">
		                            70% Increase in 30 Days
		                            </span>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
		                    <div class="info-box bg-red">
		                        <span class="info-box-icon"><i class="ion ion-trash-a"></i></span>
		                        <div class="info-box-content">
		                            <span class="info-box-text"><?php echo $Waste; ?></span>
		                            <span class="info-box-number">41,410</span>
		                            
		                            <div class="progress">
		                                <div class="progress-bar" style="width: 70%"></div>
		                            </div>
		                            <span class="progress-description">
		                            70% Increase in 30 Days
		                            </span>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
		                    <div class="info-box bg-green">
		                        <span class="info-box-icon"><i class="ion ion-social-usd-outline"></i></span>
		                        
		                        <div class="info-box-content">
		                            <span class="info-box-text"><?php echo $Sales; ?></span>
		                            <span class="info-box-number">41,410</span>
		                            
		                            <div class="progress">
		                                <div class="progress-bar" style="width: 50%"></div>
		                            </div>
		                            <span class="progress-description">
		                            70% Increase in 30 Days
		                            </span>
		                        </div>
		                    </div>
		                </div>

		                <?php if($GRF_GOALC == 1) { ?>
			                <div class="col-md-12">
			                    <p class="text-center">
			                    	<strong><br>Goal Completion</strong>
			                    </p>
			                    <div class="progress-group">
			                        <span class="progress-text"><?php echo $SalesOrder; ?></span>
			                        <span class="progress-number"><b>160</b>/200</span>
			                        
			                    	<div class="progress sm">
			                            <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
			                        </div>
			                    </div>
			                    <div class="progress-group">
			                        <span class="progress-text">Job Order</span>
			                        <span class="progress-number"><b>310</b>/400</span>
			                        
			                        <div class="progress sm">
			                            <div class="progress-bar progress-bar-red" style="width: 60%"></div>
			                        </div>
			                    </div>
			                    <div class="progress-group">
			                        <span class="progress-text"><?php echo $Production; ?></span>
			                        <span class="progress-number"><b>480</b>/800</span>
			                        
			                        <div class="progress sm">
			                        	<div class="progress-bar progress-bar-green" style="width: 90%"></div>
			                    	</div>
			                    </div>
			                </div>
		                <?php } ?>
	            	</section>

			        <section class="col-lg-5 connectedSortable">
			        	<?php if($GRF_LOGHIST == 1) { ?>
				          	<div class="box box-solid bg-teal-gradient">
								<div class="box-header">
									<i class="fa fa-th"></i>

									<h3 class="box-title"><?php echo $transL5; ?></h3>

									<div class="box-tools pull-right">
										<button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
										</button>
										<button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
										</button>
									</div>
								</div>
					            <div class="box-body border-radius-none">
					            	<div class="chart" id="line-chart1" style="height: 250px;"></div>
					            </div>

				            	<div class="box-footer no-border">
									<div class="row">
										<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
											<input type="text" class="knob" data-readonly="true" value="80" data-width="60" data-height="60" data-fgColor="#00BEEE">
											<div class="knob-label">Aktif</div>
										</div>

										<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
											<input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#00A65B">
											<div class="knob-label">Online</div>
										</div>

										<div class="col-xs-4 text-center">
											<input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#F39F12">
											<div class="knob-label">Offline</div>
										</div>
				              		</div>
				            	</div>
				          	</div>
			          	<?php } ?>

			         	<div class="box box-solid bg-green-gradient">
							<div class="box-header">
								<i class="fa fa-calendar"></i>
								<h3 class="box-title">Calendar</h3>
								<div class="pull-right box-tools">
									<div class="btn-group" style="display: none;">
										<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-bars"></i></button>
										<ul class="dropdown-menu pull-right" role="menu">
											<li><a href="#">Add new event ss</a></li>
											<li><a href="#">Clear events</a></li>
											<li class="divider"></li>
											<li><a href="#">View calendar</a></li>
										</ul>
									</div>
									<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
			            	</div>

				            <div class="box-body no-padding">
				             	<div id="calendar" style="width: 100%"></div>
				            </div>

				            <div class="box-footer text-black" style="display: none;">
								<div class="row">
									<div class="col-sm-6">
										<div class="clearfix">
											<span class="pull-left">Task #1</span>
											<small class="pull-right">90%</small>
										</div>
										<div class="progress xs">
											<div class="progress-bar progress-bar-green" style="width: 90%;"></div>
										</div>

										<div class="clearfix">
											<span class="pull-left">Task #2</span>
											<small class="pull-right">70%</small>
										</div>
										<div class="progress xs">
											<div class="progress-bar progress-bar-green" style="width: 70%;"></div>
										</div>
									</div>

									<div class="col-sm-6">
										<div class="clearfix">
											<span class="pull-left">Task #3</span>
											<small class="pull-right">60%</small>
										</div>
										<div class="progress xs">
											<div class="progress-bar progress-bar-green" style="width: 60%;"></div>
										</div>

										<div class="clearfix">
											<span class="pull-left">Task #4</span>
											<small class="pull-right">40%</small>
										</div>
										<div class="progress xs">
											<div class="progress-bar progress-bar-green" style="width: 40%;"></div>
										</div>
									</div>
								</div>
							</div>
			          	</div>
			        </section>
		      	</div>
	    	</section>
	  	</div>
		<footer class="main-footer">
			<div class="pull-right hidden-xs">
				<b>Version</b> 2.4.18
			</div>
			<strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights reserved.
		</footer>

	  	<div class="control-sidebar-bg"></div>
	</body>
</html>
<script>
	$(function () {
	"use strict";

	// AREA CHART
		<?php if($GRF_SALES == 1) { ?>
		var area = new Morris.Area({
			element: 'revenue-chart1',
			resize: true,
			data: [
				<?php
					/*$sqlSOMNTH	= "SELECT DISTINCT MONTH (B.SO_DATE) AS M_SO, YEAR (B.SO_DATE) AS Y_SO
									FROM
										tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM AND B.SO_STAT IN (2,3)";*/
					$sqlSOMNTH	= "SELECT DISTINCT YEAR (B.SO_DATE) AS Y_SO, QUARTER(B.SO_DATE) AS QUART
									FROM
										tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM AND B.SO_STAT IN (2,3)
									ORDER BY YEAR(B.SO_DATE) DESC LIMIT 3";
					$resSOMNTH	= $this->db->query($sqlSOMNTH)->result();
					foreach($resSOMNTH as $rowSOMNTH):
						$Y_SO 		= $rowSOMNTH->Y_SO;
						$QUART 		= $rowSOMNTH->QUART;

						$M_SO2		= 0;
						$sqlSOQTY2	= "SELECT IF(ISNULL(SUM(SO_VOLM)), 0, SUM(SO_VOLM)) AS SOQTY
										FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
											AND QUARTER(B.SO_DATE) = $QUART AND YEAR(B.SO_DATE) = $Y_SO AND B.SO_STAT = 2";
						$resSOQTY2	= $this->db->query($sqlSOQTY2)->result();
						foreach($resSOQTY2 as $rowSOQTY2):
							$M_SO2 	= $rowSOQTY2->SOQTY;
						endforeach;

						$M_SO3		= 0;
						$sqlSOQTY3	= "SELECT IF(ISNULL(SUM(SO_VOLM)), 0, SUM(SO_VOLM)) AS SOQTY
										FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
											AND QUARTER(B.SO_DATE) = $QUART AND YEAR(B.SO_DATE) = $Y_SO AND B.SO_STAT = 3";
						$resSOQTY3	= $this->db->query($sqlSOQTY3)->result();
						foreach($resSOQTY3 as $rowSOQTY3):
							$M_SO3 	= $rowSOQTY3->SOQTY;
						endforeach;

						$QRT 	= "Q".$QUART;
						$year1 	= "'$Y_SO $QRT'";
						?>
							{y: <?php echo $year1; ?>, so2qty: <?php echo $M_SO2; ?>, so3qty: <?php echo $M_SO3; ?>},
						<?php
					endforeach;
				?>
				/*{y: '2011 Q1', so2qty: 3750, so3qty: 3750},
				{y: '2011 Q2', so2qty: 2778, so3qty: 2294},
				{y: '2011 Q3', so2qty: 4912, so3qty: 1969},
				{y: '2011 Q4', so2qty: 3767, so3qty: 3597},
				{y: '2012 Q1', so2qty: 6810, so3qty: 1914},
				{y: '2012 Q2', so2qty: 5670, so3qty: 4293},
				{y: '2012 Q3', so2qty: 4820, so3qty: 3795},
				{y: '2012 Q4', so2qty: 15073, so3qty: 5967},
				{y: '2013 Q1', so2qty: 10687, so3qty: 4460},
				{y: '2013 Q2', so2qty: 8432, so3qty: 5713},*/
			],
			xkey: 'y',
			ykeys: ['so2qty', 'so3qty'],
			labels: ['Confirmed', 'Approved'],
			lineColors: ['#a0d0e0', '#3c8dbc'],
			hideHover: 'auto'
		});
	<?php } ?>

	// LINE CHART
		var line = new Morris.Line({
			element: 'line-chart1',
			resize: true,
			data: [
				{y: '2011 Q1', soqty: 2666},
				{y: '2011 Q2', soqty: 2778},
				{y: '2011 Q3', soqty: 4912},
				{y: '2011 Q4', soqty: 3767},
				{y: '2012 Q1', soqty: 6810},
				{y: '2012 Q2', soqty: 5670},
				{y: '2012 Q3', soqty: 4820},
				{y: '2012 Q4', soqty: 15073},
				{y: '2013 Q1', soqty: 10687},
				{y: '2013 Q2', soqty: 8432}
			],
			xkey: 'y',
			ykeys: ['soqty'],
			labels: ['Item 1'],
			lineColors: ['#3c8dbc'],
			hideHover: 'auto'
		});
	});
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

	// Right side column. contains the Control Panel
	$this->load->view('template/aside');

	$this->load->view('template/js_data');

	$this->load->view('template/foot');
?>