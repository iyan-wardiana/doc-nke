<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Maret 2017
 * File Name	= M_project_progress.php
 * Location		= -
*/

class M_project_progress extends CI_Model
{
	var $table 	= 'tbl_projprogres';
	var $tableH = 'tbl_galeri_header';
	var $tableD = 'tbl_galeri_detail';
		
	function updatePP($proj_Code, $progress_Type, $progress_Step, $parameters)
	{
    	$Prg_Real		= $parameters['Prg_Real'];
    	$Prg_RealAkum	= $parameters['Prg_RealAkum'];
    	$Prg_Dev		= $parameters['Prg_Dev'];
    	$Prg_ProjNotes	= $parameters['Prg_ProjNotes'];
    	$Prg_PstNotes	= $parameters['Prg_PstNotes'];
    	$Prg_LastUpdate	= $parameters['Prg_LastUpdate'];
		$isShow			= 1;
		$isShowRA		= 1;
		$isShowDev		= 1;	
		
		$sqlUpdPP		= "UPDATE tbl_projprogres SET Prg_Real = $Prg_Real, Prg_RealAkum = $Prg_RealAkum, Prg_Dev = $Prg_Dev,
							Prg_ProjNotes = '$Prg_ProjNotes', Prg_PstNotes = '$Prg_PstNotes', Prg_LastUpdate = '$Prg_LastUpdate',
							isShow = 1, isShowRA = 1, isShowDev = 1, lastStepPS = 1
							WHERE proj_Code = '$proj_Code' AND progress_Type = $progress_Type AND Prg_Step = $progress_Step";
		$this->db->query($sqlUpdPP);
	}
	
	function addHeaderPic($insPicHeader)
	{
		$this->db->insert($this->tableH, $insPicHeader);
	}
	
	function addDetailPic($insPicDetail)
	{
		$this->db->insert($this->tableD, $insPicDetail);
	}	
	
	function updatePPPst($proj_Code, $parameters)
	{
		$proj_Code 		= $parameters['proj_Code'];
		$Prg_Year 		= $parameters['Prg_Year'];
		$Prg_Month 		= $parameters['Prg_Month'];
    	$progress_Type 	= $parameters['progress_Type'];
		$Prg_Step 		= $parameters['Prg_Step'];
		//$progress_Date 	= $parameters['progress_Date']; // tidak terpakai
    	$Prg_PstNotes	= $parameters['Prg_PstNotes'];
    	$Prg_LastUpdate	= $parameters['Prg_LastUpdate'];
		$isShow			= 1;
		$isShowRA		= 1;
		$isShowDev		= 1;	
		
		$sqlUpdPPPst	= "UPDATE tbl_projprogres SET Prg_PstNotes = '$Prg_PstNotes' WHERE proj_Code = '$proj_Code' AND progress_Type = $progress_Type AND Prg_Step = $Prg_Step";
		$this->db->query($sqlUpdPPPst);
	}
}
?>