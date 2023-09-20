<?php
require_once APPPATH.'/third_party/spout/src/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Common\Type;

function myerror($error_no, $error_msg){
  	//echo "$error_msg";
}

ini_set('max_execution_time', 0); // to get unlimited php script execution time

if(empty($_SESSION['i'])){
    $_SESSION['i'] = 0;
}

// CHECK STATUS
if(isset($_POST['IMP_CODEX']))
{
	$PRJCODE	= $_POST['PRJCODE'];
	$PRJPERIOD	= $_POST['PRJPERIOD'];
	$COAH_CODEY	= $_POST['IMP_CODEX'];
	$IMP_TYPE	= $_POST['IMP_TYPE'];

	// ---------- START EXCEL_READER2 ----------
		error_reporting(E_ALL ^ E_NOTICE);
		/*error_reporting(0);
		ini_set('display_errors', 0);*/
		/**
		 * A class for reading Microsoft Excel (88/2003) Spreadsheets.
		 *
		 * Version 2.21
		 *
		 * Enhanced and maintained by Matt Kruse < http://mattkruse.com >
		 * Maintained at http://code.google.com/p/php-excel-reader/
		 *
		 * Format parsing and MUCH more contributed by:
		 *    Matt Roxburgh < http://www.roxburgh.me.uk >
		 *
		 * DOCUMENTATION
		 * =============
		 *   http://code.google.com/p/php-excel-reader/wiki/Documentation
		 *
		 * CHANGE LOG
		 * ==========
		 *   http://code.google.com/p/php-excel-reader/wiki/ChangeHistory
		 *
		 * DISCUSSION/SUPPORT
		 * ==================
		 *   http://groups.google.com/group/php-excel-reader-discuss/topics
		 *
		 * --------------------------------------------------------------------------
		 *
		 * Originally developed by Vadim Tkachenko under the name PHPExcelReader.
		 * (http://sourceforge.net/projects/phpexcelreader)
		 * Based on the Java version by Andy Khan (http://www.andykhan.com).  Now
		 * maintained by David Sanders.  Reads only Biff 8 and Biff 8 formats.
		 *
		 * PHP versions 4 and 5
		 *
		 * LICENSE: This source file is subject to version 3.0 of the PHP license
		 * that is available through the world-wide-web at the following URI:
		 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
		 * the PHP License and are unable to obtain it through the web, please
		 * send a note to license@php.net so we can mail you a copy immediately.
		 *
		 * @category   Spreadsheet
		 * @package	Spreadsheet_Excel_Reader
		 * @author	 Vadim Tkachenko <vt@apachephp.com>
		 * @license	http://www.php.net/license/3_0.txt  PHP License 3.0
		 * @version	CVS: $Id: reader.php 18 2008-03-13 12:42:41Z shangxiao $
		 * @link	   http://pear.php.net/package/Spreadsheet_Excel_Reader
		 * @see		OLE, Spreadsheet_Excel_Writer
		 * --------------------------------------------------------------------------
		 */
		
		define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);
		define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);
		define('ROOT_START_BLOCK_POS', 0x30);
		define('BIG_BLOCK_SIZE', 0x200);
		define('SMALL_BLOCK_SIZE', 0x40);
		define('EXTENSION_BLOCK_POS', 0x44);
		define('NUM_EXTENSION_BLOCK_POS', 0x48);
		define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);
		define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);
		define('SMALL_BLOCK_THRESHOLD', 0x1000);
		// property storage offsets
		define('SIZE_OF_NAME_POS', 0x40);
		define('TYPE_POS', 0x42);
		define('START_BLOCK_POS', 0x84);
		define('SIZE_POS', 0x88);
		define('IDENTIFIER_OLE', pack("CCCCCCCC",0xd0,0xcf,0x11,0xe0,0xa1,0xb1,0x1a,0xe1));
		function GetInt4d($data, $pos) {
			$value = ord($data[$pos]) | (ord($data[$pos+1])	<< 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
			if ($value>=4284868284) {
				$value=-2;
			}
			return $value;
		}
		// http://uk.php.net/manual/en/function.getdate.php
		function gmgetdate($ts = null){
			$k = array('seconds','minutes','hours','mday','wday','mon','year','yday','weekday','month',0);
			return(array_comb($k,explode(":",gmdate('s:i:G:j:w:n:Y:z:l:F:U',is_null($ts)?time():$ts))));
			} 
		// Added for PHP4 compatibility
		function array_comb($array1, $array2) {
			$out = array();
			foreach ($array1 as $key => $value) {
				$out[$value] = $array2[$key];
			}
			return $out;
		}
		function v($data,$pos) {
			return ord($data[$pos]) | ord($data[$pos+1])<<8;
		}
		class OLERead {
			var $data = '';
			function OLERead(){	}
			function read($sFileName){
				// check if file exist and is readable (Darko Miljanovic)
				if(!is_readable($sFileName)) {
					$this->error = 1;
					return false;
				}
				$this->data = @file_get_contents($sFileName);
				if (!$this->data) {
					$this->error = 1;
					return false;
				}
				if (substr($this->data, 0, 8) != IDENTIFIER_OLE) {
					$this->error = 1;
					return false;
				}
				$this->numBigBlockDepotBlocks = GetInt4d($this->data, NUM_BIG_BLOCK_DEPOT_BLOCKS_POS);
				$this->sbdStartBlock = GetInt4d($this->data, SMALL_BLOCK_DEPOT_BLOCK_POS);
				$this->rootStartBlock = GetInt4d($this->data, ROOT_START_BLOCK_POS);
				$this->extensionBlock = GetInt4d($this->data, EXTENSION_BLOCK_POS);
				$this->numExtensionBlocks = GetInt4d($this->data, NUM_EXTENSION_BLOCK_POS);
				$bigBlockDepotBlocks = array();
				$pos = BIG_BLOCK_DEPOT_BLOCKS_POS;
				$bbdBlocks = $this->numBigBlockDepotBlocks;
				if ($this->numExtensionBlocks != 0) {
					$bbdBlocks = (BIG_BLOCK_SIZE - BIG_BLOCK_DEPOT_BLOCKS_POS)/4;
				}
				for ($i = 0; $i < $bbdBlocks; $i++) {
					$bigBlockDepotBlocks[$i] = GetInt4d($this->data, $pos);
					$pos += 4;
				}
				for ($j = 0; $j < $this->numExtensionBlocks; $j++) {
					$pos = ($this->extensionBlock + 1) * BIG_BLOCK_SIZE;
					$blocksToRead = min($this->numBigBlockDepotBlocks - $bbdBlocks, BIG_BLOCK_SIZE / 4 - 1);
					for ($i = $bbdBlocks; $i < $bbdBlocks + $blocksToRead; $i++) {
						$bigBlockDepotBlocks[$i] = GetInt4d($this->data, $pos);
						$pos += 4;
					}
					$bbdBlocks += $blocksToRead;
					if ($bbdBlocks < $this->numBigBlockDepotBlocks) {
						$this->extensionBlock = GetInt4d($this->data, $pos);
					}
				}
				// readBigBlockDepot
				$pos = 0;
				$index = 0;
				$this->bigBlockChain = array();
				for ($i = 0; $i < $this->numBigBlockDepotBlocks; $i++) {
					$pos = ($bigBlockDepotBlocks[$i] + 1) * BIG_BLOCK_SIZE;
					//echo "pos = $pos";
					for ($j = 0 ; $j < BIG_BLOCK_SIZE / 4; $j++) {
						$this->bigBlockChain[$index] = GetInt4d($this->data, $pos);
						$pos += 4 ;
						$index++;
					}
				}
				// readSmallBlockDepot();
				$pos = 0;
				$index = 0;
				$sbdBlock = $this->sbdStartBlock;
				$this->smallBlockChain = array();
				while ($sbdBlock != -2) {
				  $pos = ($sbdBlock + 1) * BIG_BLOCK_SIZE;
				  for ($j = 0; $j < BIG_BLOCK_SIZE / 4; $j++) {
					$this->smallBlockChain[$index] = GetInt4d($this->data, $pos);
					$pos += 4;
					$index++;
				  }
				  $sbdBlock = $this->bigBlockChain[$sbdBlock];
				}
				// readData(rootStartBlock)
				$block = $this->rootStartBlock;
				$pos = 0;
				$this->entry = $this->__readData($block);
				$this->__readPropertySets();
			}
			function __readData($bl) {
				$block = $bl;
				$pos = 0;
				$data = '';
				while ($block != -2)  {
					$pos = ($block + 1) * BIG_BLOCK_SIZE;
					$data = $data.substr($this->data, $pos, BIG_BLOCK_SIZE);
					$block = $this->bigBlockChain[$block];
				}
				return $data;
			 }
			function __readPropertySets(){
				$offset = 0;
				while ($offset < strlen($this->entry)) {
					$d = substr($this->entry, $offset, PROPERTY_STORAGE_BLOCK_SIZE);
					$nameSize = ord($d[SIZE_OF_NAME_POS]) | (ord($d[SIZE_OF_NAME_POS+1]) << 8);
					$type = ord($d[TYPE_POS]);
					$startBlock = GetInt4d($d, START_BLOCK_POS);
					$size = GetInt4d($d, SIZE_POS);
					$name = '';
					for ($i = 0; $i < $nameSize ; $i++) {
						$name .= $d[$i];
					}
					$name = str_replace("\x00", "", $name);
					$this->props[] = array (
						'name' => $name,
						'type' => $type,
						'startBlock' => $startBlock,
						'size' => $size);
					if ((strtolower($name) == "workbook") || ( strtolower($name) == "book")) {
						$this->wrkbook = count($this->props) - 1;
					}
					if ($name == "Root Entry") {
						$this->rootentry = count($this->props) - 1;
					}
					$offset += PROPERTY_STORAGE_BLOCK_SIZE;
				}
			}
			function getWorkBook(){
				if ($this->props[$this->wrkbook]['size'] < SMALL_BLOCK_THRESHOLD){
					$rootdata = $this->__readData($this->props[$this->rootentry]['startBlock']);
					$streamData = '';
					$block = $this->props[$this->wrkbook]['startBlock'];
					$pos = 0;
					while ($block != -2) {
						  $pos = $block * SMALL_BLOCK_SIZE;
						  $streamData .= substr($rootdata, $pos, SMALL_BLOCK_SIZE);
						  $block = $this->smallBlockChain[$block];
					}
					return $streamData;
				}else{
					$numBlocks = $this->props[$this->wrkbook]['size'] / BIG_BLOCK_SIZE;
					if ($this->props[$this->wrkbook]['size'] % BIG_BLOCK_SIZE != 0) {
						$numBlocks++;
					}
					if ($numBlocks == 0) return '';
					$streamData = '';
					$block = $this->props[$this->wrkbook]['startBlock'];
					$pos = 0;
					while ($block != -2) {
					  $pos = ($block + 1) * BIG_BLOCK_SIZE;
					  $streamData .= substr($this->data, $pos, BIG_BLOCK_SIZE);
					  $block = $this->bigBlockChain[$block];
					}
					return $streamData;
				}
			}
		}
		//define('SPREADSHEET_EXCEL_READER_BIFF8',			 0x600);
		define('SPREADSHEET_EXCEL_READER_BIFF8',			 0x500);
		define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS',   0x5);
		define('SPREADSHEET_EXCEL_READER_WORKSHEET',		 0x10);
		define('SPREADSHEET_EXCEL_READER_TYPE_BOF',		  0x808);
		define('SPREADSHEET_EXCEL_READER_TYPE_EOF',		  0x0a);
		define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET',   0x85);
		define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION',	0x200);
		define('SPREADSHEET_EXCEL_READER_TYPE_ROW',		  0x208);
		define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL',	   0xd8);
		define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS',	 0x2f);
		define('SPREADSHEET_EXCEL_READER_TYPE_NOTE',		 0x1c);
		define('SPREADSHEET_EXCEL_READER_TYPE_TXO',		  0x1b6);
		define('SPREADSHEET_EXCEL_READER_TYPE_RK',		   0x8e);
		define('SPREADSHEET_EXCEL_READER_TYPE_RK2',		  0x28e);
		define('SPREADSHEET_EXCEL_READER_TYPE_MULRK',		0xbd);
		define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK',	 0xbe);
		define('SPREADSHEET_EXCEL_READER_TYPE_INDEX',		0x20b);
		define('SPREADSHEET_EXCEL_READER_TYPE_SST',		  0xfc);
		define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST',	   0xff);
		define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE',	 0x3c);
		define('SPREADSHEET_EXCEL_READER_TYPE_LABEL',		0x204);
		define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST',	 0xfd);
		define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER',	   0x203);
		define('SPREADSHEET_EXCEL_READER_TYPE_NAME',		 0x18);
		define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY',		0x221);
		define('SPREADSHEET_EXCEL_READER_TYPE_STRING',	   0x208);
		define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA',	  0x406);
		define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2',	 0x6);
		define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT',	   0x41e);
		define('SPREADSHEET_EXCEL_READER_TYPE_XF',		   0xe0);
		define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR',	  0x205);
		define('SPREADSHEET_EXCEL_READER_TYPE_FONT',	  0x0031);
		define('SPREADSHEET_EXCEL_READER_TYPE_PALETTE',	  0x0082);
		define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN',	  0xffff);
		define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);
		define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS',  0xE5);
		define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS' ,	25568);
		define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1804', 24108);
		define('SPREADSHEET_EXCEL_READER_MSINADAY',		  86400);
		define('SPREADSHEET_EXCEL_READER_TYPE_HYPER',	     0x01b8);
		define('SPREADSHEET_EXCEL_READER_TYPE_COLINFO',	     0x8d);
		define('SPREADSHEET_EXCEL_READER_TYPE_DEFCOLWIDTH',  0x55);
		define('SPREADSHEET_EXCEL_READER_TYPE_STANDARDWIDTH', 0x88);
		define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT',	"%s");
		/*
		* Main Class
		*/
		class Spreadsheet_Excel_Reader {
			// MK: Added to make data retrieval easier
			var $colnames = array();
			var $colindexes = array();
			var $standardColWidth = 0;
			var $defaultColWidth = 0;
			function myHex($d) {
				if ($d < 16) return "0" . dechex($d);
				return dechex($d);
			}
			
			function dumpHexData($data, $pos, $length) {
				$info = "";
				for ($i = 0; $i <= $length; $i++) {
					$info .= ($i==0?"":" ") . $this->myHex(ord($data[$pos + $i])) . (ord($data[$pos + $i])>31? "[" . $data[$pos + $i] . "]":'');
				}
				return $info;
			}
			function getCol($col) {
				if (is_string($col)) {
					$col = strtolower($col);
					if (array_key_exists($col,$this->colnames)) {
						$col = $this->colnames[$col];
					}
				}
				return $col;
			}
			// PUBLIC API FUNCTIONS
			// --------------------
			function val($row,$col,$sheet=0) {
				$col = $this->getCol($col);
				if (array_key_exists($row,$this->sheets[$sheet]['cells']) && array_key_exists($col,$this->sheets[$sheet]['cells'][$row])) {
					return $this->sheets[$sheet]['cells'][$row][$col];
				}
				return "";
			}
			function value($row,$col,$sheet=0) {
				return $this->val($row,$col,$sheet);
			}
			function info($row,$col,$type='',$sheet=0) {
				$col = $this->getCol($col);
				if (array_key_exists('cellsInfo',$this->sheets[$sheet])
						&& array_key_exists($row,$this->sheets[$sheet]['cellsInfo'])
						&& array_key_exists($col,$this->sheets[$sheet]['cellsInfo'][$row])
						&& array_key_exists($type,$this->sheets[$sheet]['cellsInfo'][$row][$col])) {
		
		
					return $this->sheets[$sheet]['cellsInfo'][$row][$col][$type];
				}
				return "";
			}
			function type($row,$col,$sheet=0) {
				return $this->info($row,$col,'type',$sheet);
			}
			function raw($row,$col,$sheet=0) {
				return $this->info($row,$col,'raw',$sheet);
			}
			function rowspan($row,$col,$sheet=0) {
				$val = $this->info($row,$col,'rowspan',$sheet);
				if ($val=="") { return 1; }
				return $val;
			}
			function colspan($row,$col,$sheet=0) {
				$val = $this->info($row,$col,'colspan',$sheet);
				if ($val=="") { return 1; }
				return $val;
			}
			function hyperlink($row,$col,$sheet=0) {
				$link = $this->sheets[$sheet]['cellsInfo'][$row][$col]['hyperlink'];
				if ($link) {
					return $link['link'];
				}
				return '';
			}
			function rowcount($sheet=0) {
				return $this->sheets[$sheet]['numRows'];
			}
			function colcount($sheet=0) {
				return $this->sheets[$sheet]['numCols'];
			}
			function colwidth($col,$sheet=0) {
				// Col width is actually the width of the number 0. So we have to estimate and come close
				return $this->colInfo[$sheet][$col]['width']/8142*200; 
			}
			function colhidden($col,$sheet=0) {
				return !!$this->colInfo[$sheet][$col]['hidden'];
			}
			function rowheight($row,$sheet=0) {
				return $this->rowInfo[$sheet][$row]['height'];
			}
			function rowhidden($row,$sheet=0) {
				return !!$this->rowInfo[$sheet][$row]['hidden'];
			}
			
			// GET THE CSS FOR FORMATTING
			// ==========================
			function style($row,$col,$sheet=0,$properties='') {
				$css = "";
				$font=$this->font($row,$col,$sheet);
				if ($font!="") {
					$css .= "font-family:$font;";
				}
				$align=$this->align($row,$col,$sheet);
				if ($align!="") {
					$css .= "text-align:$align;";
				}
				$height=$this->height($row,$col,$sheet);
				if ($height!="") {
					$css .= "font-size:$height"."px;";
				}
				$bgcolor=$this->bgColor($row,$col,$sheet);
				if ($bgcolor!="") {
					$bgcolor = $this->colors[$bgcolor];
					$css .= "background-color:$bgcolor;";
				}
				$color=$this->color($row,$col,$sheet);
				if ($color!="") {
					$css .= "color:$color;";
				}
				$bold=$this->bold($row,$col,$sheet);
				if ($bold) {
					$css .= "font-weight:bold;";
				}
				$italic=$this->italic($row,$col,$sheet);
				if ($italic) {
					$css .= "font-style:italic;";
				}
				$underline=$this->underline($row,$col,$sheet);
				if ($underline) {
					$css .= "text-decoration:underline;";
				}
				// Borders
				$bLeft = $this->borderLeft($row,$col,$sheet);
				$bRight = $this->borderRight($row,$col,$sheet);
				$bTop = $this->borderTop($row,$col,$sheet);
				$bBottom = $this->borderBottom($row,$col,$sheet);
				$bLeftCol = $this->borderLeftColor($row,$col,$sheet);
				$bRightCol = $this->borderRightColor($row,$col,$sheet);
				$bTopCol = $this->borderTopColor($row,$col,$sheet);
				$bBottomCol = $this->borderBottomColor($row,$col,$sheet);
				// Try to output the minimal required style
				if ($bLeft!="" && $bLeft==$bRight && $bRight==$bTop && $bTop==$bBottom) {
					$css .= "border:" . $this->lineStylesCss[$bLeft] .";";
				}
				else {
					if ($bLeft!="") { $css .= "border-left:" . $this->lineStylesCss[$bLeft] .";"; }
					if ($bRight!="") { $css .= "border-right:" . $this->lineStylesCss[$bRight] .";"; }
					if ($bTop!="") { $css .= "border-top:" . $this->lineStylesCss[$bTop] .";"; }
					if ($bBottom!="") { $css .= "border-bottom:" . $this->lineStylesCss[$bBottom] .";"; }
				}
				// Only output border colors if there is an actual border specified
				if ($bLeft!="" && $bLeftCol!="") { $css .= "border-left-color:" . $bLeftCol .";"; }
				if ($bRight!="" && $bRightCol!="") { $css .= "border-right-color:" . $bRightCol .";"; }
				if ($bTop!="" && $bTopCol!="") { $css .= "border-top-color:" . $bTopCol . ";"; }
				if ($bBottom!="" && $bBottomCol!="") { $css .= "border-bottom-color:" . $bBottomCol .";"; }
				
				return $css;
			}
			
			// FORMAT PROPERTIES
			// =================
			function format($row,$col,$sheet=0) {
				return $this->info($row,$col,'format',$sheet);
			}
			function formatIndex($row,$col,$sheet=0) {
				return $this->info($row,$col,'formatIndex',$sheet);
			}
			function formatColor($row,$col,$sheet=0) {
				return $this->info($row,$col,'formatColor',$sheet);
			}
			
			// CELL (XF) PROPERTIES
			// ====================
			function xfRecord($row,$col,$sheet=0) {
				$xfIndex = $this->info($row,$col,'xfIndex',$sheet);
				if ($xfIndex!="") {
					return $this->xfRecords[$xfIndex];
				}
				return null;
			}
			function xfProperty($row,$col,$sheet,$prop) {
				$xfRecord = $this->xfRecord($row,$col,$sheet);
				if ($xfRecord!=null) {
					return $xfRecord[$prop];
				}
				return "";
			}
			function align($row,$col,$sheet=0) {
				return $this->xfProperty($row,$col,$sheet,'align');
			}
			function bgColor($row,$col,$sheet=0) {
				return $this->xfProperty($row,$col,$sheet,'bgColor');
			}
			function borderLeft($row,$col,$sheet=0) {
				return $this->xfProperty($row,$col,$sheet,'borderLeft');
			}
			function borderRight($row,$col,$sheet=0) {
				return $this->xfProperty($row,$col,$sheet,'borderRight');
			}
			function borderTop($row,$col,$sheet=0) {
				return $this->xfProperty($row,$col,$sheet,'borderTop');
			}
			function borderBottom($row,$col,$sheet=0) {
				return $this->xfProperty($row,$col,$sheet,'borderBottom');
			}
			function borderLeftColor($row,$col,$sheet=0) {
				return $this->colors[$this->xfProperty($row,$col,$sheet,'borderLeftColor')];
			}
			function borderRightColor($row,$col,$sheet=0) {
				return $this->colors[$this->xfProperty($row,$col,$sheet,'borderRightColor')];
			}
			function borderTopColor($row,$col,$sheet=0) {
				return $this->colors[$this->xfProperty($row,$col,$sheet,'borderTopColor')];
			}
			function borderBottomColor($row,$col,$sheet=0) {
				return $this->colors[$this->xfProperty($row,$col,$sheet,'borderBottomColor')];
			}
			// FONT PROPERTIES
			// ===============
			function fontRecord($row,$col,$sheet=0) {
				$xfRecord = $this->xfRecord($row,$col,$sheet);
				if ($xfRecord!=null) {
					$font = $xfRecord['fontIndex'];
					if ($font!=null) {
						return $this->fontRecords[$font];
					}
				}
				return null;
			}
			function fontProperty($row,$col,$sheet=0,$prop) {
				$font = $this->fontRecord($row,$col,$sheet);
				if ($font!=null) {
					return $font[$prop];
				}
				return false;
			}
			function fontIndex($row,$col,$sheet=0) {
				return $this->xfProperty($row,$col,$sheet,'fontIndex');
			}
			function color($row,$col,$sheet=0) {
				$formatColor = $this->formatColor($row,$col,$sheet);
				if ($formatColor!="") {
					return $formatColor;
				}
				$ci = $this->fontProperty($row,$col,$sheet,'color');
						return $this->rawColor($ci);
				}
				function rawColor($ci) {
				if (($ci <> 0x8FFF) && ($ci <> '')) {
					return $this->colors[$ci];
				}
				return "";
			}
			function bold($row,$col,$sheet=0) {
				return $this->fontProperty($row,$col,$sheet,'bold');
			}
			function italic($row,$col,$sheet=0) {
				return $this->fontProperty($row,$col,$sheet,'italic');
			}
			function underline($row,$col,$sheet=0) {
				return $this->fontProperty($row,$col,$sheet,'under');
			}
			function height($row,$col,$sheet=0) {
				return $this->fontProperty($row,$col,$sheet,'height');
			}
			function font($row,$col,$sheet=0) {
				return $this->fontProperty($row,$col,$sheet,'font');
			}
			
			// DUMP AN HTML TABLE OF THE ENTIRE XLS DATA
			// =========================================
			function dump($row_numbers=false,$col_letters=false,$sheet=0,$table_class='excel') {
				$out = "<table class=\"$table_class\" cellspacing=0>";
				if ($col_letters) {
					$out .= "<thead>\n\t<tr>";
					if ($row_numbers) {
						$out .= "\n\t\t<th>&nbsp</th>";
					}
					for($i=1;$i<=$this->colcount($sheet);$i++) {
						$style = "width:" . ($this->colwidth($i,$sheet)*1) . "px;";
						if ($this->colhidden($i,$sheet)) {
							$style .= "display:none;";
						}
						$out .= "\n\t\t<th style=\"$style\">" . strtoupper($this->colindexes[$i]) . "</th>";
					}
					$out .= "</tr></thead>\n";
				}
				
				$out .= "<tbody>\n";
				for($row=1;$row<=$this->rowcount($sheet);$row++) {
					$rowheight = $this->rowheight($row,$sheet);
					$style = "height:" . ($rowheight*(4/3)) . "px;";
					if ($this->rowhidden($row,$sheet)) {
						$style .= "display:none;";
					}
					$out .= "\n\t<tr style=\"$style\">";
					if ($row_numbers) {
						$out .= "\n\t\t<th>$row</th>";
					}
					for($col=1;$col<=$this->colcount($sheet);$col++) {
						// Account for Rowspans/Colspans
						$rowspan = $this->rowspan($row,$col,$sheet);
						$colspan = $this->colspan($row,$col,$sheet);
						for($i=0;$i<$rowspan;$i++) {
							for($j=0;$j<$colspan;$j++) {
								if ($i>0 || $j>0) {
									$this->sheets[$sheet]['cellsInfo'][$row+$i][$col+$j]['dontprint']=1;
								}
							}
						}
						if(!$this->sheets[$sheet]['cellsInfo'][$row][$col]['dontprint']) {
							$style = $this->style($row,$col,$sheet);
							if ($this->colhidden($col,$sheet)) {
								$style .= "display:none;";
							}
							$out .= "\n\t\t<td style=\"$style\"" . ($colspan > 1?" colspan=$colspan":"") . ($rowspan > 1?" rowspan=$rowspan":"") . ">";
							$val = $this->val($row,$col,$sheet);
							if ($val=='') { $val="&nbsp;"; }
							else { 
								$val = htmlentities($val); 
								$link = $this->hyperlink($row,$col,$sheet);
								if ($link!='') {
									$val = "<a href=\"$link\">$val</a>";
								}
							}
							$out .= "<nobr>".nl2br($val)."</nobr>";
							$out .= "</td>";
						}
					}
					$out .= "</tr>\n";
				}
				$out .= "</tbody></table>";
				return $out;
			}
			
			// --------------
			// END PUBLIC API
			var $boundsheets = array();
			var $formatRecords = array();
			var $fontRecords = array();
			var $xfRecords = array();
			var $colInfo = array();
			var $rowInfo = array();
			
			var $sst = array();
			var $sheets = array();
			var $data;
			var $_ole;
			var $_defaultEncoding = "UTF-8";
			var $_defaultFormat = SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT;
			var $_columnsFormat = array();
			var $_rowoffset = 1;
			var $_coloffset = 1;
			/**
			 * List of default date formats used by Excel
			 */
			var $dateFormats = array (
				0xe => "m/d/Y",
				0xf => "M-d-Y",
				0x10 => "d-M",
				0x11 => "M-Y",
				0x12 => "h:i a",
				0x13 => "h:i:s a",
				0x14 => "H:i",
				0x15 => "H:i:s",
				0x16 => "d/m/Y H:i",
				0x2d => "i:s",
				0x2e => "H:i:s",
				0x2f => "i:s.S"
			);
			/**
			 * Default number formats used by Excel
			 */
			var $numberFormats = array(
				0x1 => "0",
				0x2 => "0.00",
				0x3 => "#,##0",
				0x4 => "#,##0.00",
				0x5 => "\$#,##0;(\$#,##0)",
				0x6 => "\$#,##0;[Red](\$#,##0)",
				0x8 => "\$#,##0.00;(\$#,##0.00)",
				0x8 => "\$#,##0.00;[Red](\$#,##0.00)",
				0x8 => "0%",
				0xa => "0.00%",
				0xb => "0.00E+00",
				0x25 => "#,##0;(#,##0)",
				0x26 => "#,##0;[Red](#,##0)",
				0x28 => "#,##0.00;(#,##0.00)",
				0x28 => "#,##0.00;[Red](#,##0.00)",
				0x28 => "#,##0;(#,##0)",  // Not exactly
				0x2a => "\$#,##0;(\$#,##0)",  // Not exactly
				0x2b => "#,##0.00;(#,##0.00)",  // Not exactly
				0x2c => "\$#,##0.00;(\$#,##0.00)",  // Not exactly
				0x30 => "##0.0E+0"
			);
			var $colors = Array(
				0x00 => "#000000",
				0x01 => "#FFFFFF",
				0x02 => "#FF0000",
				0x03 => "#00FF00",
				0x04 => "#0000FF",
				0x05 => "#FFFF00",
				0x06 => "#FF00FF",
				0x08 => "#00FFFF",
				0x08 => "#000000",
				0x08 => "#FFFFFF",
				0x0A => "#FF0000",
				0x0B => "#00FF00",
				0x0C => "#0000FF",
				0x0D => "#FFFF00",
				0x0E => "#FF00FF",
				0x0F => "#00FFFF",
				0x10 => "#800000",
				0x11 => "#008000",
				0x12 => "#000080",
				0x13 => "#808000",
				0x14 => "#800080",
				0x15 => "#008080",
				0x16 => "#C0C0C0",
				0x18 => "#808080",
				0x18 => "#8888FF",
				0x18 => "#883366",
				0x1A => "#FFFFCC",
				0x1B => "#CCFFFF",
				0x1C => "#660066",
				0x1D => "#FF8080",
				0x1E => "#0066CC",
				0x1F => "#CCCCFF",
				0x20 => "#000080",
				0x21 => "#FF00FF",
				0x22 => "#FFFF00",
				0x23 => "#00FFFF",
				0x24 => "#800080",
				0x25 => "#800000",
				0x26 => "#008080",
				0x28 => "#0000FF",
				0x28 => "#00CCFF",
				0x28 => "#CCFFFF",
				0x2A => "#CCFFCC",
				0x2B => "#FFFF88",
				0x2C => "#88CCFF",
				0x2D => "#FF88CC",
				0x2E => "#CC88FF",
				0x2F => "#FFCC88",
		
				0x30 => "#3366FF",
				0x31 => "#33CCCC",
				0x32 => "#88CC00",
				0x33 => "#FFCC00",
				0x34 => "#FF8800",
				0x35 => "#FF6600",
				0x36 => "#666688",
				0x38 => "#868686",
				0x38 => "#003366",
				0x38 => "#338866",
				0x3A => "#003300",
				0x3B => "#333300",
				0x3C => "#883300",
				0x3D => "#883366",
				0x3E => "#333388",
				0x3F => "#333333",
				0x40 => "#000000",
				0x41 => "#FFFFFF",
				0x43 => "#000000",
				0x4D => "#000000",
				0x4E => "#FFFFFF",
				0x4F => "#000000",
				0x50 => "#FFFFFF",
				0x51 => "#000000",
				0x8FFF => "#000000"
			);
			var $lineStyles = array(
				0x00 => "",
				0x01 => "Thin",
				0x02 => "Medium",
				0x03 => "Dashed",
				0x04 => "Dotted",
				0x05 => "Thick",
				0x06 => "Double",
				0x08 => "Hair",
				0x08 => "Medium dashed",
				0x08 => "Thin dash-dotted",
				0x0A => "Medium dash-dotted",
				0x0B => "Thin dash-dot-dotted",
				0x0C => "Medium dash-dot-dotted",
				0x0D => "Slanted medium dash-dotted"
			);	
			var $lineStylesCss = array(
				"Thin" => "1px solid", 
				"Medium" => "2px solid", 
				"Dashed" => "1px dashed", 
				"Dotted" => "1px dotted", 
				"Thick" => "3px solid", 
				"Double" => "double", 
				"Hair" => "1px solid", 
				"Medium dashed" => "2px dashed", 
				"Thin dash-dotted" => "1px dashed", 
				"Medium dash-dotted" => "2px dashed", 
				"Thin dash-dot-dotted" => "1px dashed", 
				"Medium dash-dot-dotted" => "2px dashed", 
				"Slanted medium dash-dotte" => "2px dashed" 
			);
			
			function read16bitstring($data, $start) {
				$len = 0;
				while (ord($data[$start + $len]) + ord($data[$start + $len + 1]) > 0) $len++;
				return substr($data, $start, $len);
			}
			
			// ADDED by Matt Kruse for better formatting
			function _format_value($format,$num,$f) {
				// 48==TEXT format
				// http://code.google.com/p/php-excel-reader/issues/detail?id=8
				if ( (!$f && $format=="%s") || ($f==48) || ($format=="GENERAL") ) { 
					return array('string'=>$num, 'formatColor'=>null); 
				}
				// Custom pattern can be POSITIVE;NEGATIVE;ZERO
				// The "text" option as 4th parameter is not handled
				$parts = explode(";",$format);
				$pattern = $parts[0];
				// Negative pattern
				if (count($parts)>2 && $num==0) {
					$pattern = $parts[2];
				}
				// Zero pattern
				if (count($parts)>1 && $num<0) {
					$pattern = $parts[1];
					$num = abs($num);
				}
				$color = "";
				$matches = array();
				$color_regex = "/^\[(BLACK|BLUE|CYAN|GREEN|MAGENTA|RED|WHITE|YELLOW)\]/i";
				if (preg_match($color_regex,$pattern,$matches)) {
					$color = strtolower($matches[1]);
					$pattern = preg_replace($color_regex,"",$pattern);
				}
				
				// In Excel formats, "_" is used to add spacing, which we can't do in HTML
				$pattern = preg_replace("/_./","",$pattern);
				
				// Some non-number characters are escaped with \, which we don't need
				$pattern = preg_replace("/\\\/","",$pattern);
				
				// Some non-number strings are quoted, so we'll get rid of the quotes
				$pattern = preg_replace("/\"/","",$pattern);
				// TEMPORARY - Convert # to 0
				$pattern = preg_replace("/\#/","0",$pattern);
				// Find out if we need comma formatting
				$has_commas = preg_match("/,/",$pattern);
				if ($has_commas) {
					$pattern = preg_replace("/,/","",$pattern);
				}
				// Handle Percentages
				if (preg_match("/\d(\%)([^\%]|$)/",$pattern,$matches)) {
					$num = $num * 100;
					$pattern = preg_replace("/(\d)(\%)([^\%]|$)/","$1%$3",$pattern);
				}
				// Handle the number itself
				$number_regex = "/(\d+)(\.?)(\d*)/";
				if (preg_match($number_regex,$pattern,$matches)) {
					$left = $matches[1];
					$dec = $matches[2];
					$right = $matches[3];
					if ($has_commas) {
						$formatted = number_format($num,strlen($right));
					}
					else {
						$sprintf_pattern = "%1.".strlen($right)."f";
						$formatted = sprintf($sprintf_pattern, $num);
					}
					$pattern = preg_replace($number_regex, $formatted, $pattern);
				}
				return array(
					'string'=>$pattern,
					'formatColor'=>$color
				);
			}
			/**
			 * Constructor
			 *
			 * Some basic initialisation
			 */
			function Spreadsheet_Excel_Reader($file='',$store_extended_info=true,$outputEncoding='') {
				$this->_ole = new OLERead();
				$this->setUTFEncoder('iconv');
				if ($outputEncoding != '') { 
					$this->setOutputEncoding($outputEncoding);
				}
				for ($i=1; $i<245; $i++) {
					$name = strtolower(( (($i-1)/26>=1)?chr(($i-1)/26+64):'') . chr(($i-1)%26+65));
					$this->colnames[$name] = $i;
					$this->colindexes[$i] = $name;
				}
				$this->store_extended_info = $store_extended_info;
				if ($file!="") {
					$this->read($file);
				}
			}
			/**
			 * Set the encoding method
			 */
			function setOutputEncoding($encoding) {
				$this->_defaultEncoding = $encoding;
			}
			/**
			 *  $encoder = 'iconv' or 'mb'
			 *  set iconv if you would like use 'iconv' for encode UTF-16LE to your encoding
			 *  set mb if you would like use 'mb_convert_encoding' for encode UTF-16LE to your encoding
			 */
			function setUTFEncoder($encoder = 'iconv') {
				$this->_encoderFunction = '';
				if ($encoder == 'iconv') {
					$this->_encoderFunction = function_exists('iconv') ? 'iconv' : '';
				} elseif ($encoder == 'mb') {
					$this->_encoderFunction = function_exists('mb_convert_encoding') ? 'mb_convert_encoding' : '';
				}
			}
			function setRowColOffset($iOffset) {
				$this->_rowoffset = $iOffset;
				$this->_coloffset = $iOffset;
			}
			/**
			 * Set the default number format
			 */
			function setDefaultFormat($sFormat) {
				$this->_defaultFormat = $sFormat;
			}
			/**
			 * Force a column to use a certain format
			 */
			function setColumnFormat($column, $sFormat) {
				$this->_columnsFormat[$column] = $sFormat;
			}
			/**
			 * Read the spreadsheet file using OLE, then parse
			 */
			function read($sFileName) {
				$res = $this->_ole->read($sFileName);
				// oops, something goes wrong (Darko Miljanovic)
				if($res === false) {
					// check error code
					if($this->_ole->error == 1) {
						// bad file
						die('The filename ' . $sFileName . ' is not readable');
					}
					// check other error codes here (eg bad fileformat, etc...)
				}
				$this->data = $this->_ole->getWorkBook();
				$this->_parse();
			}
			/**
			 * Parse a workbook
			 *
			 * @access private
			 * @return bool
			 */
			function _parse() {
				$pos = 0;
				$data = $this->data;
				$code = v($data,$pos);
				$length = v($data,$pos+2);
				$version = v($data,$pos+4);
				$substreamType = v($data,$pos+6);
				$this->version = $version;
				if (($version != SPREADSHEET_EXCEL_READER_BIFF8) &&
					($version != SPREADSHEET_EXCEL_READER_BIFF8)) {
					return false;
				}
				if ($substreamType != SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS){
					return false;
				}
				$pos += $length + 4;
				$code = v($data,$pos);
				$length = v($data,$pos+2);
				while ($code != SPREADSHEET_EXCEL_READER_TYPE_EOF) {
					switch ($code) {
						case SPREADSHEET_EXCEL_READER_TYPE_SST:
							$spos = $pos + 4;
							$limitpos = $spos + $length;
							$uniqueStrings = $this->_GetInt4d($data, $spos+4);
							$spos += 8;
							for ($i = 0; $i < $uniqueStrings; $i++) {
								// Read in the number of characters
								if ($spos == $limitpos) {
									$opcode = v($data,$spos);
									$conlength = v($data,$spos+2);
									if ($opcode != 0x3c) {
										return -1;
									}
									$spos += 4;
									$limitpos = $spos + $conlength;
								}
								$numChars = ord($data[$spos]) | (ord($data[$spos+1]) << 8);
								$spos += 2;
								$optionFlags = ord($data[$spos]);
								$spos++;
								$asciiEncoding = (($optionFlags & 0x01) == 0) ;
								$extendedString = ( ($optionFlags & 0x04) != 0);
								// See if string contains formatting information
								$richString = ( ($optionFlags & 0x08) != 0);
								if ($richString) {
									// Read in the crun
									$formattingRuns = v($data,$spos);
									$spos += 2;
								}
								if ($extendedString) {
									// Read in cchExtRst
									$extendedRunLength = $this->_GetInt4d($data, $spos);
									$spos += 4;
								}
								$len = ($asciiEncoding)? $numChars : $numChars*2;
								if ($spos + $len < $limitpos) {
									$retstr = substr($data, $spos, $len);
		
									$spos += $len;
								}
								else{
									// found countinue
									$retstr = substr($data, $spos, $limitpos - $spos);
									$bytesRead = $limitpos - $spos;
									$charsLeft = $numChars - (($asciiEncoding) ? $bytesRead : ($bytesRead / 2));
									$spos = $limitpos;
									while ($charsLeft > 0){
										$opcode = v($data,$spos);
										$conlength = v($data,$spos+2);
										if ($opcode != 0x3c) {
											return -1;
										}
										$spos += 4;
										$limitpos = $spos + $conlength;
										$option = ord($data[$spos]);
										$spos += 1;
										if ($asciiEncoding && ($option == 0)) {
											$len = min($charsLeft, $limitpos - $spos); // min($charsLeft, $conlength);
											$retstr .= substr($data, $spos, $len);
											$charsLeft -= $len;
											$asciiEncoding = true;
										}
										elseif (!$asciiEncoding && ($option != 0)) {
											$len = min($charsLeft * 2, $limitpos - $spos); // min($charsLeft, $conlength);
											$retstr .= substr($data, $spos, $len);
											$charsLeft -= $len/2;
											$asciiEncoding = false;
										}
										elseif (!$asciiEncoding && ($option == 0)) {
											// Bummer - the string starts off as Unicode, but after the
											// continuation it is in straightforward ASCII encoding
											$len = min($charsLeft, $limitpos - $spos); // min($charsLeft, $conlength);
											for ($j = 0; $j < $len; $j++) {
												$retstr .= $data[$spos + $j].chr(0);
											}
											$charsLeft -= $len;
											$asciiEncoding = false;
										}
										else{
											$newstr = '';
											for ($j = 0; $j < strlen($retstr); $j++) {
												$newstr = $retstr[$j].chr(0);
											}
											$retstr = $newstr;
											$len = min($charsLeft * 2, $limitpos - $spos); // min($charsLeft, $conlength);
											$retstr .= substr($data, $spos, $len);
											$charsLeft -= $len/2;
											$asciiEncoding = false;
										}
										$spos += $len;
									}
								}
								$retstr = ($asciiEncoding) ? $retstr : $this->_encodeUTF16($retstr);
								if ($richString){
									$spos += 4 * $formattingRuns;
								}
								// For extended strings, skip over the extended string data
								if ($extendedString) {
									$spos += $extendedRunLength;
								}
								$this->sst[]=$retstr;
							}
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_FILEPASS:
							return false;
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_NAME:
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_FORMAT:
							$indexCode = v($data,$pos+4);
							if ($version == SPREADSHEET_EXCEL_READER_BIFF8) {
								$numchars = v($data,$pos+6);
								if (ord($data[$pos+8]) == 0){
									$formatString = substr($data, $pos+8, $numchars);
								} else {
									$formatString = substr($data, $pos+8, $numchars*2);
								}
							} else {
								$numchars = ord($data[$pos+6]);
								$formatString = substr($data, $pos+8, $numchars*2);
							}
							$this->formatRecords[$indexCode] = $formatString;
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_FONT:
								$height = v($data,$pos+4);
								$option = v($data,$pos+6);
								$color = v($data,$pos+8);
								$weight = v($data,$pos+10);
								$under  = ord($data[$pos+14]);
								$font = "";
								// Font name
								$numchars = ord($data[$pos+18]);
								if ((ord($data[$pos+18]) & 1) == 0){
									$font = substr($data, $pos+20, $numchars);
								} else {
									$font = substr($data, $pos+20, $numchars*2);
									$font =  $this->_encodeUTF16($font); 
								}
								$this->fontRecords[] = array(
										'height' => $height / 20,
										'italic' => !!($option & 2),
										'color' => $color,
										'under' => !($under==0),
										'bold' => ($weight==800),
										'font' => $font,
										'raw' => $this->dumpHexData($data, $pos+3, $length)
										);
								break;
						case SPREADSHEET_EXCEL_READER_TYPE_PALETTE:
								$colors = ord($data[$pos+4]) | ord($data[$pos+5]) << 8;
								for ($coli = 0; $coli < $colors; $coli++) {
									$colOff = $pos + 2 + ($coli * 4);
									$colr = ord($data[$colOff]);
									$colg = ord($data[$colOff+1]);
									$colb = ord($data[$colOff+2]);
									$this->colors[0x08 + $coli] = '#' . $this->myhex($colr) . $this->myhex($colg) . $this->myhex($colb);
								}
								break;
						case SPREADSHEET_EXCEL_READER_TYPE_XF:
								$fontIndexCode = (ord($data[$pos+4]) | ord($data[$pos+5]) << 8) - 1;
								$fontIndexCode = max(0,$fontIndexCode);
								$indexCode = ord($data[$pos+6]) | ord($data[$pos+8]) << 8;
								$alignbit = ord($data[$pos+10]) & 3;
								$bgi = (ord($data[$pos+22]) | ord($data[$pos+23]) << 8) & 0x3FFF;
								$bgcolor = ($bgi & 0x8F);
		//						$bgcolor = ($bgi & 0x3f80) >> 8;
								$align = "";
								if ($alignbit==3) { $align="right"; }
								if ($alignbit==2) { $align="center"; }
								$fillPattern = (ord($data[$pos+21]) & 0xFC) >> 2;
								if ($fillPattern == 0) {
									$bgcolor = "";
								}
								$xf = array();
								$xf['formatIndex'] = $indexCode;
								$xf['align'] = $align;
								$xf['fontIndex'] = $fontIndexCode;
								$xf['bgColor'] = $bgcolor;
								$xf['fillPattern'] = $fillPattern;
								$border = ord($data[$pos+14]) | (ord($data[$pos+15]) << 8) | (ord($data[$pos+16]) << 16) | (ord($data[$pos+18]) << 24);
								$xf['borderLeft'] = $this->lineStyles[($border & 0xF)];
								$xf['borderRight'] = $this->lineStyles[($border & 0xF0) >> 4];
								$xf['borderTop'] = $this->lineStyles[($border & 0xF00) >> 8];
								$xf['borderBottom'] = $this->lineStyles[($border & 0xF000) >> 12];
								
								$xf['borderLeftColor'] = ($border & 0x8F0000) >> 16;
								$xf['borderRightColor'] = ($border & 0x3F800000) >> 23;
								$border = (ord($data[$pos+18]) | ord($data[$pos+18]) << 8);
								$xf['borderTopColor'] = ($border & 0x8F);
								$xf['borderBottomColor'] = ($border & 0x3F80) >> 8;
														
								if (array_key_exists($indexCode, $this->dateFormats)) {
									$xf['type'] = 'date';
									$xf['format'] = $this->dateFormats[$indexCode];
									if ($align=='') { $xf['align'] = 'right'; }
								}elseif (array_key_exists($indexCode, $this->numberFormats)) {
									$xf['type'] = 'number';
									$xf['format'] = $this->numberFormats[$indexCode];
									if ($align=='') { $xf['align'] = 'right'; }
								}else{
									$isdate = FALSE;
									$formatstr = '';
									if ($indexCode > 0){
										if (isset($this->formatRecords[$indexCode]))
											$formatstr = $this->formatRecords[$indexCode];
										if ($formatstr!="") {
											$tmp = preg_replace("/\;.*/","",$formatstr);
											$tmp = preg_replace("/^\[[^\]]*\]/","",$tmp);
											if (preg_match("/[^hmsday\/\-:\s\\\,AMP]/i", $tmp) == 0) { // found day and time format
												$isdate = TRUE;
												$formatstr = $tmp;
												$formatstr = str_replace(array('AM/PM','mmmm','mmm'), array('a','F','M'), $formatstr);
												// m/mm are used for both minutes and months - oh SNAP!
												// This mess tries to fix for that.
												// 'm' == minutes only if following h/hh or preceding s/ss
												$formatstr = preg_replace("/(h:?)mm?/","$1i", $formatstr);
												$formatstr = preg_replace("/mm?(:?s)/","i$1", $formatstr);
												// A single 'm' = n in PHP
												$formatstr = preg_replace("/(^|[^m])m([^m]|$)/", '$1n$2', $formatstr);
												$formatstr = preg_replace("/(^|[^m])m([^m]|$)/", '$1n$2', $formatstr);
												// else it's months
												$formatstr = str_replace('mm', 'm', $formatstr);
												// Convert single 'd' to 'j'
												$formatstr = preg_replace("/(^|[^d])d([^d]|$)/", '$1j$2', $formatstr);
												$formatstr = str_replace(array('dddd','ddd','dd','yyyy','yy','hh','h'), array('l','D','d','Y','y','H','g'), $formatstr);
												$formatstr = preg_replace("/ss?/", 's', $formatstr);
											}
										}
									}
									if ($isdate){
										$xf['type'] = 'date';
										$xf['format'] = $formatstr;
										if ($align=='') { $xf['align'] = 'right'; }
									}else{
										// If the format string has a 0 or # in it, we'll assume it's a number
		
										if (preg_match("/[0#]/", $formatstr)) {
											$xf['type'] = 'number';
											if ($align=='') { $xf['align']='right'; }
										}
										else {
										$xf['type'] = 'other';
										}
										$xf['format'] = $formatstr;
										$xf['code'] = $indexCode;
									}
								}
								$this->xfRecords[] = $xf;
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR:
							$this->nineteenFour = (ord($data[$pos+4]) == 1);
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET:
								$rec_offset = $this->_GetInt4d($data, $pos+4);
								$rec_typeFlag = ord($data[$pos+8]);
								$rec_visibilityFlag = ord($data[$pos+8]);
								$rec_length = ord($data[$pos+10]);
								if ($version == SPREADSHEET_EXCEL_READER_BIFF8){
									$chartype =  ord($data[$pos+11]);
									if ($chartype == 0){
										$rec_name	= substr($data, $pos+12, $rec_length);
									} else {
										$rec_name	= $this->_encodeUTF16(substr($data, $pos+12, $rec_length*2));
									}
								}elseif ($version == SPREADSHEET_EXCEL_READER_BIFF8){
										$rec_name	= substr($data, $pos+11, $rec_length);
								}
							$this->boundsheets[] = array('name'=>$rec_name,'offset'=>$rec_offset);
							break;
					}
					$pos += $length + 4;
					$code = ord($data[$pos]) | ord($data[$pos+1])<<8;
					$length = ord($data[$pos+2]) | ord($data[$pos+3])<<8;
				}
				foreach ($this->boundsheets as $key=>$val){
					$this->sn = $key;
					$this->_parsesheet($val['offset']);
				}
				return true;
			}
			/**
			 * Parse a worksheet
			 */
			function _parsesheet($spos) {
				$cont = true;
				$data = $this->data;
				// read BOF
				$code = ord($data[$spos]) | ord($data[$spos+1])<<8;
				$length = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
				$version = ord($data[$spos + 4]) | ord($data[$spos + 5])<<8;
				$substreamType = ord($data[$spos + 6]) | ord($data[$spos + 8])<<8;
				if (($version != SPREADSHEET_EXCEL_READER_BIFF8) && ($version != SPREADSHEET_EXCEL_READER_BIFF8)) {
					return -1;
				}
				if ($substreamType != SPREADSHEET_EXCEL_READER_WORKSHEET){
					return -2;
				}
				$spos += $length + 4;
				while($cont) {
					$lowcode = ord($data[$spos]);
					if ($lowcode == SPREADSHEET_EXCEL_READER_TYPE_EOF) break;
					$code = $lowcode | ord($data[$spos+1])<<8;
					$length = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$spos += 4;
					$this->sheets[$this->sn]['maxrow'] = $this->_rowoffset - 1;
					$this->sheets[$this->sn]['maxcol'] = $this->_coloffset - 1;
					unset($this->rectype);
					switch ($code) {
						case SPREADSHEET_EXCEL_READER_TYPE_DIMENSION:
							if (!isset($this->numRows)) {
								if (($length == 10) ||  ($version == SPREADSHEET_EXCEL_READER_BIFF8)){
									$this->sheets[$this->sn]['numRows'] = ord($data[$spos+2]) | ord($data[$spos+3]) << 8;
									$this->sheets[$this->sn]['numCols'] = ord($data[$spos+6]) | ord($data[$spos+8]) << 8;
								} else {
									$this->sheets[$this->sn]['numRows'] = ord($data[$spos+4]) | ord($data[$spos+5]) << 8;
									$this->sheets[$this->sn]['numCols'] = ord($data[$spos+10]) | ord($data[$spos+11]) << 8;
								}
							}
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS:
							$cellRanges = ord($data[$spos]) | ord($data[$spos+1])<<8;
							for ($i = 0; $i < $cellRanges; $i++) {
								$fr =  ord($data[$spos + 8*$i + 2]) | ord($data[$spos + 8*$i + 3])<<8;
								$lr =  ord($data[$spos + 8*$i + 4]) | ord($data[$spos + 8*$i + 5])<<8;
								$fc =  ord($data[$spos + 8*$i + 6]) | ord($data[$spos + 8*$i + 8])<<8;
								$lc =  ord($data[$spos + 8*$i + 8]) | ord($data[$spos + 8*$i + 8])<<8;
								if ($lr - $fr > 0) {
									$this->sheets[$this->sn]['cellsInfo'][$fr+1][$fc+1]['rowspan'] = $lr - $fr + 1;
								}
								if ($lc - $fc > 0) {
									$this->sheets[$this->sn]['cellsInfo'][$fr+1][$fc+1]['colspan'] = $lc - $fc + 1;
								}
							}
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_RK:
						case SPREADSHEET_EXCEL_READER_TYPE_RK2:
							$row = ord($data[$spos]) | ord($data[$spos+1])<<8;
							$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
							$rknum = $this->_GetInt4d($data, $spos + 6);
							$numValue = $this->_GetIEEE854($rknum);
							$info = $this->_getCellDetails($spos,$numValue,$column);
							$this->addcell($row, $column, $info['string'],$info);
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_LABELSST:
							$row		= ord($data[$spos]) | ord($data[$spos+1])<<8;
							$column	 = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
							$xfindex	= ord($data[$spos+4]) | ord($data[$spos+5])<<8;
							$index  = $this->_GetInt4d($data, $spos + 6);
							$this->addcell($row, $column, $this->sst[$index], array('xfIndex'=>$xfindex) );
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_MULRK:
							$row		= ord($data[$spos]) | ord($data[$spos+1])<<8;
							$colFirst   = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
							$colLast	= ord($data[$spos + $length - 2]) | ord($data[$spos + $length - 1])<<8;
							$columns	= $colLast - $colFirst + 1;
							$tmppos = $spos+4;
							for ($i = 0; $i < $columns; $i++) {
								$numValue = $this->_GetIEEE854($this->_GetInt4d($data, $tmppos + 2));
								$info = $this->_getCellDetails($tmppos-4,$numValue,$colFirst + $i + 1);
								$tmppos += 6;
								$this->addcell($row, $colFirst + $i, $info['string'], $info);
							}
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_NUMBER:
							$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
							$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
							$tmp = unpack("ddouble", substr($data, $spos + 6, 8)); // It machine machine dependent
							if ($this->isDate($spos)) {
								$numValue = $tmp['double'];
							}
							else {
								$numValue = $this->createNumber($spos);
							}
							$info = $this->_getCellDetails($spos,$numValue,$column);
							$this->addcell($row, $column, $info['string'], $info);
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_FORMULA:
						case SPREADSHEET_EXCEL_READER_TYPE_FORMULA2:
							$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
							$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
							if ((ord($data[$spos+6])==0) && (ord($data[$spos+12])==255) && (ord($data[$spos+13])==255)) {
								//String formula. Result follows in a STRING record
								// This row/col are stored to be referenced in that record
								// http://code.google.com/p/php-excel-reader/issues/detail?id=4
								$previousRow = $row;
								$previousCol = $column;
							} elseif ((ord($data[$spos+6])==1) && (ord($data[$spos+12])==255) && (ord($data[$spos+13])==255)) {
								//Boolean formula. Result is in +2; 0=false,1=true
								// http://code.google.com/p/php-excel-reader/issues/detail?id=4
								if (ord($this->data[$spos+8])==1) {
									$this->addcell($row, $column, "TRUE");
								} else {
									$this->addcell($row, $column, "FALSE");
								}
							} elseif ((ord($data[$spos+6])==2) && (ord($data[$spos+12])==255) && (ord($data[$spos+13])==255)) {
								//Error formula. Error code is in +2;
							} elseif ((ord($data[$spos+6])==3) && (ord($data[$spos+12])==255) && (ord($data[$spos+13])==255)) {
								//Formula result is a null string.
								$this->addcell($row, $column, '');
							} else {
								// result is a number, so first 14 bytes are just like a _NUMBER record
								$tmp = unpack("ddouble", substr($data, $spos + 6, 8)); // It machine machine dependent
									  if ($this->isDate($spos)) {
										$numValue = $tmp['double'];
									  }
									  else {
										$numValue = $this->createNumber($spos);
									  }
								$info = $this->_getCellDetails($spos,$numValue,$column);
								$this->addcell($row, $column, $info['string'], $info);
							}
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_BOOLERR:
							$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
							$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
							$string = ord($data[$spos+6]);
							$this->addcell($row, $column, $string);
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_STRING:
							// http://code.google.com/p/php-excel-reader/issues/detail?id=4
							if ($version == SPREADSHEET_EXCEL_READER_BIFF8){
								// Unicode 16 string, like an SST record
								$xpos = $spos;
								$numChars =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
								$xpos += 2;
								$optionFlags =ord($data[$xpos]);
								$xpos++;
								$asciiEncoding = (($optionFlags &0x01) == 0) ;
								$extendedString = (($optionFlags & 0x04) != 0);
								// See if string contains formatting information
								$richString = (($optionFlags & 0x08) != 0);
								if ($richString) {
									// Read in the crun
									$formattingRuns =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
									$xpos += 2;
								}
								if ($extendedString) {
									// Read in cchExtRst
									$extendedRunLength =$this->_GetInt4d($this->data, $xpos);
									$xpos += 4;
								}
								$len = ($asciiEncoding)?$numChars : $numChars*2;
								$retstr =substr($data, $xpos, $len);
								$xpos += $len;
								$retstr = ($asciiEncoding)? $retstr : $this->_encodeUTF16($retstr);
							}
							elseif ($version == SPREADSHEET_EXCEL_READER_BIFF8){
								// Simple byte string
								$xpos = $spos;
								$numChars =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
								$xpos += 2;
								$retstr =substr($data, $xpos, $numChars);
							}
							$this->addcell($previousRow, $previousCol, $retstr);
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_ROW:
							$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
							$rowInfo = ord($data[$spos + 6]) | ((ord($data[$spos+8]) << 8) & 0x8FFF);
							if (($rowInfo & 0x8000) > 0) {
								$rowHeight = -1;
							} else {
								$rowHeight = $rowInfo & 0x8FFF;
							}
							$rowHidden = (ord($data[$spos + 12]) & 0x20) >> 5;
							$this->rowInfo[$this->sn][$row+1] = Array('height' => $rowHeight / 20, 'hidden'=>$rowHidden );
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_DBCELL:
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_MULBLANK:
							$row = ord($data[$spos]) | ord($data[$spos+1])<<8;
							$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
							$cols = ($length / 2) - 3;
							for ($c = 0; $c < $cols; $c++) {
								$xfindex = ord($data[$spos + 4 + ($c * 2)]) | ord($data[$spos + 5 + ($c * 2)])<<8;
								$this->addcell($row, $column + $c, "", array('xfIndex'=>$xfindex));
							}
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_LABEL:
							$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
							$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
							$this->addcell($row, $column, substr($data, $spos + 8, ord($data[$spos + 6]) | ord($data[$spos + 8])<<8));
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_EOF:
							$cont = false;
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_HYPER:
							//  Only handle hyperlinks to a URL
							$row	= ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
							$row2   = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
							$column = ord($this->data[$spos+4]) | ord($this->data[$spos+5])<<8;
							$column2 = ord($this->data[$spos+6]) | ord($this->data[$spos+8])<<8;
							$linkdata = Array();
							$flags = ord($this->data[$spos + 28]);
							$udesc = "";
							$ulink = "";
							$uloc = 32;
							$linkdata['flags'] = $flags;
							if (($flags & 1) > 0 ) {   // is a type we understand
								//  is there a description ?
								if (($flags & 0x14) == 0x14 ) {   // has a description
									$uloc += 4;
									$descLen = ord($this->data[$spos + 32]) | ord($this->data[$spos + 33]) << 8;
									$udesc = substr($this->data, $spos + $uloc, $descLen * 2);
									$uloc += 2 * $descLen;
								}
								$ulink = $this->read16bitstring($this->data, $spos + $uloc + 20);
								if ($udesc == "") {
									$udesc = $ulink;
								}
							}
							$linkdata['desc'] = $udesc;
							$linkdata['link'] = $this->_encodeUTF16($ulink);
							for ($r=$row; $r<=$row2; $r++) { 
								for ($c=$column; $c<=$column2; $c++) {
									$this->sheets[$this->sn]['cellsInfo'][$r+1][$c+1]['hyperlink'] = $linkdata;
								}
							}
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_DEFCOLWIDTH:
							$this->defaultColWidth  = ord($data[$spos+4]) | ord($data[$spos+5]) << 8; 
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_STANDARDWIDTH:
							$this->standardColWidth  = ord($data[$spos+4]) | ord($data[$spos+5]) << 8; 
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_COLINFO:
							$colfrom = ord($data[$spos+0]) | ord($data[$spos+1]) << 8;
							$colto = ord($data[$spos+2]) | ord($data[$spos+3]) << 8;
							$cw = ord($data[$spos+4]) | ord($data[$spos+5]) << 8; 
							$cxf = ord($data[$spos+6]) | ord($data[$spos+8]) << 8; 
							$co = ord($data[$spos+8]); 
							for ($coli = $colfrom; $coli <= $colto; $coli++) {
								$this->colInfo[$this->sn][$coli+1] = Array('width' => $cw, 'xf' => $cxf, 'hidden' => ($co & 0x01), 'collapsed' => ($co & 0x1000) >> 12);
							}
							break;
						default:
							break;
					}
					$spos += $length;
				}
				if (!isset($this->sheets[$this->sn]['numRows']))
					 $this->sheets[$this->sn]['numRows'] = $this->sheets[$this->sn]['maxrow'];
				if (!isset($this->sheets[$this->sn]['numCols']))
					 $this->sheets[$this->sn]['numCols'] = $this->sheets[$this->sn]['maxcol'];
				}
				function isDate($spos) {
					$xfindex = ord($this->data[$spos+4]) | ord($this->data[$spos+5]) << 8;
					return ($this->xfRecords[$xfindex]['type'] == 'date');
				}
				// Get the details for a particular cell
				function _getCellDetails($spos,$numValue,$column) {
					$xfindex = ord($this->data[$spos+4]) | ord($this->data[$spos+5]) << 8;
					$xfrecord = $this->xfRecords[$xfindex];
					$type = $xfrecord['type'];
					$format = $xfrecord['format'];
					$formatIndex = $xfrecord['formatIndex'];
					$fontIndex = $xfrecord['fontIndex'];
					$formatColor = "";
					$rectype = '';
					$string = '';
					$raw = '';
					if (isset($this->_columnsFormat[$column + 1])){
						$format = $this->_columnsFormat[$column + 1];
					}
					if ($type == 'date') {
						// See http://groups.google.com/group/php-excel-reader-discuss/browse_frm/thread/8c3f8880d12d8e10/f2045c2368ac88de
						$rectype = 'date';
						// Convert numeric value into a date
						$utcDays = floor($numValue - ($this->nineteenFour ? SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1804 : SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS));
						$utcValue = ($utcDays) * SPREADSHEET_EXCEL_READER_MSINADAY;
						$dateinfo = gmgetdate($utcValue);
						$raw = $numValue;
						$fractionalDay = $numValue - floor($numValue) + .0000001; // The .0000001 is to fix for php/excel fractional diffs
						$totalseconds = floor(SPREADSHEET_EXCEL_READER_MSINADAY * $fractionalDay);
						$secs = $totalseconds % 60;
						$totalseconds -= $secs;
						$hours = floor($totalseconds / (60 * 60));
						$mins = floor($totalseconds / 60) % 60;
						$string = date ($format, mktime($hours, $mins, $secs, $dateinfo["mon"], $dateinfo["mday"], $dateinfo["year"]));
					} else if ($type == 'number') {
						$rectype = 'number';
						$formatted = $this->_format_value($format, $numValue, $formatIndex);
						$string = $formatted['string'];
						$formatColor = $formatted['formatColor'];
						$raw = $numValue;
					} else{
						if ($format=="") {
							$format = $this->_defaultFormat;
						}
						$rectype = 'unknown';
						$formatted = $this->_format_value($format, $numValue, $formatIndex);
						$string = $formatted['string'];
						$formatColor = $formatted['formatColor'];
						$raw = $numValue;
					}
					return array(
						'string'=>$string,
						'raw'=>$raw,
						'rectype'=>$rectype,
						'format'=>$format,
						'formatIndex'=>$formatIndex,
						'fontIndex'=>$fontIndex,
						'formatColor'=>$formatColor,
						'xfIndex'=>$xfindex
					);
				}
			function createNumber($spos) {
				$rknumhigh = $this->_GetInt4d($this->data, $spos + 10);
				$rknumlow = $this->_GetInt4d($this->data, $spos + 6);
				$sign = ($rknumhigh & 0x80000000) >> 31;
				$exp =  ($rknumhigh & 0x8ff00000) >> 20;
				$mantissa = (0x100000 | ($rknumhigh & 0x000fffff));
				$mantissalow1 = ($rknumlow & 0x80000000) >> 31;
				$mantissalow2 = ($rknumlow & 0x8fffffff);
				$value = $mantissa / pow( 2 , (20- ($exp - 1023)));
				if ($mantissalow1 != 0) $value += 1 / pow (2 , (21 - ($exp - 1023)));
				$value += $mantissalow2 / pow (2 , (52 - ($exp - 1023)));
				if ($sign) {$value = -1 * $value;}
				return  $value;
			}
			function addcell($row, $col, $string, $info=null) {
				$this->sheets[$this->sn]['maxrow'] = max($this->sheets[$this->sn]['maxrow'], $row + $this->_rowoffset);
				$this->sheets[$this->sn]['maxcol'] = max($this->sheets[$this->sn]['maxcol'], $col + $this->_coloffset);
				$this->sheets[$this->sn]['cells'][$row + $this->_rowoffset][$col + $this->_coloffset] = $string;
				if ($this->store_extended_info && $info) {
					foreach ($info as $key=>$val) {
						$this->sheets[$this->sn]['cellsInfo'][$row + $this->_rowoffset][$col + $this->_coloffset][$key] = $val;
					}
				}
			}
			function _GetIEEE854($rknum) {
				if (($rknum & 0x02) != 0) {
						$value = $rknum >> 2;
				} else {
					//mmp
					// I got my info on IEEE854 encoding from
					// http://research.microsoft.com/~hollasch/cgindex/coding/ieeefloat.html
					// The RK format calls for using only the most significant 30 bits of the
					// 64 bit floating point value. The other 34 bits are assumed to be 0
					// So, we use the upper 30 bits of $rknum as follows...
					$sign = ($rknum & 0x80000000) >> 31;
					$exp = ($rknum & 0x8ff00000) >> 20;
					$mantissa = (0x100000 | ($rknum & 0x000ffffc));
					$value = $mantissa / pow( 2 , (20- ($exp - 1023)));
					if ($sign) {
						$value = -1 * $value;
					}
					//end of changes by mmp
				}
				if (($rknum & 0x01) != 0) {
					$value /= 100;
				}
				return $value;
			}
			function _encodeUTF16($string) {
				$result = $string;
				if ($this->_defaultEncoding){
					switch ($this->_encoderFunction){
						case 'iconv' :	 $result = iconv('UTF-16LE', $this->_defaultEncoding, $string);
										break;
						case 'mb_convert_encoding' :	 $result = mb_convert_encoding($string, $this->_defaultEncoding, 'UTF-16LE' );
										break;
					}
				}
				return $result;
			}
			function _GetInt4d($data, $pos) {
				$value = ord($data[$pos]) | (ord($data[$pos+1]) << 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
				if ($value>=4284868284) {
					$value=-2;
				}
				return $value;
			}
		}
	// ---------- END EXCEL_READER2 ----------
	
	//set_error_handler("myerror");
	
	if($IMP_TYPE == 'COA')
	{
		$this->db->trans_begin();

		$sqlCountCOA	= "tbl_coa_uphist WHERE COAH_STAT = 2 AND COAH_CODE = '$COAH_CODEY'";
		$resCountCOA	= $this->db->count_all($sqlCountCOA);

		$COMPID		= 0;
		$sqlPHO		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJCODE = '$PRJCODE'";
		$resPHO		= $this->db->query($sqlPHO)->result();
		foreach($resPHO as $rowpho) :
			$COMPID = $rowpho->PRJCODE;
		endforeach;

		$isHO		= 0;
		$sqlISHO	= "SELECT isHO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resISHO	= $this->db->query($sqlISHO)->result();
		foreach($resISHO as $rowisho) :
			$isHO 	= $rowisho->isHO;
		endforeach;

		if($resCountCOA == 0)
		{
			$sqlCOAC		= "tbl_chartaccount WHERE PRJCODE != '$PRJCODE'";
			$resCOAC		= $this->db->count_all($sqlCOAC);
			if($resCOAC == 0)
				$sqldelCOA		= "TRUNCATE tbl_chartaccount";
			else
				$sqldelCOA		= "DELETE FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";

			$this->db->query($sqldelCOA);
			
			$sqlUpdITM1		= "UPDATE tbl_coa_uphist SET COAH_STAT = 3 WHERE COAH_STAT = 2";
			$this->db->query($sqlUpdITM1);

			$COAH_FN	= '';
			$COAH_CODEX	= $_POST['IMP_CODEX'];
			$COAH_CODE	= $_POST['IMP_CODEX'];
			$sqlBoQ		= "SELECT COAH_FN FROM tbl_coa_uphist WHERE COAH_CODE = '$COAH_CODEX'";
			$reslBoQ	= $this->db->query($sqlBoQ)->result();
			foreach($reslBoQ as $rowBoQ) :
				$COAH_FN	= $rowBoQ->COAH_FN;
			endforeach;
			
			$myXlsFile	= "$COAH_FN";
			// ADA PERUBAHAN PROSEDURE PEMBACAAN FILE EXCEL
			// PROSEDURE LAMA
				/*$myPath 	= "import_excel/import_coa/$myXlsFile";
				$file 		= file(base_url() . "$myPath"); # read file into array
				$count 		= count($file);
				$data 		= new Spreadsheet_Excel_Reader($myPath);
		
				// membaca jumlah baris dari data excel
				$baris = $data->rowcount($sheet_index=0);

				$totCol			= 0;

				$colName[]	= array();
				for ($i=1; $i <= 100; $i++)
				{
					$colNm			= $data->val(1, $i);
					$colName[$i]	= $data->val(1, $i);
					if($colNm == '')
						break;
					else
						$totCol		= $totCol+1;
				}*/
				
			// START : PROSEDURE BARU
				$myPath 	= APPPATH."xlsxfile/import_coa/$myXlsFile";
				$reader 	= ReaderEntityFactory::createXLSXReader($myPath);

				$reader->open($myPath);
				$rowCellH	= 0;
				$totCol 	= 0;
				$isError 	= 0;

				// CREATE ARRAY HEADER
					foreach ($reader->getSheetIterator() as $sheet)
					{
						foreach ($sheet->getRowIterator() as $row)
						{
							$rowCellH 	= $rowCellH+1;
							
							if($rowCellH == 1)
							{
								$arrHn[]	= array();
								for($cl=0;$cl<=100;$cl++)
								{
						        	$cells 		= $row->getCells();
						        	try 
						        	{
									  	$HeadCells 	= $cells[$cl];
									  	if(isset($cells[$cl]))
									  	{
									  		$HeadNm 		= $HeadCells->getValue() ?: '';
									  		$totCol			= $totCol+1;
									  		$arrHn[$cl]		= $HeadNm;
									  	}
									}
									catch (exceuta $e)
									{
									   	//
									}
								}
							}
							$percent 	= intval(100)."%";
							$collected 	= intval($rowCellH);
							echo '<script>
								  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

						    ob_flush(); 
						    flush();
						}
					}

				// SART : GET ALL ROW DATA AND INSERT INTO TABLE
			        $baris 		= $rowCellH;
					$rata2 		= $baris / 100;				// TOTAL RATA2 PER / 100 --> TOTAL LOOP 100
					$rata2a		= $rata2 * 1;				// TOTAL VALUE PER LOOP
					$rata2b		= intval($rata2 * 1);		// TOTAL LOOP = 100
					$totLoop 	= intval($baris / $rata2a);	// TOTAL LOOP = 100
					$rowCellD	= 0;
					$ORD_ID		= 0;
					$BaseYear2 	= "";

					foreach ($reader->getSheetIterator() as $sheet)
					{
						$rowCellD	= 0;
						foreach ($sheet->getRowIterator() as $row)
						{
							$rowCellD 	= $rowCellD+1;
							if($rowCellD > 1)
							{
								for($rw=0;$rw<$totCol;$rw++)
								{
							  		$cells 		= $row->getCells();
					        		$hdName 	= $arrHn[$rw];
								  	$HeadCells 	= $cells[$rw];
								  	$RowData 	= '';
								  	if(null !== $HeadCells)
							  			$RowData 	= $HeadCells->getValue();

									if($hdName == 'ORD_ID')
										$ORDID 			= $RowData;
									elseif($hdName == 'PRJCODE')
										$PRJCODE 		= $RowData;
									elseif($hdName == 'PRJCODE_HO')
										$PRJCODE_HO 	= $RowData;
									/*elseif($hdName == 'Acc_ID')
										$Acc_ID 		= $RowData;*/
									elseif($hdName == 'Account_Class')
										$Account_Class 	= $RowData;
									elseif($hdName == 'Account_Number')
									{
										$Account_Number = $RowData;
										$Acc_ID 		= $Account_Number;
									}
									elseif($hdName == 'Acc_DirParent')
										$Acc_DirParent 	= $RowData;
									elseif($hdName == 'Account_NameEn')
										$Account_NameEn = $RowData;
									elseif($hdName == 'Account_NameId')
										$Account_NameId = $RowData;
									elseif($hdName == 'Account_Category')
										$Account_Category = $RowData ?: 1;
									elseif($hdName == 'Account_Level')
										$Account_Level 	= $RowData;
									elseif($hdName == 'Default_Acc')
										$Default_Acc 	= $RowData;
									elseif($hdName == 'Base_OpeningBalance')
										$Base_OBal 	= $RowData;
									elseif($hdName == 'Base_Debet')
										$Base_Debet 	= $RowData;
									elseif($hdName == 'Base_Kredit')
										$Base_Kredit 	= $RowData;
									elseif($hdName == 'BaseYear')
										$BaseYear 		= $RowData;
									elseif($hdName == 'COGSReportID')
										$COGSReportID 	= $RowData;
									/*elseif($hdName == 'Link_Report')
										$Link_Report 	= $RowData;*/
									elseif($hdName == 'syncPRJ')
										$syncPRJ 		= $RowData ?: $PRJCODE;
									elseif($hdName == 'isLast')
										$isLast 		= $RowData;

									$ISHEADER			= 0;
									if($isLast == 0)
										$ISHEADER		= 1;
								}

								if($BaseYear == '')
									$BaseYear 		= date('Y');

								$BaseYear2 			= $BaseYear;

								// INSERT INTO COA
									$sqlInsCOA		= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
															Account_Number, Acc_DirParent, Account_NameEn, Account_NameId, Account_Category,
															Account_Level, Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, 
															Base_OpeningBalance, Base_Debet, Base_Kredit,
															Base_OpeningBalance2, Base_Debet2, Base_Kredit2,
															BaseD_$BaseYear, BaseK_$BaseYear,
															Currency_id, COGSReportID, isHO, syncPRJ, isLast)

														VALUES ('$rowCellD', '$PRJCODE', '$PRJCODE_HO', '$Acc_ID', '$Account_Class',
															'$Account_Number', '$Acc_DirParent','$Account_NameEn', '$Account_NameId', '$Account_Category',
															'$Account_Level', 1, 0, '$COMPID', '$Default_Acc',
															'$Base_OBal', '$Base_Debet', '$Base_Kredit',
															'$Base_OBal', '$Base_Debet', '$Base_Kredit',
															'$Base_Debet', '$Base_Kredit',
															'IDR', '$COGSReportID', '$isHO', '$syncPRJ', '$isLast')";
									$this->db->query($sqlInsCOA);

								// TOTAL ROW IMPORTED
							        $sIMP	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
									$rIMP	= $this->db->count_all($sIMP);

								// START : SENDING PROCESS
									$modRow		= $rowCellD % 10;
									$percent 	= intval($rIMP/$baris * 100)."%";
									/*if($modRow == 0)
									{*/
										//sleep(1); // Here call your time taking function like sending bulk sms etc.
										
										echo '<script>
								    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed</span></div>";</script>';

									    ob_flush(); 
									    flush(); 
									//}
								// END : SENDING PROCESS
							}
						}
					}

					// UPDATE STATUS
						$sqlUpdCOA		= "UPDATE tbl_coa_uphist SET COAH_STAT = 2 WHERE COAH_CODE = '$COAH_CODEX'";
						$this->db->query($sqlUpdCOA);

					$percent = intval(100)."%";
					/*echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';*/
					echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed completed</span></div>";</script>';

					echo '<script>
					    parent.document.getElementById("loading_1").style.display ="none";
					    parent.document.getElementById("loading_2").style.display ="none";
					    parent.updStat();</script>';
				// END : GET ALL ROW DATA AND INSERT INTO TABLE
			// END : PROSEDURE BARU
		}
		else
		{
			//echo "Sudah diimport sebelumnya";
			//return false;
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
	else if($IMP_TYPE == 'BOQ_ORI_220518')
	{
		$PRJCODEH	= '';
        $sqlPRJHO 	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
        $resPRJHO	= $this->db->query($sqlPRJHO)->result();
        foreach($resPRJHO as $rwPRJHO):
            $PRJCODEH 	= $rwPRJHO->PRJCODE;
        endforeach;

		$this->db->trans_begin();
			$BOQH_CODEX		= '';
			$sqlBoQC		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
			$resBoQC		= $this->db->count_all($sqlBoQC);
			
			if(isset($_POST['IMP_CODEX']))	// MARKETING
			{
				$BOQH_CODEY		= $_POST['IMP_CODEX'];
				$BOQH_CODEX		= $_POST['IMP_CODEX'];
				$BOQH_CODE		= $_POST['IMP_CODEX'];
				
				$sqlCountBOQ	= "tbl_boq_hist WHERE BOQH_STAT = 2 AND BOQH_CODE = '$BOQH_CODEY'";
				$resCountBOQ	= $this->db->count_all($sqlCountBOQ);

				$PRJCODE_HO 	= "";
				$PRJPERIOD 		= $PRJCODE;
				$PRJPERIOD_P	= $PRJPERIOD;

				$sql 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$result 		= $this->db->query($sql)->result();
				foreach($result as $row) :
					$PRJCODE_HO = $row ->PRJCODE_HO;
				endforeach;

				$sqlUpdITM1		= "UPDATE tbl_boq_hist SET BOQH_STAT = 3 WHERE BOQH_STAT = 2";
				//$this->db->query($sqlUpdITM1);

				$BOQH_FN	= '';
				$BOQH_TYPE	= 2;
				$sqlBoQ		= "SELECT BOQH_FN, BOQH_TYPE FROM tbl_boq_hist WHERE BOQH_CODE = '$BOQH_CODEX'";
				$reslBoQ	= $this->db->query($sqlBoQ)->result();
				foreach($reslBoQ as $rowBoQ) :
					$BOQH_FN	= $rowBoQ->BOQH_FN;
					$BOQH_TYPE	= $rowBoQ->BOQH_TYPE;
				endforeach;
				
				if($BOQH_TYPE == 2)
				{
					$sqlUpdITM1		= "UPDATE tbl_boq_hist SET BOQH_STAT = 3 WHERE BOQH_STAT = 2 AND BOQH_TYPE = 2";
					$this->db->query($sqlUpdITM1);
				
					// SET STATUS
						$sqlSeStatBoQ	= "UPDATE tbl_boqlist SET BOQ_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE'";
						$this->db->query($sqlSeStatBoQ);
						
						$sqlSeStatJL	= "UPDATE tbl_joblist SET WBS_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE'";
						$this->db->query($sqlSeStatJL);
						
						$sqlSeStatJLD	= "UPDATE tbl_joblist_detail SET WBSD_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE'";
						$this->db->query($sqlSeStatJLD);
				
					$sqldelBOQ		= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sqldelBOQ);
				
					$sqldelBOQ		= "DELETE FROM tbl_boqlist_temp WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sqldelBOQ);
					
					$sqldelJL		= "DELETE FROM tbl_joblist WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sqldelJL);
					
					$sqldelJLD		= "DELETE FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sqldelJLD);
				}
				else
				{
					$sqlUpdITM1		= "UPDATE tbl_boq_hist SET BOQH_STAT = 3 WHERE BOQH_STAT = 2 AND BOQH_TYPE = 1";
					$this->db->query($sqlUpdITM1);
					
					// SET STATUS
						$sqlSeStatBoQ	= "UPDATE tbl_boqlistm SET BOQ_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE_HO'";
						$this->db->query($sqlSeStatBoQ);
						
						$sqlSeStatJL	= "UPDATE tbl_joblist SET WBS_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE_HO'";
						//$this->db->query($sqlSeStatJL);
						
						$sqlSeStatJLD	= "UPDATE tbl_joblist_detailm SET WBSD_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE_HO'";
						$this->db->query($sqlSeStatJLD);
				
					$sqldelBOQ		= "DELETE FROM tbl_boqlistm WHERE PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($sqldelBOQ);
					
					$sqldelJL		= "DELETE FROM tbl_joblist WHERE PRJCODE_HO = '$PRJCODE_HO'";
					//$this->db->query($sqldelJL);
					
					$sqldelJLD		= "DELETE FROM tbl_joblist_detailm WHERE PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($sqldelJLD);
				}
				
				$myXlsFile	= "$BOQH_FN";
				// ADA PERUBAHAN PROSEDURE PEMBACAAN FILE EXCEL
				// PROSEDURE LAMA
					/*if($BOQH_TYPE == 1)
						$myPath 	= "import_excel/import_boq/master/$myXlsFile";
					else
						$myPath 	= "import_excel/import_boq/period/$myXlsFile";
						
					$file 		= file(base_url() . "$myPath"); # read file into array
					$count 		= count($file);
					$data 		= new Spreadsheet_Excel_Reader($myPath);
					echo $count;
					return false;
					$totCol		= 0;
					if($BOQH_TYPE == 2)
					{
						$colName[]	= array();
						for ($i=1; $i <= 100; $i++)
						{
							$colNm			= $data->val(1, $i);
							$colName[$i]	= $data->val(1, $i);
							if($colNm == '')
								break;
							else
								$totCol		= $totCol+1;
						}
					}
					
					// membaca jumlah baris dari data excel
					$baris = $data->rowcount($sheet_index=0);*/

				// START : PROSEDURE BARU
					if($BOQH_TYPE == 1)
						$myPath = APPPATH."xlsxfile/import_boq/master/$myXlsFile";
					else
						$myPath = APPPATH."xlsxfile/import_boq/period/$myXlsFile";

					$reader 	= ReaderEntityFactory::createXLSXReader($myPath);

					$reader->open($myPath);
					$rowCellH	= 0;
					$totCol 	= 0;
					$isError 	= 0;

					// CREATE ARRAY HEADER
						foreach ($reader->getSheetIterator() as $sheet)
						{
							foreach ($sheet->getRowIterator() as $row)
							{
								$rowCellH 	= $rowCellH+1;
								if($rowCellH == 1)
								{
									$arrHn[]	= array();
									for($cl=0;$cl<=100;$cl++)
									{
							        	$cells 		= $row->getCells();
							        	try 
							        	{
										  	if(isset($cells[$cl]))
										  	{
										  		$HeadCells 		= $cells[$cl];
										  		$HeadNm 		= $HeadCells->getValue() ?: '';
										  		$totCol			= $totCol+1;
										  		$arrHn[$cl]		= $HeadNm;
										  	}
										}
										catch (exceuta $e)
										{
										   	//
										}
									}
								}
								$percent 	= intval(100)."%";
								$collected 	= intval($rowCellH);
								echo '<script>
									  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

							    ob_flush(); 
							    flush();
							}
						}

					// START : GET ALL ROW DATA AND INSERT INTO TABLE
				        $baris 		= $rowCellH;
				        $barisD 	= $rowCellH-1;				// DIKURANGI HEADER
						$rata2 		= $baris / 100;				// TOTAL RATA2 PER / 100 --> TOTAL LOOP 100
						$rata2a		= $rata2 * 1;				// TOTAL VALUE PER LOOP
						$rata2b		= intval($rata2 * 1);		// TOTAL LOOP = 100
						$totLoop 	= intval($baris / $rata2a);	// TOTAL LOOP = 100
						$rowCellD	= 0;
						$ORD_ID		= 0;
						$ORD_IDH 	= 0;
						$PATT_NO 	= 0;

						foreach ($reader->getSheetIterator() as $sheet)
						{
							$rowCellD	= 0;
							foreach ($sheet->getRowIterator() as $row)
							{
								$rowCellD 	= $rowCellD+1;
								if($rowCellD > 1)
								{
									$ORDID 		= $rowCellD-1;
									$ITM_CODE 	= "";
									$JOBDESC 	= "";
									for($rw=0;$rw<$totCol;$rw++)
									{
							        	$cells 		= $row->getCells();
						        		$hdName 	= $arrHn[$rw];
									  	$HeadCells 	= $cells[$rw];
									  	$RowData 	= '';
									  	if(null !== $HeadCells)
								  			$RowData 	= $HeadCells->getValue();

										if($hdName == 'JOBCODEID')
										{
											$JOBCODEID 		= $RowData;
											$JOBCODEDET 	= $JOBCODEID;
										}
										elseif($hdName == 'JOBPARENT')
											$JOBPARENT 		= $RowData;
										elseif($hdName == 'JOBCODEID_P')
											$JOBCODEID_P 	= $RowData;
										/*elseif($hdName == 'PRJCODE')
											$PRJCODE 		= $RowData;*/
										/*elseif($hdName == 'PRJPERIOD')
										{
											$PRJPERIOD 		= $RowData;
											$PRJPERIOD_P	= $PRJPERIOD;
										}*/
										elseif($hdName == 'JOBDESC')
											$JOBDESC 		= addslashes($RowData);
										elseif($hdName == 'ITM_GROUP')
											$ITM_GROUP 		= $RowData;
										elseif($hdName == 'GROUP_CATEG')
											$GROUP_CATEG 	= $RowData;
										elseif($hdName == 'ITM_CODE')
											$ITM_CODE 		= $RowData;
										elseif($hdName == 'ITM_UNIT')
											$ITM_UNIT 		= addslashes($RowData);
										elseif($hdName == 'ITM_VOLM')
										{
											$ITM_VOLM1 		= $RowData;
											$ITM_VOLM2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $ITM_VOLM1);
											$ITM_VOLM 		= sprintf("%f", $ITM_VOLM1);
											if($ITM_VOLM == '')
												$ITM_VOLM	= 0;
										}
										elseif($hdName == 'ITM_PRICE')
										{
											$ITM_PRICE1 	= $RowData;
											$ITM_PRICE2		= preg_replace('/[^A-Za-z0-8\.]/', '', $ITM_PRICE1);
											$ITM_PRICE 		= sprintf("%f", $ITM_PRICE1);
											if($ITM_PRICE == '')
												$ITM_PRICE	= 0;
											$ITM_LASTP 		= $ITM_PRICE;
										}
										elseif($hdName == 'ITM_BUDG')
										{
											$ITM_BUDG1 		= $RowData;
											$ITM_BUDG2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $ITM_BUDG1);
											$ITM_BUDG 		= sprintf("%f", $ITM_BUDG1);
											if($ITM_BUDG == '')
												$ITM_BUDG	= 0;
										}
										elseif($hdName == 'BOQ_VOLM')
										{
											$BOQ_VOLM1 		= $RowData;
											$BOQ_VOLM2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $BOQ_VOLM1);
											$BOQ_VOLM 		= sprintf("%f", $BOQ_VOLM1);
											if($BOQ_VOLM == '')
												$BOQ_VOLM	= 0;
										}
										elseif($hdName == 'BOQ_PRICE')
										{
											$BOQ_PRICE1 	= $RowData;
											$BOQ_PRICE2		= preg_replace('/[^A-Za-z0-8\.]/', '', $BOQ_PRICE1);
											$BOQ_PRICE 		= sprintf("%f", $BOQ_PRICE1);
											if($BOQ_PRICE == '')
												$BOQ_PRICE	= 0;
										}
										elseif($hdName == 'BOQ_BUDG')
										{
											$BOQ_BUDG1 		= $RowData;
											$BOQ_BUDG2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $BOQ_BUDG1);
											$BOQ_BUDG 		= sprintf("%f", $BOQ_BUDG1);
											if($BOQ_BUDG == '')
												$BOQ_BUDG	= 0;
										}
										elseif($hdName == 'BOQ_BOBOT')
										{
											$BOQ_BOBOT1		= $RowData;
											$BOQ_BOBOT2		= preg_replace('/[^A-Za-z0-8\.]/', '', $BOQ_BOBOT1);
											$BOQ_BOBOT 		= sprintf("%f", $BOQ_BOBOT1);
											if($BOQ_BOBOT == '')
												$BOQ_BOBOT	= 0;
										}
										elseif($hdName == 'ISBOBOT')
										{
											$ISBOBOT1 		= $RowData;
											$ISBOBOT2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $ISBOBOT1);
											$ISBOBOT 		= sprintf("%f", $ISBOBOT1);
											if($ISBOBOT == '')
												$ISBOBOT	= 0;
										}
										elseif($hdName == 'IS_LEVEL')
											$IS_LEVEL 		= $RowData;
										elseif($hdName == 'ISLASTH')
											$ISLASTH 		= $RowData;
										elseif($hdName == 'ISLAST')
											$ISLAST 		= $RowData;
										elseif($hdName == 'Patt_Number')
											$Patt_Number 	= $RowData;
									}
									/*if($Patt_Number == '')
									{*/
										$PATT_NO  		= $PATT_NO + 1;
										$Patt_Number 	= $PATT_NO;
									//}

									//$BOQ_BUDG 		= $BOQ_BUDG ?: 0;
									$ISHEADER		= 0;
									if($ISLAST == 0)
										$ISHEADER	= 1;

									if(strtoupper($ITM_UNIT) == 'LUMP' || strtoupper($ITM_UNIT) == 'LS')
									{
										$BOQ_VOLM 		= $BOQ_BUDG;
										$BOQ_PRICE 		= 1;

										$ITM_VOLM 		= $ITM_BUDG;
										$ITM_PRICE 		= 1;
										$ITM_LASTP 		= 1;
									}
									
									if($ISLAST == 0)
									{
										$ORD_IDH 		= $ORD_IDH+1;
										// INSERT INTO BOQ
											$sqlInsBoQ	= "INSERT INTO tbl_boqlist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT,
																PRJCODE, PRJCODE_HO,
																ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV,
																JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST,
																PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT, ISBOBOT,
																ISHEADER, ISLASTH, ISLAST, Patt_Number)
															VALUES ($ORD_IDH, '$JOBCODEID', '$JOBCODEID', '$JOBPARENT', 
																'$PRJCODE', '$PRJCODE_HO',
																'$ITM_CODE', '$JOBDESC','$ITM_GROUP', '$ITM_UNIT', '$IS_LEVEL',
																'$ITM_VOLM', '$ITM_PRICE', '$ITM_BUDG', '$BOQ_VOLM', '$BOQ_PRICE', '$BOQ_BUDG', 
																'$PRJPERIOD', '$PRJPERIOD_P', '$BOQ_BOBOT', '$ISBOBOT',
																'$ISHEADER', '$ISLASTH', '$ISLAST', '$Patt_Number')";
											$this->db->query($sqlInsBoQ);
										
										// INSERT INTO Joblist. SEHARUSNYA UNTUK JOBLIST HANYA MENYIMPAN HEADER
											/*if($ISHEADER == 1)
											{*/
												$sqlInsJL	= "INSERT INTO tbl_joblist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, 
																	PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP,
																	JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST,
																	BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P, BOQ_JOBCOST,
																	BOQ_BOBOT, ISBOBOT, ISHEADER, ISLASTH, ISLAST, Patt_Number)
																VALUES ($ORD_IDH, '$JOBCODEID', '$JOBCODEID', '$JOBPARENT',
																	'$PRJCODE', '$PRJCODE_HO', '$ITM_CODE', '$JOBDESC', '$ITM_GROUP',
																	'$ITM_UNIT', '$IS_LEVEL', '$ITM_VOLM', '$ITM_PRICE', '$ITM_BUDG',
																	'$BOQ_VOLM', '$BOQ_PRICE', '$PRJPERIOD', '$PRJPERIOD_P', '$BOQ_BUDG', 
																	'$BOQ_BOBOT', '$ISBOBOT', '$ISHEADER', '$ISLASTH', '$ISLAST', '$Patt_Number')";
												$this->db->query($sqlInsJL);
											/*}*/
									}
									else if($ISLAST == 1)
									{
										$ACC_ID		= "";
										$ACC_ID_UM 	= "";
										$ACC_ID_SAL = "";
										$ITM_LR 	= "";
										$ITM_CATEG 	= "";
										$ITM_TYPE 	= "";
										$sqlITMACC	= "SELECT ACC_ID, ACC_ID_UM, ACC_ID_SAL, ITM_LR, ITM_CATEG, ITM_TYPE FROM tbl_item
														WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODEH' LIMIT 1";
										$resITMACC	= $this->db->query($sqlITMACC)->result();
										foreach($resITMACC as $rwITMACC) :
											$ACC_ID		= $rwITMACC->ACC_ID;
											$ACC_ID_UM	= $rwITMACC->ACC_ID_UM;
											$ACC_ID_SAL	= $rwITMACC->ACC_ID_SAL;
											$ITM_LR		= $rwITMACC->ITM_LR;
											$ITM_CATEG	= $rwITMACC->ITM_CATEG;
											$ITM_TYPE	= $rwITMACC->ITM_TYPE;
										endforeach;

										$s_00 		= 	"INSERT INTO tbl_item (PRJCODE, PRJCODE_HO, PRJPERIOD, ITM_CODE, ITM_GROUP, 
															ITM_CATEG, ITM_NAME, ITM_TYPE, ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLMBG,
															ITM_VOLMBGR, ITM_VOLM,  ITM_PRICE, ITM_REMQTY, ITM_TOTALP, ITM_LASTP, ITM_AVGP, BOQ_ITM_VOLM, 
															BOQ_ITM_PRICE, BOQ_ITM_TOTALP, ACC_ID, ACC_ID_UM, ACC_ID_SAL, STATUS, ITM_LR)
													    SELECT '$PRJCODE', '$PRJCODE_HO', '$PRJPERIOD', '$ITM_CODE', '$ITM_GROUP',
													    	'$ITM_CATEG', '$JOBDESC', '$ITM_TYPE', '$ITM_UNIT', '$ITM_UNIT', 'IDR', '$ITM_VOLM',
													    	'$ITM_VOLM', '0', '$ITM_PRICE', '0', '0', '$ITM_PRICE', '$ITM_PRICE', '$BOQ_VOLM',
													    	'$BOQ_PRICE', '$ITM_BUDG', '$ACC_ID', '$ACC_ID_UM', '$ACC_ID_SAL', 1, '$ITM_LR'
													    WHERE NOT EXISTS (SELECT ITM_CODE FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE')";
										$this->db->query($s_00);

										$s_01 		= 	"INSERT INTO tbl_item (PRJCODE, PRJPERIOD, ITM_CODE, ITM_GROUP, 
															ITM_CATEG, ITM_NAME, ITM_TYPE, ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLMBG,
															ITM_VOLMBGR, ITM_VOLM,  ITM_PRICE, ITM_REMQTY, ITM_TOTALP, ITM_LASTP, ITM_AVGP, BOQ_ITM_VOLM, 
															BOQ_ITM_PRICE, BOQ_ITM_TOTALP, ACC_ID, ACC_ID_UM, ACC_ID_SAL, STATUS, ITM_LR)
													    SELECT '$PRJCODEH', '$PRJCODEH', '$ITM_CODE', '$ITM_GROUP',
													    	'$ITM_CATEG', '$JOBDESC', '$ITM_TYPE', '$ITM_UNIT', '$ITM_UNIT', 'IDR', '$ITM_VOLM',
													    	'$ITM_VOLM', '0', '$ITM_PRICE', '0', '0', '$ITM_PRICE', '$ITM_PRICE', '$BOQ_VOLM',
													    	'$BOQ_PRICE', '$ITM_BUDG', '$ACC_ID', '$ACC_ID_UM', '$ACC_ID_SAL', 1, '$ITM_LR'
													    WHERE NOT EXISTS (SELECT ITM_CODE FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODEH')";
										$this->db->query($s_01);
									}

									// INSERT INTO JoblistDetail
										$sqlInsJLD		= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, 
																PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, 
																BOQ_BOBOT, ISBOBOT, IS_LEVEL, ISLASTH, ISLAST, Patt_Number)
															VALUES ($ORDID, '$JOBCODEDET', '$JOBCODEID', '$JOBPARENT', 
																'$PRJCODE', '$PRJCODE_HO', '$ITM_CODE', '$JOBDESC',
																'$ITM_GROUP','$GROUP_CATEG', '$ITM_UNIT', '$ITM_VOLM', '$ITM_PRICE', '$ITM_LASTP', 
																'$PRJPERIOD', '$PRJPERIOD_P', '$ITM_BUDG', '$BOQ_VOLM', '$BOQ_PRICE', '$BOQ_BUDG',
																'$BOQ_BOBOT', '$ISBOBOT', '$IS_LEVEL', '$ISLASTH', '$ISLAST', '$Patt_Number')";
										$this->db->query($sqlInsJLD);

									// TOTAL ROW IMPORTED
								        $sIMP	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
										$rIMP	= $this->db->count_all($sIMP);

									// START : SENDING PROCESS
										$modRow		= $rowCellD % 10;
										$percent 	= intval($rIMP/$baris * 100)."%";
										/*if($modRow == 0)
										{*/
											//sleep(1); // Here call your time taking function like sending bulk sms etc.
											
											echo '<script>
									    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed</span></div>";</script>';

										    ob_flush(); 
										    flush(); 
										//}
									// END : SENDING PROCESS
								}
							}
							$percent = intval(100)."%";    
							/*echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-succes cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';*/
							echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed completed</span></div>";</script>';

						    ob_flush(); 
						    flush();
						}
						$reader->close();
					// END : GET ALL ROW DATA AND INSERT INTO TABLE
				// END : PROSEDURE BARU

				$percent = intval(100)."%";
				echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';

			    ob_flush(); 
			    flush();

				// IMPORT CONCLUSION
					$TotBOQ		= 0;
					$BOQ_TotBOQ	= 0;
					if($BOQH_TYPE == 1)
					{
						$sqlJCOST	= "SELECT JOBCOST, BOQ_JOBCOST FROM tbl_boqlistm WHERE PRJCODE = '$PRJCODE' AND JOBLEV = 1 AND PRJPERIOD = '$PRJPERIOD'";
						$resJCOST 	= $this->db->query($sqlJCOST)->result();
						foreach($resJCOST as $rowJCOST) :
							$JOBCOST 	= $rowJCOST->JOBCOST;
							$BOQ_JOBCOST= $rowJCOST->BOQ_JOBCOST;
							$TotBOQM	= $TotBOQ + $JOBCOST;			// RAP
							$BOQ_TotBOQ	= $BOQ_TotBOQ + $BOQ_JOBCOST;	// RAB / BoQ
						endforeach;	
						$sqlUPDPRJ	= "UPDATE tbl_project_budgm SET PRJBOQ = $BOQ_TotBOQM, PRJCOST = $BOQ_TotBOQM, PRJRAP = $TotBOQM
										WHERE PRJCODE = '$PRJCODE'";
						$this->db->query($sqlUPDPRJ);
					}
					else
					{
						$sqlJCOST	= "SELECT JOBCOST, BOQ_JOBCOST FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBLEV = 1 AND PRJPERIOD = '$PRJPERIOD'";
						$resJCOST 	= $this->db->query($sqlJCOST)->result();
						foreach($resJCOST as $rowJCOST) :
							$JOBCOST 	= $rowJCOST->JOBCOST;
							$BOQ_JOBCOST= $rowJCOST->BOQ_JOBCOST;
							$TotBOQ		= $TotBOQ + $JOBCOST;			// RAP
							$BOQ_TotBOQ	= $BOQ_TotBOQ + $BOQ_JOBCOST;	// RAB / BoQ
						endforeach;
						
						// UPDATE RAB AND RAP IN PROJECT
						$sqlUPDPRJ	= "UPDATE tbl_project SET PRJBOQ = $BOQ_TotBOQ, PRJCOST = $BOQ_TotBOQ, PRJRAP = $TotBOQ
										WHERE PRJCODE = '$PRJCODE'";
						$this->db->query($sqlUPDPRJ);

						$sqlUPDPRJ	= "UPDATE tbl_project_budg SET PRJBOQ = $BOQ_TotBOQ, PRJCOST = $BOQ_TotBOQ, PRJRAP = $TotBOQ
										WHERE PRJCODE = '$PRJCODE'";
						$this->db->query($sqlUPDPRJ);
					}
					// UPDATE STATUS
						$sqlUpdBoQ	= "UPDATE tbl_boq_hist SET BOQH_STAT = 2 WHERE BOQH_CODE = '$BOQH_CODEX'";
						$this->db->query($sqlUpdBoQ);

						$sqlUpdITM	= "UPDATE tbl_item A SET A.ITM_VOLMBG = (SELECT SUM(B.ITM_VOLM) FROM tbl_joblist_detail B WHERE B.ITM_CODE = A.ITM_CODE
												AND B.PRJCODE = A.PRJCODE),
											A.ITM_VOLMBGR = A.ITM_VOLMBG,
											A.BOQ_ITM_VOLM = A.ITM_VOLMBG,
											A.BOQ_ITM_TOTALP = (SELECT SUM(B.ITM_BUDG) FROM tbl_joblist_detail B WHERE B.ITM_CODE = A.ITM_CODE
												AND B.PRJCODE = A.PRJCODE),
											A.BOQ_ITM_PRICE	= A.BOQ_ITM_TOTALP / A.ITM_VOLMBG
										WHERE A.PRJCODE = '$PRJCODE'";
						$this->db->query($sqlUpdITM);

				// UPDATE HEADER WHEN ZERO
					$s_00c 	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_BUDG = 0 AND ISLAST = 0";
					$r_00c 	= $this->db->count_all($s_00c);

					$GROW 	= 0;
					$s_00 	= "SELECT JOBCODEID, IS_LEVEL, ITM_BUDG, BOQ_JOBCOST, ADD_JOBCOST, ITM_USED_AM FROM tbl_joblist_detail
								WHERE PRJCODE = '$PRJCODE' AND ITM_BUDG = 0 AND ISLAST = 0";
					$r_00 	= $this->db->query($s_00)->result();
					foreach($r_00 as $rw_00):
						$GROW 		= $GROW+1;
						$JOBID 		= $rw_00->JOBCODEID;
						$JOBLEV		= $rw_00->IS_LEVEL;
						$JOBBUDG	= $rw_00->ITM_BUDG;
						$JOBBOQVAL	= $rw_00->BOQ_JOBCOST;
						$JOBADDVAL 	= $rw_00->ADD_JOBCOST;
						$JOBUSEVAL 	= $rw_00->ITM_USED_AM;

						if($JOBBUDG == 0)
						{
							$sqlTBUDG	= "SELECT IF(SUM(ITM_BUDG) != '', SUM(ITM_BUDG), 0) AS TOT_BUDGAM, IF(SUM(ADD_JOBCOST) != '',
												SUM(ADD_JOBCOST), 0) AS TOT_ADDAM, IF(SUM(ITM_USED_AM) != '', SUM(ITM_USED_AM), 0) AS TOT_USEDAM
											FROM tbl_joblist_detail
											WHERE JOBPARENT LIKE '$JOBID%' AND PRJCODE = '$PRJCODE'
												AND IS_LEVEL > $JOBLEV";
							echo "$sqlTBUDG ==== ";
							$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
							foreach($resTBUDG as $rowTBUDG) :
								$TOT_BUDGAM	= $rowTBUDG->TOT_BUDGAM;
								$TOT_ADDAM	= $rowTBUDG->TOT_ADDAM;
								$TOT_USEDAM	= $rowTBUDG->TOT_USEDAM;

								$s_01 	= "UPDATE tbl_joblist SET JOBCOST = $TOT_BUDGAM, ADD_JOBCOST = $TOT_ADDAM
											WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBID'";
								$this->db->query($s_01);
								$s_02 	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_BUDGAM
											WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBID'";
								$this->db->query($s_02);
								$s_03 	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_BUDGAM, ADD_JOBCOST = $TOT_ADDAM,
											ITM_USED_AM = $TOT_USEDAM WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBID'";
								$this->db->query($s_03);
								echo "$s_03<br>";
							endforeach;
						}

						if($JOBBOQVAL == 0)
						{
							$sqlTBUDG	= "SELECT IF(SUM(BOQ_JOBCOST) != '', SUM(BOQ_JOBCOST), 0) AS TOT_BOQAM FROM tbl_joblist_detail
											WHERE JOBPARENT LIKE '$JOBID%' AND PRJCODE = '$PRJCODE' AND IS_LEVEL > $JOBLEV AND ISLASTH = 1";
							$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
							foreach($resTBUDG as $rowTBUDG) :
								$TOT_BOQAM	= $rowTBUDG->TOT_BOQAM;

								$s_01 	= "UPDATE tbl_joblist SET BOQ_JOBCOST = $TOT_BOQAM
											WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBID'";
								$this->db->query($s_01);
								$s_02 	= "UPDATE tbl_boqlist SET BOQ_JOBCOST = $TOT_BOQAM
											WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBID'";
								$this->db->query($s_02);
								$s_03 	= "UPDATE tbl_joblist_detail SET BOQ_JOBCOST = $TOT_BOQAM WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBID'";
								$this->db->query($s_03);
							endforeach;
						}

						// START : SENDING PROCESS
							$percent 	= intval($GROW/$r_00c * 100)."%";

							echo '<script>
						    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$GROW. ' / '.$r_00c.') processed syncronized</span></div>";</script>';

						    ob_flush(); 
						    flush(); 
						// END : SENDING PROCESS
					endforeach;

				// IMPORT COMPLETE INFO
					$percent = intval(100)."%";
					echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
					echo '<script>
					    parent.document.getElementById("loading_1").style.display ="none";
					    parent.document.getElementById("loading_2").style.display ="none";
					    parent.updStat();</script>';
			}
			else
			{
				//echo "Sudah diimport sebelumnya";
				//return false;
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
	else if($IMP_TYPE == 'BOQ')
	{
		$PRJCODEH	= '';
        $sqlPRJHO 	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
        $resPRJHO	= $this->db->query($sqlPRJHO)->result();
        foreach($resPRJHO as $rwPRJHO):
            $PRJCODEH 	= $rwPRJHO->PRJCODE;
        endforeach;

		$this->db->trans_begin();
			$BOQH_CODEX		= '';
			$sqlBoQC		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
			$resBoQC		= $this->db->count_all($sqlBoQC);
			
			if(isset($_POST['IMP_CODEX']))	// MARKETING
			{
				$BOQH_CODEY		= $_POST['IMP_CODEX'];
				$BOQH_CODEX		= $_POST['IMP_CODEX'];
				$BOQH_CODE		= $_POST['IMP_CODEX'];
				
				$sqlCountBOQ	= "tbl_boq_hist WHERE BOQH_STAT = 2 AND BOQH_CODE = '$BOQH_CODEY'";
				$resCountBOQ	= $this->db->count_all($sqlCountBOQ);

				$PRJCODE_HO 	= "";
				$PRJPERIOD 		= $PRJCODE;
				$PRJPERIOD_P	= $PRJPERIOD;

				$sql 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$result 		= $this->db->query($sql)->result();
				foreach($result as $row) :
					$PRJCODE_HO = $row ->PRJCODE_HO;
				endforeach;

				$sqlUpdITM1		= "UPDATE tbl_boq_hist SET BOQH_STAT = 3 WHERE BOQH_STAT = 2";
				//$this->db->query($sqlUpdITM1);

				$BOQH_FN	= '';
				$BOQH_TYPE	= 2;
				$sqlBoQ		= "SELECT BOQH_FN, BOQH_TYPE FROM tbl_boq_hist WHERE BOQH_CODE = '$BOQH_CODEX'";
				$reslBoQ	= $this->db->query($sqlBoQ)->result();
				foreach($reslBoQ as $rowBoQ) :
					$BOQH_FN	= $rowBoQ->BOQH_FN;
					$BOQH_TYPE	= $rowBoQ->BOQH_TYPE;
				endforeach;
				
				if($BOQH_TYPE == 2)
				{
					$sqlUpdITM1		= "UPDATE tbl_boq_hist SET BOQH_STAT = 3 WHERE BOQH_STAT = 2 AND BOQH_TYPE = 2";
					$this->db->query($sqlUpdITM1);
				
					// SET STATUS
						$sqlSeStatBoQ	= "UPDATE tbl_boqlist SET BOQ_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE'";
						$this->db->query($sqlSeStatBoQ);
						
						$sqlSeStatJL	= "UPDATE tbl_joblist SET WBS_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE'";
						$this->db->query($sqlSeStatJL);
						
						$sqlSeStatJLD	= "UPDATE tbl_joblist_detail SET WBSD_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE'";
						$this->db->query($sqlSeStatJLD);
				
					$sqldelBOQ		= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sqldelBOQ);
				
					$sqldelBOQ		= "DELETE FROM tbl_boqlist_temp WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sqldelBOQ);
					
					$sqldelJL		= "DELETE FROM tbl_joblist WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sqldelJL);
					
					$sqldelJLD		= "DELETE FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sqldelJLD);
				}
				else
				{
					$sqlUpdITM1		= "UPDATE tbl_boq_hist SET BOQH_STAT = 3 WHERE BOQH_STAT = 2 AND BOQH_TYPE = 1";
					$this->db->query($sqlUpdITM1);
					
					// SET STATUS
						$sqlSeStatBoQ	= "UPDATE tbl_boqlistm SET BOQ_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE_HO'";
						$this->db->query($sqlSeStatBoQ);
						
						$sqlSeStatJL	= "UPDATE tbl_joblist SET WBS_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE_HO'";
						//$this->db->query($sqlSeStatJL);
						
						$sqlSeStatJLD	= "UPDATE tbl_joblist_detailm SET WBSD_STAT = 0 WHERE PRJCODE_HO = '$PRJCODE_HO'";
						$this->db->query($sqlSeStatJLD);
				
					$sqldelBOQ		= "DELETE FROM tbl_boqlistm WHERE PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($sqldelBOQ);
					
					$sqldelJL		= "DELETE FROM tbl_joblist WHERE PRJCODE_HO = '$PRJCODE_HO'";
					//$this->db->query($sqldelJL);
					
					$sqldelJLD		= "DELETE FROM tbl_joblist_detailm WHERE PRJCODE_HO = '$PRJCODE_HO'";
					$this->db->query($sqldelJLD);
				}
				
				$myXlsFile	= "$BOQH_FN";
				// START STEP 1 : PROSEDURE BARU
					if($BOQH_TYPE == 1)
						$myPath = APPPATH."xlsxfile/import_boq/master/$myXlsFile";
					else
						$myPath = APPPATH."xlsxfile/import_boq/period/$myXlsFile";

					$reader 	= ReaderEntityFactory::createXLSXReader($myPath);

					$reader->open($myPath);
					$rowCellH	= 0;
					$totCol 	= 0;
					$isError 	= 0;

					// START : CREATE ARRAY HEADER
						foreach ($reader->getSheetIterator() as $sheet)
						{
							foreach ($sheet->getRowIterator() as $row)
							{
								$rowCellH 	= $rowCellH+1;
								if($rowCellH == 1)
								{
									$arrHn[]	= array();
									for($cl=0;$cl<=100;$cl++)
									{
							        	$cells 		= $row->getCells();
							        	try 
							        	{
										  	if(isset($cells[$cl]))
										  	{
										  		$HeadCells 		= $cells[$cl];
										  		$HeadNm 		= $HeadCells->getValue() ?: '';
										  		$totCol			= $totCol+1;
										  		$arrHn[$cl]		= $HeadNm;
										  	}
										}
										catch (exceuta $e)
										{
										   	//
										}
									}
								}
								$percent 	= intval(100)."%";
								$collected 	= intval($rowCellH);
								echo '<script>
									  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

							    ob_flush(); 
							    flush();
							}
						}
					// END : CREATE ARRAY HEADER

					// START : GET ALL ROW DATA AND INSERT INTO TABLE
				        $baris 		= $rowCellH;
				        $barisD 	= $rowCellH-1;				// DIKURANGI HEADER
						$rata2 		= $baris / 100;				// TOTAL RATA2 PER / 100 --> TOTAL LOOP 100
						$rata2a		= $rata2 * 1;				// TOTAL VALUE PER LOOP
						$rata2b		= intval($rata2 * 1);		// TOTAL LOOP = 100
						$totLoop 	= intval($baris / $rata2a);	// TOTAL LOOP = 100
						$rowCellD	= 0;
						$ORD_ID		= 0;
						$ORD_IDH 	= 0;
						$PATT_NO 	= 0;

						foreach ($reader->getSheetIterator() as $sheet)
						{
							$rowCellD	= 0;
							foreach ($sheet->getRowIterator() as $row)
							{
								$rowCellD 	= $rowCellD+1;
								if($rowCellD > 1)
								{
									$ORDID 		= $rowCellD-1;
									$ITM_CODE 	= "";
									$JOBDESC 	= "";
									for($rw=0;$rw<$totCol;$rw++)
									{
							        	$cells 		= $row->getCells();
						        		$hdName 	= $arrHn[$rw];
									  	$HeadCells 	= $cells[$rw];
									  	$RowData 	= '';
									  	if(null !== $HeadCells)
								  			$RowData 	= $HeadCells->getValue();

										if($hdName == 'JOBCODEID')
										{
											$JOBCODEID 		= $RowData;
											$JOBCODEDET 	= $JOBCODEID;
										}
										elseif($hdName == 'JOBPARENT')
											$JOBPARENT 		= $RowData;
										elseif($hdName == 'JOBCODEID_P')
											$JOBCODEID_P 	= $RowData;
										/*elseif($hdName == 'PRJCODE')
											$PRJCODE 		= $RowData;*/
										/*elseif($hdName == 'PRJPERIOD')
										{
											$PRJPERIOD 		= $RowData;
											$PRJPERIOD_P	= $PRJPERIOD;
										}*/
										elseif($hdName == 'JOBDESC')
											$JOBDESC 		= addslashes($RowData);
										elseif($hdName == 'ITM_GROUP')
											$ITM_GROUP 		= $RowData;
										elseif($hdName == 'GROUP_CATEG')
											$GROUP_CATEG 	= $RowData;
										elseif($hdName == 'ITM_CODE')
											$ITM_CODE 		= $RowData;
										elseif($hdName == 'ITM_UNIT')
											$ITM_UNIT 		= addslashes($RowData);
										elseif($hdName == 'ITM_VOLM')
										{
											$ITM_VOLM1 		= $RowData;
											$ITM_VOLM2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $ITM_VOLM1);
											$ITM_VOLM 		= sprintf("%f", $ITM_VOLM1);
											if($ITM_VOLM == '')
												$ITM_VOLM	= 0;
										}
										elseif($hdName == 'ITM_PRICE')
										{
											$ITM_PRICE1 	= $RowData;
											$ITM_PRICE2		= preg_replace('/[^A-Za-z0-8\.]/', '', $ITM_PRICE1);
											$ITM_PRICE 		= sprintf("%f", $ITM_PRICE1);
											if($ITM_PRICE == '')
												$ITM_PRICE	= 0;
											$ITM_LASTP 		= $ITM_PRICE;
										}
										elseif($hdName == 'ITM_BUDG')
										{
											$ITM_BUDG1 		= $RowData;
											$ITM_BUDG2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $ITM_BUDG1);
											$ITM_BUDG 		= sprintf("%f", $ITM_BUDG1);
											if($ITM_BUDG == '')
												$ITM_BUDG	= 0;
										}
										elseif($hdName == 'BOQ_VOLM')
										{
											$BOQ_VOLM1 		= $RowData;
											$BOQ_VOLM2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $BOQ_VOLM1);
											$BOQ_VOLM 		= sprintf("%f", $BOQ_VOLM1);
											if($BOQ_VOLM == '')
												$BOQ_VOLM	= 0;
										}
										elseif($hdName == 'BOQ_PRICE')
										{
											$BOQ_PRICE1 	= $RowData;
											$BOQ_PRICE2		= preg_replace('/[^A-Za-z0-8\.]/', '', $BOQ_PRICE1);
											$BOQ_PRICE 		= sprintf("%f", $BOQ_PRICE1);
											if($BOQ_PRICE == '')
												$BOQ_PRICE	= 0;
										}
										elseif($hdName == 'BOQ_BUDG')
										{
											$BOQ_BUDG1 		= $RowData;
											$BOQ_BUDG2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $BOQ_BUDG1);
											$BOQ_BUDG 		= sprintf("%f", $BOQ_BUDG1);
											if($BOQ_BUDG == '')
												$BOQ_BUDG	= 0;
										}
										elseif($hdName == 'BOQ_BOBOT')
										{
											$BOQ_BOBOT1		= $RowData;
											$BOQ_BOBOT2		= preg_replace('/[^A-Za-z0-8\.]/', '', $BOQ_BOBOT1);
											$BOQ_BOBOT 		= sprintf("%f", $BOQ_BOBOT1);
											if($BOQ_BOBOT == '')
												$BOQ_BOBOT	= 0;
										}
										elseif($hdName == 'ISBOBOT')
										{
											$ISBOBOT1 		= $RowData;
											$ISBOBOT2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $ISBOBOT1);
											$ISBOBOT 		= sprintf("%f", $ISBOBOT1);
											if($ISBOBOT == '')
												$ISBOBOT	= 0;
										}
										elseif($hdName == 'IS_LEVEL')
											$IS_LEVEL 		= $RowData;
										elseif($hdName == 'ISLASTH')
											$ISLASTH 		= $RowData;
										elseif($hdName == 'ISLAST')
											$ISLAST 		= $RowData;
										elseif($hdName == 'Patt_Number')
											$Patt_Number 	= $RowData;
									}
									/*if($Patt_Number == '')
									{*/
										$PATT_NO  		= $PATT_NO + 1;
										$Patt_Number 	= $PATT_NO;
									//}

									//$BOQ_BUDG 		= $BOQ_BUDG ?: 0;
									$ISHEADER		= 0;
									if($ISLAST == 0)
										$ISHEADER	= 1;

									if(strtoupper($ITM_UNIT) == 'LUMP' || strtoupper($ITM_UNIT) == 'LS')
									{
										$BOQ_VOLM 		= 1;
										$BOQ_PRICE 		= $BOQ_BUDG;

										$ITM_VOLM 		= 1;
										$ITM_PRICE 		= $ITM_BUDG;
										$ITM_LASTP 		= $ITM_BUDG;
									}

									$ORD_IDH 	= $ORD_IDH+1;

									$sqlInsBoQ	= "INSERT INTO tbl_boqlist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT,
														PRJCODE, PRJCODE_HO,
														ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT,
														JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST,
														PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT, ISBOBOT, Patt_Number)
													VALUES ($ORD_IDH, '$JOBCODEID', '$JOBCODEID', '$JOBPARENT', 
														'$PRJCODE', '$PRJCODE_HO',
														'$ITM_CODE', '$JOBDESC','$ITM_GROUP', '$ITM_UNIT',
														'$ITM_VOLM', '$ITM_PRICE', '$ITM_BUDG', '$BOQ_VOLM', '$BOQ_PRICE', '$BOQ_BUDG', 
														'$PRJPERIOD', '$PRJPERIOD_P', '$BOQ_BOBOT', '$ISBOBOT', '$Patt_Number')";
									$this->db->query($sqlInsBoQ);

									$sqlInsJL	= "INSERT INTO tbl_joblist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, 
														PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP,
														JOBUNIT, JOBVOLM, PRICE, JOBCOST,
														BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P, BOQ_JOBCOST,
														BOQ_BOBOT, ISBOBOT, Patt_Number)
													VALUES ($ORD_IDH, '$JOBCODEID', '$JOBCODEID', '$JOBPARENT',
														'$PRJCODE', '$PRJCODE_HO', '$ITM_CODE', '$JOBDESC', '$ITM_GROUP',
														'$ITM_UNIT', '$ITM_VOLM', '$ITM_PRICE', '$ITM_BUDG',
														'$BOQ_VOLM', '$BOQ_PRICE', '$PRJPERIOD', '$PRJPERIOD_P', '$BOQ_BUDG', 
														'$BOQ_BOBOT', '$ISBOBOT', '$Patt_Number')";
									$this->db->query($sqlInsJL);

									$sqlInsJLD		= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, 
															PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
															ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
															PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, 
															BOQ_BOBOT, ISBOBOT, Patt_Number)
														VALUES ($ORDID, '$JOBCODEDET', '$JOBCODEID', '$JOBPARENT', 
															'$PRJCODE', '$PRJCODE_HO', '$ITM_CODE', '$JOBDESC',
															'$ITM_GROUP','$GROUP_CATEG', '$ITM_UNIT', '$ITM_VOLM', '$ITM_PRICE', '$ITM_LASTP', 
															'$PRJPERIOD', '$PRJPERIOD_P', '$ITM_BUDG', '$BOQ_VOLM', '$BOQ_PRICE', '$BOQ_BUDG',
															'$BOQ_BOBOT', '$ISBOBOT', '$Patt_Number')";
									$this->db->query($sqlInsJLD);

									// TOTAL ROW IMPORTED
								        $sIMP	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
										$rIMP	= $this->db->count_all($sIMP);

									// START : SENDING PROCESS
										$modRow		= $rowCellD % 10;
										$percent 	= intval($rIMP/$baris * 100)."%";
										/*if($modRow == 0)
										{*/
											//sleep(1); // Here call your time taking function like sending bulk sms etc.
											
											echo '<script>
									    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed</span></div>";</script>';

										    ob_flush(); 
										    flush(); 
										//}
									// END : SENDING PROCESS
								}
							}
							$percent = intval(100)."%";    
							/*echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-succes cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';*/
							echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed completed (1/3)</span></div>";</script>';

						    ob_flush(); 
						    flush();
						}
						$reader->close();
					// END : GET ALL ROW DATA AND INSERT INTO TABLE

					$percent = intval(100)."%";
					echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed (1/3)</span></div>";</script>';

				    ob_flush(); 
				    flush();

					// START : UPDATE TOTAL RAP AND BOQ
						$TotBOQ		= 0;
						$TOT_BOQ	= 0;
						$sqlJBOQV	= "SELECT SUM(BOQ_JOBCOST) AS TOT_BOQ FROM tbl_joblist_detail WHERE ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
						$resJBOQV 	= $this->db->query($sqlJBOQV)->result();
						foreach($resJCOST as $rowJCOST) :
							$TOT_BOQ	= $TOT_BOQ;
							
							// START : UPDATE RAB AND RAP IN PROJECT
								$upd_00	= "UPDATE tbl_project SET PRJBOQ = $TOT_BOQ, PRJRAP = 0 WHERE PRJCODE = '$PRJCODE'";
								$this->db->query($upd_00);
								
								$upd_01	= "UPDATE tbl_project_budg SET PRJBOQ = $TOT_BOQ, PRJRAP = 0 WHERE PRJCODE = '$PRJCODE'";
								$this->db->query($upd_01);
								
								$upd_02	= "UPDATE tbl_project_budgm SET PRJBOQ = $TOT_BOQ, PRJRAP = 0 WHERE PRJCODE = '$PRJCODE'";
								$this->db->query($upd_02);
							// END : UPDATE RAB AND RAP IN PROJECT
						endforeach;
					// END : UPDATE TOTAL RAP AND BOQ

					// START : UPDATE STATUS
						$sqlUpdBoQ	= "UPDATE tbl_boq_hist SET BOQH_STAT = 2 WHERE BOQH_CODE = '$BOQH_CODEX'";
						$this->db->query($sqlUpdBoQ);
					// END : UPDATE STATUS
				// END STEP 1 : PROSEDURE BARU

				// START STEP 2 : UPDATE ISLASTH AND LEVEL
					$percent = intval(0)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed (2/3)</span></div>";</script>';

				    ob_flush();
				    flush();

				    // TOTAL BARIS LAST HEADER
						$sqlJLD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
						$resJLD		= $this->db->count_all($sqlJLD);

					// START : PROCEDURE UPDATE ISLASTH
						$tRow 	= 0;
						$sql_01	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
						$res_01 = $this->db->query($sql_01)->result();
						foreach($res_01 as $row_01) :
							$tRow 		= $tRow+1;
							$JOBD_01	= $row_01->JOBCODEID;
							$TOTCHAR	= strlen($JOBD_01);
							echo "$JOBD_01 = $TOTCHAR<br>";
							if($TOTCHAR == 1)
								$JOBLEV = 1;
							elseif($TOTCHAR == 4)
								$JOBLEV = 2;
							elseif($TOTCHAR == 7)
								$JOBLEV = 3;
							elseif($TOTCHAR == 10)
								$JOBLEV = 4;
							elseif($TOTCHAR == 13)
								$JOBLEV = 5;
							elseif($TOTCHAR == 16)
								$JOBLEV = 6;
							elseif($TOTCHAR == 19)
								$JOBLEV = 7;
							elseif($TOTCHAR == 22)
								$JOBLEV = 8;
							elseif($TOTCHAR == 25)
								$JOBLEV = 9;
							elseif($TOTCHAR == 28)
								$JOBLEV = 10;
							else
								$JOBLEV = 0;

							$sqlCHILD	= "tbl_joblist_detail WHERE JOBPARENT = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
							$resCHILD	= $this->db->count_all($sqlCHILD);
							if($resCHILD == 0)
							{
								$sql_02A	= "UPDATE tbl_boqlist SET ISHEADER = 1, ISLASTH = 1, ISLAST = 0, JOBLEV = $JOBLEV
												WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_02A);
								$sql_02B	= "UPDATE tbl_joblist SET ISHEADER = 1, ISLASTH = 1, ISLAST = 0, JOBLEV = $JOBLEV
												WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_02B);
								$sql_02C	= "UPDATE tbl_joblist_detail SET ISLASTH = 1, ISLAST = 0, IS_LEVEL = $JOBLEV
												WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_02C);
							}
							elseif($resCHILD > 1)
							{
								$sql_02A	= "UPDATE tbl_boqlist SET ISHEADER = 1, ISLASTH = 0, ISLAST = 0, JOBLEV = $JOBLEV
												WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_02A);
								$sql_02B	= "UPDATE tbl_joblist SET ISHEADER = 1, ISLASTH = 0, ISLAST = 0, JOBLEV = $JOBLEV
												WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_02B);
								$sql_02C	= "UPDATE tbl_joblist_detail SET ISLASTH = 0, ISLAST = 0, IS_LEVEL = $JOBLEV
												WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_02C);
							}

						    // START : BAR PERCENTATION PROGRESSED
								$percent 	= intval($tRow/$resJLD * 100)."%";
								echo '<script>
										    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$tRow. ' / '.$resJLD.') processed (2/3)</span></div>";</script>';

							    ob_flush(); 
							    flush();
						    // END : BAR PERCENTATION PROGRESSED
						endforeach;
					// END : PROCEDURE UPDATE ISLASTH
					$percent = intval(100)."%";
					echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed (2/3)</span></div>";</script>';
				    ob_flush(); 
				    flush();
				// END STEP 2 : UPDATE ISLASTH AND LEVEL

				// START STEP 3 : UPDATE HEADER VALUE
					$sql3a	= "UPDATE tbl_boqlist SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sql3a);
					$sql3b	= "UPDATE tbl_joblist SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sql3b);
					$sql3c	= "UPDATE tbl_joblist_detail SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($sql3c);

					$percent = intval(0)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed (3/3)</span></div>";</script>';

				    ob_flush();
				    flush();

				    // TOTAL BARIS LAST HEADER
						$sqlJLT		= "tbl_joblist_detail WHERE ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
						$resJLT		= $this->db->count_all($sqlJLT);

					// START : UPDATE VALUE HEADER
						$tRow 	= 0;
						$sql_01	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail
									WHERE ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
						$res_01 = $this->db->query($sql_01)->result();
						foreach($res_01 as $row_01) :
							$tRow 		= $tRow+1;
							$JOBD_01	= $row_01->JOBCODEID;
							$JOBH_01	= $row_01->JOBPARENT;
							$BUDV_01	= $row_01->BUDVAL;
							$BOQV_01	= $row_01->BOQVAL;
							$TOT_RAP_01 = 0;
							$TOT_RAB_01 = 0;
							$sql_01		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_01)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_01)) AS TOT_RAB
											FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
							$res_01 	= $this->db->query($sql_01)->result();
							foreach($res_01 as $row_01) :
								$TOT_RAP_01	= $row_01->TOT_RAP;
								$TOT_RAB_01	= $row_01->TOT_RAB;

								$sql_01A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_01, BOQ_JOBCOST = $TOT_RAB_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_01A);
								$sql_01A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_01, BOQ_JOBCOST = $TOT_RAB_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_01A);
								$sql_01A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_01, BOQ_JOBCOST = $TOT_RAB_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sql_01A);
								//echo "$sql_01<br>$sql_01A<br>";

								$sqlJLH_02		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_01' AND PRJCODE = '$PRJCODE'";
								$resJLH_02		= $this->db->count_all($sqlJLH_02);
								if($resJLH_02 > 0)
								{
									$sql_02	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_01' AND PRJCODE = '$PRJCODE'";
									$res_02 = $this->db->query($sql_02)->result();
									foreach($res_02 as $row_02) :
										$JOBD_02	= $row_02->JOBCODEID;
										$JOBH_02	= $row_02->JOBPARENT;
										$BOQV_02	= $row_02->BOQVAL;
										$BUDV_02	= $row_02->BUDVAL;
										$TOT_RAP_02 = 0;
										$TOT_RAB_02 = 0;
										$sql_02		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_02)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_02)) AS TOT_RAB
														FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
										$res_02 	= $this->db->query($sql_02)->result();
										foreach($res_02 as $row_02) :
											$TOT_RAP_02	= $row_02->TOT_RAP;
											$TOT_RAB_02	= $row_02->TOT_RAB;

											$sql_02A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_02, BOQ_JOBCOST = $TOT_RAB_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
											$this->db->query($sql_02A);
											$sql_02A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_02, BOQ_JOBCOST = $TOT_RAB_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
											$this->db->query($sql_02A);
											$sql_02A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_02, BOQ_JOBCOST = $TOT_RAB_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
											$this->db->query($sql_02A);


											$sqlJLH_03		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_02' AND PRJCODE = '$PRJCODE'";
											$resJLH_03		= $this->db->count_all($sqlJLH_03);
											if($resJLH_03 > 0)
											{
												$sql_03	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_02' AND PRJCODE = '$PRJCODE'";
												$res_03 = $this->db->query($sql_03)->result();
												foreach($res_03 as $row_03) :
													$JOBD_03	= $row_03->JOBCODEID;
													$JOBH_03	= $row_03->JOBPARENT;
													$BOQV_03	= $row_03->BOQVAL;
													$BUDV_03	= $row_03->BUDVAL;
													$TOT_RAP_03 = 0;
													$TOT_RAB_03 = 0;
													$sql_03		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_03)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_03)) AS TOT_RAB
																	FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
													$res_03 	= $this->db->query($sql_03)->result();
													foreach($res_03 as $row_03) :
														$TOT_RAP_03	= $row_03->TOT_RAP;
														$TOT_RAB_03	= $row_03->TOT_RAB;

														$sql_03A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_03, BOQ_JOBCOST = $TOT_RAB_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
														$this->db->query($sql_03A);
														$sql_03A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_03, BOQ_JOBCOST = $TOT_RAB_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
														$this->db->query($sql_03A);
														$sql_03A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_03, BOQ_JOBCOST = $TOT_RAB_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
														$this->db->query($sql_03A);

														$sqlJLH_04		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_03' AND PRJCODE = '$PRJCODE'";
														$resJLH_04		= $this->db->count_all($sqlJLH_04);
														if($resJLH_04 > 0)
														{
															$sql_04	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_03' AND PRJCODE = '$PRJCODE'";
															$res_04 = $this->db->query($sql_04)->result();
															foreach($res_04 as $row_04) :
																$JOBD_04	= $row_04->JOBCODEID;
																$JOBH_04	= $row_04->JOBPARENT;
																$BOQV_04	= $row_04->BOQVAL;
																$BUDV_04	= $row_04->BUDVAL;
																$TOT_RAP_04 = 0;
																$TOT_RAB_04 = 0;
																$sql_04		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_04)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_04)) AS TOT_RAB
																				FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
																$res_04 	= $this->db->query($sql_04)->result();
																foreach($res_04 as $row_04) :
																	$TOT_RAP_04	= $row_04->TOT_RAP;
																	$TOT_RAB_04	= $row_04->TOT_RAB;

																	$sql_04A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_04, BOQ_JOBCOST = $TOT_RAB_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
																	$this->db->query($sql_04A);
																	$sql_04A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_04, BOQ_JOBCOST = $TOT_RAB_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
																	$this->db->query($sql_04A);
																	$sql_04A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_04, BOQ_JOBCOST = $TOT_RAB_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
																	$this->db->query($sql_04A);

																	$sqlJLH_05		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_04' AND PRJCODE = '$PRJCODE'";
																	$resJLH_05		= $this->db->count_all($sqlJLH_05);
																	if($resJLH_05 > 0)
																	{
																		$sql_05	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_04' AND PRJCODE = '$PRJCODE'";
																		$res_05 = $this->db->query($sql_05)->result();
																		foreach($res_05 as $row_05) :
																			$JOBD_05	= $row_05->JOBCODEID;
																			$JOBH_05	= $row_05->JOBPARENT;
																			$BOQV_05	= $row_05->BOQVAL;
																			$BUDV_05	= $row_05->BUDVAL;
																			$TOT_RAP_05 = 0;
																			$TOT_RAB_05 = 0;
																			$sql_05		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_05)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_05)) AS TOT_RAB
																							FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																			$res_05 	= $this->db->query($sql_05)->result();
																			foreach($res_05 as $row_05) :
																				$TOT_RAP_05	= $row_05->TOT_RAP;
																				$TOT_RAB_05	= $row_05->TOT_RAB;

																				$sql_05A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_05, BOQ_JOBCOST = $TOT_RAB_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																				$this->db->query($sql_05A);
																				$sql_05A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_05, BOQ_JOBCOST = $TOT_RAB_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																				$this->db->query($sql_05A);
																				$sql_05A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_05, BOQ_JOBCOST = $TOT_RAB_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																				$this->db->query($sql_05A);

																				$sqlJLH_06		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_05' AND PRJCODE = '$PRJCODE'";
																				$resJLH_06		= $this->db->count_all($sqlJLH_06);
																				if($resJLH_06 > 0)
																				{
																					$sql_06	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_05' AND PRJCODE = '$PRJCODE'";
																					$res_06 = $this->db->query($sql_06)->result();
																					foreach($res_06 as $row_06) :
																						$JOBD_06	= $row_06->JOBCODEID;
																						$JOBH_06	= $row_06->JOBPARENT;
																						$BOQV_06	= $row_06->BOQVAL;
																						$BUDV_06	= $row_06->BUDVAL;
																						$TOT_RAP_06 = 0;
																						$TOT_RAB_06 = 0;
																						$sql_06		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_06)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_06)) AS TOT_RAB
																										FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																						$res_06 	= $this->db->query($sql_06)->result();
																						foreach($res_06 as $row_06) :
																							$TOT_RAP_06	= $row_06->TOT_RAP;
																							$TOT_RAB_06	= $row_06->TOT_RAB;

																							$sql_06A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_06, BOQ_JOBCOST = $TOT_RAB_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																							$this->db->query($sql_06A);
																							$sql_06A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_06, BOQ_JOBCOST = $TOT_RAB_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																							$this->db->query($sql_06A);
																							$sql_06A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_06, BOQ_JOBCOST = $TOT_RAB_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																							$this->db->query($sql_06A);

																							$sqlJLH_07		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_06' AND PRJCODE = '$PRJCODE'";
																							$resJLH_07		= $this->db->count_all($sqlJLH_07);
																							if($resJLH_07 > 0)
																							{
																								$sql_07	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_06' AND PRJCODE = '$PRJCODE'";
																								$res_07 = $this->db->query($sql_07)->result();
																								foreach($res_07 as $row_07) :
																									$JOBD_07	= $row_07->JOBCODEID;
																									$JOBH_07	= $row_07->JOBPARENT;
																									$BOQV_07	= $row_07->BOQVAL;
																									$BUDV_07	= $row_07->BUDVAL;
																									$TOT_RAP_07 = 0;
																									$TOT_RAB_07 = 0;
																									$sql_07		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_07)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_07)) AS TOT_RAB
																													FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																									$res_07 	= $this->db->query($sql_07)->result();
																									foreach($res_07 as $row_07) :
																										$TOT_RAP_07	= $row_07->TOT_RAP;
																										$TOT_RAB_07	= $row_07->TOT_RAB;

																										$sql_07A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_07, BOQ_JOBCOST = $TOT_RAB_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																										$this->db->query($sql_07A);
																										$sql_07A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_07, BOQ_JOBCOST = $TOT_RAB_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																										$this->db->query($sql_07A);
																										$sql_07A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_07, BOQ_JOBCOST = $TOT_RAB_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																										$this->db->query($sql_07A);

																										$sqlJLH_08		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_07' AND PRJCODE = '$PRJCODE'";
																										$resJLH_08		= $this->db->count_all($sqlJLH_08);
																										if($resJLH_08 > 0)
																										{
																											$sql_08	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_07' AND PRJCODE = '$PRJCODE'";
																											$res_08 = $this->db->query($sql_08)->result();
																											foreach($res_08 as $row_08) :
																												$JOBD_08	= $row_08->JOBCODEID;
																												$JOBH_08	= $row_08->JOBPARENT;
																												$BOQV_08	= $row_08->BOQVAL;
																												$BUDV_08	= $row_08->BUDVAL;
																												$TOT_RAP_08 = 0;
																												$TOT_RAB_08 = 0;
																												$sql_08		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_08)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_08)) AS TOT_RAB
																																FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																												$res_08 	= $this->db->query($sql_08)->result();
																												foreach($res_08 as $row_08) :
																													$TOT_RAP_08	= $row_08->TOT_RAP;
																													$TOT_RAB_08	= $row_08->TOT_RAB;

																													$sql_08A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_08, BOQ_JOBCOST = $TOT_RAB_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																													$this->db->query($sql_08A);
																													$sql_08A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_08, BOQ_JOBCOST = $TOT_RAB_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																													$this->db->query($sql_08A);
																													$sql_08A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_08, BOQ_JOBCOST = $TOT_RAB_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																													$this->db->query($sql_08A);

																													$sqlJLH_09		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_08' AND PRJCODE = '$PRJCODE'";
																													$resJLH_09		= $this->db->count_all($sqlJLH_09);
																													if($resJLH_09 > 0)
																													{
																														$sql_09	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_08' AND PRJCODE = '$PRJCODE'";
																														$res_09 = $this->db->query($sql_09)->result();
																														foreach($res_09 as $row_09) :
																															$JOBD_09	= $row_09->JOBCODEID;
																															$JOBH_09	= $row_09->JOBPARENT;
																															$BOQV_09	= $row_09->BOQVAL;
																															$BUDV_09	= $row_09->BUDVAL;
																															$TOT_RAP_09 = 0;
																															$TOT_RAB_09 = 0;
																															$sql_09		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_09)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_09)) AS TOT_RAB
																																			FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																															$res_09 	= $this->db->query($sql_09)->result();
																															foreach($res_09 as $row_09) :
																																$TOT_RAP_09	= $row_09->TOT_RAP;
																																$TOT_RAB_09	= $row_09->TOT_RAB;

																																$sql_09A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_09, BOQ_JOBCOST = $TOT_RAB_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																																$this->db->query($sql_09A);
																																$sql_09A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_09, BOQ_JOBCOST = $TOT_RAB_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																																$this->db->query($sql_09A);
																																$sql_09A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_09, BOQ_JOBCOST = $TOT_RAB_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																																$this->db->query($sql_09A);

																																$sqlJLH_10		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_09' AND PRJCODE = '$PRJCODE'";
																																$resJLH_10		= $this->db->count_all($sqlJLH_10);
																																if($resJLH_10 > 0)
																																{
																																	$sql_10	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_09' AND PRJCODE = '$PRJCODE'";
																																	$res_10 = $this->db->query($sql_10)->result();
																																	foreach($res_10 as $row_10) :
																																		$JOBD_10	= $row_10->JOBCODEID;
																																		$JOBH_10	= $row_10->JOBPARENT;
																																		$BOQV_10	= $row_10->BOQVAL;
																																		$BUDV_10	= $row_10->BUDVAL;
																																		$TOT_RAP_10 = 0;
																																		$TOT_RAB_10 = 0;
																																		$sql_10		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_10)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_10)) AS TOT_RAB
																																						FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																		$res_10 	= $this->db->query($sql_10)->result();
																																		foreach($res_10 as $row_10) :
																																			$TOT_RAP_10	= $row_10->TOT_RAP;
																																			$TOT_RAB_10	= $row_10->TOT_RAB;

																																			$sql_10A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_10, BOQ_JOBCOST = $TOT_RAB_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																			$this->db->query($sql_10A);
																																			$sql_10A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_10, BOQ_JOBCOST = $TOT_RAB_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																			$this->db->query($sql_10A);
																																			$sql_10A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_10, BOQ_JOBCOST = $TOT_RAB_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																			$this->db->query($sql_10A);
																																		endforeach;
																																	endforeach;
																																}
																															endforeach;
																														endforeach;
																													}
																												endforeach;
																											endforeach;
																										}
																									endforeach;
																								endforeach;
																							}
																						endforeach;
																					endforeach;
																				}
																			endforeach;
																		endforeach;
																	}
																endforeach;
															endforeach;
														}
													endforeach;
												endforeach;
											}
										endforeach;
									endforeach;
								}
							endforeach;

						    // START : BAR PERCENTATION PROGRESSED
								$percent 	= intval($tRow/$resJLT * 100)."%";
								echo '<script>
										    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$tRow. ' / '.$resJLT.') processed (3/3)</span></div>";</script>';

											    ob_flush(); 
											    flush();
						    // END : BAR PERCENTATION PROGRESSED
						endforeach;

				// END STEP 3 : UPDATE VALUE HEADER

				// IMPORT COMPLETE INFO
					$percent = intval(100)."%";
					echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
					echo '<script>
					    parent.document.getElementById("loading_1").style.display ="none";
					    parent.document.getElementById("loading_2").style.display ="none";
					    parent.updStat();</script>';
			}
			else
			{
				//echo "Sudah diimport sebelumnya";
				//return false;
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
	else if($IMP_TYPE == 'ITM')
	{
		$ITMH_CODEY		= $_POST['IMP_CODEX'];
		$ITMH_CODEX		= $_POST['IMP_CODEX'];
		$ITMH_CODE		= $_POST['IMP_CODEX'];
			
		$sqlCountITM	= "tbl_item_uphist WHERE ITMH_STAT = 2 AND ITMH_CODE = '$ITMH_CODEY'";
		$resCountITM	= $this->db->count_all($sqlCountITM);

		$sqldelITM		= "DELETE FROM tbl_item WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($sqldelITM);
		
		$sqlUpdITM1		= "UPDATE tbl_item_uphist SET ITMH_STAT = 3 WHERE ITMH_STAT = 2 AND ITMH_PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUpdITM1);

		$ITMH_FN	= '';
		$sqlBoQ		= "SELECT ITMH_FN FROM tbl_item_uphist WHERE ITMH_CODE = '$ITMH_CODEX'";
		$reslBoQ	= $this->db->query($sqlBoQ)->result();
		foreach($reslBoQ as $rowBoQ) :
			$ITMH_FN	= $rowBoQ->ITMH_FN;
		endforeach;

		$sqlJLC		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
		$resJLC		= $this->db->count_all($sqlJLC);
		
		$PRJCODE_HO 	= "";
		$sql 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJCODE_HO = $row ->PRJCODE_HO;
		endforeach;
		
		$myXlsFile	= "$ITMH_FN";

		// ADA PERUBAHAN PROSEDURE PEMBACAAN FILE EXCEL
			// PROSEDUR LAMA
				/*$myPath 	= "application/xlsxfile/import_item/period/$myXlsFile";
				$file 		= file(base_url() . "$myPath"); # read file into array
				$count 		= count($file);
				$data 		= new Spreadsheet_Excel_Reader($myPath);

				$totCol		= 0;

				$colName[]	= array();
				for ($i=1; $i <= 100; $i++)
				{
					$colNm			= $data->val(1, $i);
					$colName[$i]	= $data->val(1, $i);
					if($colNm == '')
						break;
					else
						$totCol		= $totCol+1;
				}*/

			// START : PROSEDURE BARU
				$myPath 	= APPPATH."xlsxfile/import_item/period/$myXlsFile";
				$reader 	= ReaderEntityFactory::createXLSXReader($myPath);

				$reader->open($myPath);
				$rowCellH	= 0;
				$totCol 	= 0;
				$isError 	= 0;

				// CREATE ARRAY HEADER
					foreach ($reader->getSheetIterator() as $sheet)
					{
						foreach ($sheet->getRowIterator() as $row)
						{
							$rowCellH 	= $rowCellH+1;
							if($rowCellH == 1)
							{
								$arrHn[]	= array();
								for($cl=0;$cl<=100;$cl++)
								{
						        	$cells 		= $row->getCells();
						        	try 
						        	{
									  	$HeadCells 	= $cells[$cl];
									  	if(isset($cells[$cl]))
									  	{
									  		$HeadNm 		= $HeadCells->getValue() ?: '';
									  		$totCol			= $totCol+1;
									  		$arrHn[$cl]		= $HeadNm;
									  	}
									}
									catch (exceuta $e)
									{
									   	//
									}
								}
							}
							$percent 	= intval(100)."%";
							$collected 	= intval($rowCellH);
							echo '<script>
								  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

						    ob_flush(); 
						    flush();
						}
					}

				// SART : GET ALL ROW DATA AND INSERT INTO TABLE
			        $baris 		= $rowCellH-1;				// DIKURANGI HEADER
					$rata2 		= $baris / 100;				// TOTAL RATA2 PER / 100 --> TOTAL LOOP 100
					$rata2a		= $rata2 * 1;				// TOTAL VALUE PER LOOP
					$rata2b		= intval($rata2 * 1);		// TOTAL LOOP = 100
					$totLoop 	= intval($baris / $rata2a);	// TOTAL LOOP = 100
					$rowCellD	= 0;
					$ORD_ID		= 0;

					foreach ($reader->getSheetIterator() as $sheet)
					{
						$rowCellD	= 0;
						foreach ($sheet->getRowIterator() as $row)
						{
							$rowCellD 	= $rowCellD+1;
							if($rowCellD > 1)
							{
								for($rw=0;$rw<$totCol;$rw++)
								{
							  		$cells 		= $row->getCells();
					        		$hdName 	= $arrHn[$rw];
								  	$HeadCells 	= $cells[$rw];
								  	$RowData 	= '';
								  	if(null !== $HeadCells)
							  			$RowData 	= $HeadCells->getValue(); 

									if($hdName == 'PRJPERIOD')
									{
										$PRJPERIOD 		= $RowData;
										if($PRJPERIOD == '')
											$PRJPERIOD 	= $PRJCODE;
									}
									elseif($hdName == 'ITM_CODE')
										$ITM_CODE 	= $RowData;
									elseif($hdName == 'ITM_GROUP')
										$ITM_GROUP 		= $RowData;
									elseif($hdName == 'ITM_CATEG')
										$ITM_CATEG 		= $RowData;
									elseif($hdName == 'ITM_NAME')
										$ITM_NAME 		= addslashes($RowData);
									elseif($hdName == 'ITM_DESC')
										$ITM_DESC 		= addslashes($RowData);
									elseif($hdName == 'ITM_TYPE')
									{
										$ITM_TYPE 		= addslashes($RowData);
										if($ITM_TYPE == '')
											$ITM_TYPE 	= 'PRM';
									}
									elseif($hdName == 'ITM_UNIT')
									{
										$ITM_UNIT 		= addslashes($RowData);
									}
									elseif($hdName == 'ITM_VOLMBG')
									{
										$ITM_VOLMBG1 	= preg_replace('/[^A-Za-z0-8\.]/', '', $RowData);
										$ITM_VOLMBG 	= sprintf("%f", $ITM_VOLMBG1);
										if($ITM_VOLMBG == '')
											$ITM_VOLMBG	= 0;
									}
									elseif($hdName == 'ITM_VOLMBGR')
									{
										$ITM_VOLMBGR1 	= preg_replace('/[^A-Za-z0-8\.]/', '', $RowData);
										$ITM_VOLMBGR	= sprintf("%f", $ITM_VOLMBGR1);
										if($ITM_VOLMBGR == '')
											$ITM_VOLMBGR= 0;
									}
									elseif($hdName == 'ITM_VOLM')
									{
										$ITM_VOLM1 		= preg_replace('/[^A-Za-z0-8\.]/', '', $RowData);
										$ITM_VOLM		= sprintf("%f", $ITM_VOLM1);
										if($ITM_VOLM == '')
											$ITM_VOLM 	= 0;
									}
									elseif($hdName == 'ITM_PRICE')
									{
										$ITM_PRICE1 	= preg_replace('/[^A-Za-z0-8\.]/', '', $RowData);
										$ITM_PRICE 		= sprintf("%f", $ITM_PRICE1);
										if($ITM_PRICE == '')
											$ITM_PRICE 	= 0;
									}
									elseif($hdName == 'ITM_LASTP')
									{
										$ITM_LASTP1 	= preg_replace('/[^A-Za-z0-8\.]/', '', $RowData);
										$ITM_LASTP 		= sprintf("%f", $ITM_LASTP1);
										if($ITM_LASTP == '')
											$ITM_LASTP 	= 0;
									}
									elseif($hdName == 'ACC_ID')
										$ACC_ID 	= $RowData;
									elseif($hdName == 'ACC_ID_UM')
										$ACC_ID_UM 	= $RowData;
									elseif($hdName == 'ACC_ID_SAL')
										$ACC_ID_SAL = $RowData;
									elseif($hdName == 'STATUS')
										$STATUS 	= $RowData;
									elseif($hdName == 'ISMTRL')
										$ISMTRL 	= $RowData;
									elseif($hdName == 'ISRM')
										$ISRM 		= $RowData;
									elseif($hdName == 'ISWIP')
										$ISWIP 		= $RowData;
									elseif($hdName == 'ISFG')
										$ISFG 		= $RowData;
									elseif($hdName == 'ISRIB')
										$ISRIB 		= $RowData;
									elseif($hdName == 'ISCOST')
										$ISCOST 	= $RowData;
									elseif($hdName == 'NEEDQRC')
										$NEEDQRC 	= $RowData;
									elseif($hdName == 'ITM_LR')
										$ITM_LR 	= $RowData;
								}
								
								$ITM_CURRENCY 	= 'IDR';
								$UMCODE			= $ITM_UNIT;
								$ITM_REMQTY		= $ITM_VOLM;

								//$ITM_LASTP	= $ITM_PRICE;
								$ITM_AVGP		= $ITM_PRICE;

								$ITM_VOLMBGR 	= $ITM_VOLMBGR;
								if($resJLC > 0)
								{
									$sqlBudg	= "SELECT sum(B.ITM_VOLM) AS TOTBUDG FROM tbl_joblist_detail B
													WHERE B.ITM_CODE = '$ITM_CODE' AND B.PRJCODE = '$PRJCODE'";
									$resBudg 	= $this->db->query($sqlBudg)->result();
									foreach($resBudg as $rowBudg) :
										$TOTBUDG = $rowBudg->TOTBUDG;
									endforeach;
									$ITM_VOLMBG = $TOTBUDG ?: 0;

									$ITM_VOLMBGR= $ITM_VOLMBG;
								}
								
								if($ITM_UNIT == 'LUMP')
								{
									$BOQ_ITM_VOLM 	= $BOQ_ITM_TOTALP;
									$BOQ_ITM_PRICE 	= 1;

									$ITM_VOLM 		= $ITM_TOTALP;
									$ITM_PRICE 		= 1;
									$ITM_LASTP 		= 1;

								}

								$ITM_TOTALP		= $ITM_VOLMBG * $ITM_PRICE;
								if($ITM_TOTALP == '')
									$ITM_TOTALP	= 0;
								
								$BOQ_ITM_VOLM	= $ITM_VOLMBG;
								$BOQ_ITM_PRICE	= $ITM_PRICE;
								$BOQ_ITM_TOTALP	= $ITM_TOTALP;

								// INSERT INTO ITM
									$sqlInsITM	= "INSERT INTO tbl_item (PRJCODE, PRJCODE_HO, PRJPERIOD, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, 
														ITM_DESC, ITM_TYPE, ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLMBG, ITM_VOLMBGR, 
														ITM_VOLM,  ITM_PRICE, ITM_REMQTY, ITM_TOTALP, ITM_LASTP, ITM_AVGP, BOQ_ITM_VOLM, 
														BOQ_ITM_PRICE, BOQ_ITM_TOTALP, ACC_ID, ACC_ID_UM, ACC_ID_SAL, STATUS, 
														ISMTRL, ISRM, ISWIP, ISFG, ISRIB, NEEDQRC, LASTNO, ITM_LR)
													VALUES ('$PRJCODE', '$PRJCODE_HO', '$PRJPERIOD', '$ITM_CODE', '$ITM_GROUP', '$ITM_CATEG', '$ITM_NAME', 
														'$ITM_DESC', '$ITM_TYPE', '$ITM_UNIT', '$UMCODE', '$ITM_CURRENCY', '$ITM_VOLMBG', '$ITM_VOLMBGR', 
														'$ITM_VOLM', '$ITM_PRICE', '$ITM_REMQTY', '$ITM_TOTALP', '$ITM_LASTP', '$ITM_AVGP', '$BOQ_ITM_VOLM', 
														'$BOQ_ITM_PRICE', '$BOQ_ITM_TOTALP', '$ACC_ID', '$ACC_ID_UM', '$ACC_ID_SAL', '$STATUS', 
														'$ISMTRL', '$ISRM', '$ISWIP', '$ISFG', '$ISRIB', '$NEEDQRC', '$rowCellD', '$ITM_LR')";
									$this->db->query($sqlInsITM);

								// TOTAL ROW IMPORTED
							        $sIMP	= "tbl_item WHERE PRJCODE = '$PRJCODE'";
									$rIMP	= $this->db->count_all($sIMP);

								// START : SENDING PROCESS
									$modRow		= $rowCellD % 10;
									$percent 	= intval($rIMP/$baris * 100)."%";
									/*if($modRow == 0)
									{*/
										//sleep(1); // Here call your time taking function like sending bulk sms etc.
										
										echo '<script>
								    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed</span></div>";</script>';

									    ob_flush(); 
									    flush(); 
									/*}*/
								// END : SENDING PROCESS
							}
						}
					}
				// END : GET ALL ROW DATA AND INSERT INTO TABLE
			// END : PROSEDURE BARU

			// IMPORT CONCLUSION
				// UPDATE STATUS
					$sqlUpdITM		= "UPDATE tbl_item_uphist SET ITMH_STAT = 2 WHERE ITMH_CODE = '$ITMH_CODEX'";
					$this->db->query($sqlUpdITM);

				// CEK ITEM UNIT
					$sqlUNIT	= "SELECT DISTINCT A.ITM_UNIT 
									FROM tbl_item A
									WHERE NOT EXISTS (SELECT 1 FROM tbl_unittype B WHERE B.Unit_Type_Code = A.ITM_UNIT)
									AND A.PRJCODE = '$PRJCODE'";
					$resUNIT	= $this->db->query($sqlUNIT)->result();
					foreach($resUNIT as $rowUNIT) :
						$ITM_UNIT	= $rowUNIT->ITM_UNIT;
						if($ITM_UNIT != '')
						{
							$sqlInsUNIT	= "INSERT INTO tbl_unittype (Unit_Type_Code, UMCODE, Unit_Type_Name)
												VALUES ('$ITM_UNIT', '$ITM_UNIT', '$ITM_UNIT')";
							$this->db->query($sqlInsUNIT);
						}
					endforeach;

			// IMPORT COMPLETE INFO
				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.document.getElementById("loading_2").style.display ="none";
				    parent.updStat();</script>';
	}
	else if($IMP_TYPE == 'RESET')
	{
		$PRJSCATEG	= $this->session->userdata['PRJSCATEG'];

		date_default_timezone_set("Asia/Jakarta");
		$DNOW		= date('Y-m-d H:i:s');
		$EMPID		= $this->session->userdata['Emp_ID'];
		$RHIST_CODE	= date('YmhHis');
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		//$PrjCode 	= $colExpl[0];
        $RESFULL 	= $colExpl[1];

        $host_name 	= gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $ipaddress	= '';
        if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	        $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'IP Tidak Dikenali';

	    $browser = '';
	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape'))
	        $browser = 'Netscape';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
	        $browser = 'Firefox';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
	        $browser = 'Chrome';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
	        $browser = 'Opera';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
	        $browser = 'Internet Explorer';
	    else
	        $browser = 'Other';

	    $insResHist	= "INSERT INTO tbl_rhist (RHIST_CODE, RHIST_EMPID, RHIST_IPADD, RHIST_HOTSN, RHIST_BROWS, RHIST_DATET)
							VALUES ('$RHIST_CODE', '$EMPID', '$ipaddress', '$host_name', '$browser', '$DNOW')";
		$this->db->query($insResHist);

		//sleep(1); // Here call your time taking function like sending bulk sms etc.
		$percent = intval(1)."%";
		echo '<script>
	    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

	    ob_flush();
	    flush();
	    
	    $RESFULL = $COAH_CODEY;
		//$PRJCODE 	= $PrjCode;
	    if($PRJSCATEG == 1)		// KONTRAKTOR
	    {
			if($RESFULL == 0)		// Hanya Data Transaksi
			{
	            $sql003	= "DELETE FROM tbl_alert_list WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);	

	        	$s_001	= "DELETE FROM tbl_doc_concl WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($s_001);

	        	$s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AMD_NUM 
	                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_amd_detail WHERE AMD_NUM IN (SELECT AMD_NUM 
	                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_apartement			-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASEXP_NUM 
	                            FROM tbl_asset_exph WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_exph WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_expd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(2)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	        	// tbl_asset_group 			-- No Reset

	            $sql003	= "DELETE FROM tbl_asset_joblist WHERE JL_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	        	// tbl_asset_list 			-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AM_CODE 
	                            FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_maintendet WHERE AM_CODE IN (SELECT AM_CODE 
	                            FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_asset_prod WHERE AP_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_asset_rcost WHERE RASTC_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_asset_rjob WHERE RASTC_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(5)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASTSF_NUM 
	                            FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE')";
	           //__ $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_tsfd WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            // tbl_asset_type			-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AU_CODE 
	                            FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_usagedet WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(8)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AUR_CODE 
	                            FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_assetexp_concl WHERE RASTXP_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RASSET_CODE 
	                            FROM tbl_assetexp_header WHERE RASSET_PROJECT = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_assetexp_detail WHERE RASSETD_PROJECT = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_assetexp_header WHERE RASSET_PROJECT = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(8)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_auth 				-- Not Used
	            // tbl_balances				-- Not Used
	            // tbl_bgheader				-- Not Used

	            $sql003	= "DELETE FROM tbl_bobot WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT BOM_NUM 
	                            FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_bom_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	            $sql004	= "DELETE FROM tbl_bom_stfdetail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql004);
	            $sql004	= "DELETE FROM tbl_bom_stfdetail_qrc WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql004);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(8)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql003	= "DELETE FROM tbl_boq_hist WHERE BOQH_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlist_temp WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlistm WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
	                            FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_bp_detail WHERE JournalH_Code IN (SELECT JournalH_Code 
	                            FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(11)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
	                            FROM tbl_br_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_br_detail WHERE JournalH_Code IN 
	                        (SELECT JournalH_Code FROM tbl_br_header 
	                        WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_br_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_cashbank				-- No Reset
	            // tbl_cb_detail			-- No Reset / Not Used
	            // tbl_cb_header			-- No Reset / Not Used

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(13)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT CCAL_NUM 
	                            FROM tbl_ccal_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_ccal_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ccal_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_ccoa WHERE CCOA_PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(14)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_cf_report_in, tbl_cf_report_out		-- Not Used

	            // RE-COUNT CHART ACCOUNT FROM JURNAL FOR ALL RELATION CHART ACCOUNT
	                /*$sqlJOURND	= "SELECT A.Acc_Id, A.proj_Code, A.Base_Debet, 
	                                    A.Base_Debet_tax, A.Base_Kredit, A.Base_Kredit_tax
	                                FROM tbl_journaldetail A INNER JOIN tbl_journalheader B 
	                                    ON A.JournalH_Code = B.JournalH_Code
	                                WHERE B.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3";
	                $resJOURND	= $this->db->query($sqlJOURND)->result();
	                foreach($resJOURND as $rowJD) :
	                    $Acc_Id				= $rowJD->Acc_Id;
	                    $proj_Code			= $rowJD->proj_Code;
	                    $Base_Debet			= $rowJD->Base_Debet;
	                    $Base_Debet_tax		= $rowJD->Base_Debet_tax;
	                    $Base_Kredit		= $rowJD->Base_Kredit;
	                    $Base_Kredit_tax	= $rowJD->Base_Kredit_tax;
	                    
	                    // LOOP PROJECT
	                    $syncPRJ	= '';
	                    $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount
	                                    WHERE PRJCODE = '$proj_Code'
	                                        AND Account_Number = '$Acc_Id' LIMIT 1";
	                    $resISHO	= $this->db->query($sqlISHO)->result();
	                    foreach($resISHO as $rowISHO):
	                        $syncPRJ= $rowISHO->syncPRJ;
	                    endforeach;
	                    $dataPecah 	= explode("~",$syncPRJ);
	                    $jmD 		= count($dataPecah);
	                
	                    if($jmD > 0)
	                    {
	                        $SYNC_PRJ	= '';
	                        for($i=0; $i < $jmD; $i++)
	                        {
	                            $SYNC_PRJ	= $dataPecah[$i];
	                            $sqlCOA		= "UPDATE tbl_chartaccount SET 
			                                        Base_Debet = Base_Debet - $Base_Debet, 
			                                        Base_Debet_tax = Base_Debet_tax - $Base_Debet_tax, 
			                                        Base_Debet2 = Base_Debet2 - $Base_Debet,
			                                        Base_Debet_tax2 = Base_Debet_tax2 - $Base_Debet_tax,
			                                        Base_Kredit = Base_Kredit - $Base_Kredit,
			                                        Base_Kredit_tax = Base_Kredit_tax - $Base_Kredit_tax, 
			                                        Base_Kredit2 = Base_Kredit2 - $Base_Kredit, 
			                                        Base_Kredit_tax2 = Base_Kredit_tax2 - $Base_Kredit_tax 
		                                        WHERE Account_Number = '$Acc_Id' 
		                                        AND PRJCODE = '$SYNC_PRJ'";
	                            $this->db->query($sqlCOA);
	                        }
	                    }
	                endforeach;*/
	                
	            // CLEAR CHART ACCOUNT
	                /*$sqlCOA	= "UPDATE tbl_chartaccount SET 
	                                Base_Debet = 0, 
	                                Base_Debet_tax = 0, 
	                                Base_Debet2 = 0,
	                                Base_Debet_tax2 = 0,
	                                Base_Kredit = 0,
	                                Base_Kredit_tax = 0, 
	                                Base_Kredit2 = 0, 
	                                Base_Kredit_tax2 = 0
	                            WHERE PRJCODE = '$PRJCODE'";
	                $this->db->query($sqlCOA);*/

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(15)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            //, tbl_chartaccountm, 			-- Not Used
	            // tbl_chartcategory,			-- No Reset
	            // tbl_chat, tbl_chat_detail, 	-- Not Used

	            $sql003	= "DELETE FROM tbl_coa_uphist WHERE COAH_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_coadetail, tbl_cssjs, tbl_currate, tbl_currconv, tbl_currency, 	-- No Reset
	            // tbl_custcat, tbl_customer, tbl_customer_img							-- No Reset

	            $sql003	= "DELETE FROM tbl_dash_data WHERE PRJ_CODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_dash_hr, tbl_dash_sett, tbl_dash_sett_emp, tbl_dash_sett_hr, tbl_dash_sett_hr_emp,	-- No Reset

	            $sql003	= "DELETE FROM tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_doc_concl WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_dash_transac_all, tbl_decreaseinvoice, tbl_department, tbl_doc_cc, tbl_docpattern,	-- No Reset
	            // tbl_docstepapp, tbl_docstepapp_det, tbl_document, 										-- No Reset

	            $sql003	= "DELETE FROM tbl_dp_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(16)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_dp_report, tbl_dp_report_in, tbl_dp_report_out, tbl_dpr_header, tbl_driver,			-- No Reset
	            // tbl_dwlhist, tbl_emp_vers, tbl_employee, tbl_employee_acc, tbl_employee_age, 			-- No Reset
	            // tbl_employee_appauth, tbl_employee_circle, tbl_employee_docauth, tbl_employee_gol,		-- No Reset
	            // tbl_employee_img, tbl_employee_proj, 													-- No Reset

	            $sql003	= "DELETE FROM tbl_financial_monitor WHERE FM_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_financial_track WHERE FT_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FPA_NUM 
	                        FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_fpa_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(18)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FU_CODE 
	                            FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            // tbl_genfileupload, tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,					-- No Reset
	            // tbl_inv_detail, tbl_inv_header,  														-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT IR_NUM 
	                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(20)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	            
	            $sql002	= "DELETE FROM tbl_ir_detail WHERE IR_NUM IN (SELECT IR_NUM 
	                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql002	= "DELETE FROM tbl_ir_detail_tmp WHERE IR_NUM IN (SELECT IR_NUM 
	                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ir_detail_trash WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // RESET ITEM
	                $sql003	= "UPDATE tbl_item SET 
	                                ITM_VOLMBGR = ITM_VOLMBG, ITM_VOLM = 0,
	                                ITM_REMQTY = 0, ITM_TOTALP = 0, ITM_LASTP = ITM_PRICE,
	                                ITM_IN = 0, ITM_INP = 0, ITM_OUT = 0, ITM_OUTP = 0, 
	                                PR_VOLM = 0, PR_AMOUNT = 0, MR_VOLM = 0, MR_AMOUNT = 0,
	                                PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0, 
	                                UM_VOLM = 0, UM_AMOUNT = 0, SO_VOLM = 0, SO_AMOUNT = 0,
	                                JO_VOLM = 0, JO_AMOUNT = 0, PROD_VOLM = 0, PROD_AMOUNT = 0, 
	                                RET_VOLM = 0, RET_AMOUNT = 0, ADDVOLM = 0, ADDCOST = 0, 
	                                ADDMVOLM = 0, ADDMCOST = 0, ITM_VOLMBON = 0
	                            WHERE PRJCODE = '$PRJCODE'";
	                $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(25)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ADJ_NUM 
	                            FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            /*$sql002	= "DELETE FROM tbl_item_adjd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);*/
	            $sql003	= "DELETE FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_colld WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_collh WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(28)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	            
	            $sql003	= "DELETE FROM tbl_item_cutd WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_cuth WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	        
	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ITMTSF_NUM 
	                            FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_item_tsfd WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_item_uphist WHERE ITMH_PRJCODE = '$PRJCODE'";
	            $this->db->query($s_001);

	            $s_001	= "DELETE FROM tbl_item_whqty WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($s_001);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(30)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_itemcategory, tbl_itemgroup				-- No Reset

	            $sql003	= "DELETE FROM tbl_itemhistory WHERE proj_Code = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOC_CODE 
	                            FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	        
	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JO_NUM 
	                            FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_jo_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_jo_detail_tmp3 WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(32)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
	                            FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            
	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
	                            FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            
	            $sql002	= "DELETE FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(33)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	            
	            // RESET JOBLIST
	                $sql004	= "UPDATE tbl_joblist SET BOQ_PROGR = 0, 
	                                ADD_VOLM = 0, ADD_PRICE = 0, 
	                                ADD_JOBCOST = 0, ADDM_VOLM = 0, ADDM_JOBCOST = 0 
	                            WHERE PRJCODE = '$PRJCODE'";
	                $this->db->query($sql004);
	                
	            // RESET JOBLIST DETAIL
	                $sql005	= "UPDATE tbl_joblist_detail SET ITM_LASTP = ITM_PRICE,
	                                BOQ_AMDVOLM = 0, BOQ_AMDPRICE = 0, BOQ_AMDTOTAL = 0, 
	                                ADD_VOLM = 0, ADD_PRICE = 0, ADD_JOBCOST = 0, 
	                                ADDM_VOLM = 0, ADDM_JOBCOST = 0, REQ_VOLM = 0, REQ_AMOUNT = 0, 
	                                PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0, 
	                                WO_QTY = 0, WO_AMOUNT = 0, OPN_QTY = 0, OPN_AMOUNT = 0, 
	                                ITM_USED = 0, ITM_USED_AM = 0, ITM_RET = 0, ITM_RET_AM = 0, 
	                                ITM_STOCK = 0, ITM_STOCK_AM = 0
	                            WHERE PRJCODE = '$PRJCODE'";
	                $this->db->query($sql005);

	            // tbl_joblist_detailm, tbl_joblistm, tbl_jobopname,	-- Not Used 
	            
	            // CLEAR JOURNAL
	                $s_001	= "DELETE FROM tbl_journalheader WHERE proj_Code = '$PRJCODE'";
	                $this->db->query($s_001);
	                
	                $sql002	= "DELETE FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE'";
	                $this->db->query($sql002);

	            // tbl_language, tbl_lastsync, tbl_link_account, tbl_login_concl, tbl_login_hist,		-- No Reset
	            // tbl_machine, tbl_machine_itm, tbl_mail_dept, tbl_mail_dept_emp, tbl_mail_detail, 	-- No Reset
	            // tbl_mail_header, tbl_mail_type, tbl_mailbox, tbl_mailbox_reply, tbl_mailbox_send,	-- No Reset
	            // tbl_mailbox_trash, tbl_mailbox_trash_ext, tbl_mailgroup_detail, tbl_mailgroup_header,-- No Reset
	            // tbl_major_app, tbl_master_item, tbl_mc_balance										-- No Reset


	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(35)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql003	= "DELETE FROM tbl_mc_conc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_mc_plan WHERE MCP_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MCH_CODE 
	                            FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_mcg_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	        
	            $sql003	= "DELETE FROM tbl_mcheader WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(38)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_meeting_room						-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MR_NUM 
	                            FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_mr_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_news_detail, tbl_news_header		-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OFF_NUM 
	                            FROM tbl_offering_h WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_offering_d WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_offering_h WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(40)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OPNH_NUM 
	                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_opn_detail WHERE OPNH_NUM IN (SELECT OPNH_NUM 
	                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_opn_inv, tbl_opn_invdet, 		-- Not Used
	            // tbl_outpay_report, tbl_overhead, 	-- Not Used
	            // tbl_owner, tbl_owner_img,			-- No Reset
	            // tbl_payterm							-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT INV_NUM 
	                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_pinv_detail WHERE INV_NUM IN (SELECT INV_NUM 
	                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PO_NUM 
	                            FROM tbl_po_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_po_detail WHERE PO_NUM IN (SELECT PO_NUM 
	                            FROM tbl_po_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_po_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(45)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_position, tbl_position_func, tbl_position_str, 						-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PR_NUM 
	                            FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_pr_detail WHERE PR_NUM IN (SELECT PR_NUM 
	                            FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_pr_detail_trash WHERE PR_NUM IN (SELECT PR_NUM 
	                            FROM tbl_pr_header_trash WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_pr_header_trash WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_pr_detail_canc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_printdoc, tbl_printdoc_wo,											-- Not Used
	            // tbl_prodstep, 															-- No Reset

	            $sql003	= "DELETE FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_profloss_man,														-- Not Used
	            // tbl_progg_uphist,														-- Not Used
	            // tbl_project, tbl_project_active, tbl_project_budg, tbl_project_budgm, 	-- No Reset

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(48)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql002	= "DELETE FROM tbl_project_concl WHERE PROGG_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PRJP_NUM 
	                            FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_project_progress_det WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_project_recom, tbl_project_recom_hist,								-- Not Used
	            // tbl_projhistory, tbl_projinv_detail										-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PINV_CODE 
	                            FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql003	= "DELETE FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_projinv_realh, tbl_projplan_material									-- Not Used

	            $sql002	= "UPDATE tbl_projprogres SET Prg_Real = 0, Prg_RealAkum = 0, 
	                            Prg_Dev = 0, isShowRA = 0, isShowDev = 0,
	                            isShowRA = 0, isShowDev = 0, lastStepPS = 0
	                        WHERE proj_Code = '$PRJCODE'";
	            $this->db->query($sql002);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(50)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_purch_report, tbl_qhsedoc_header, 									-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT QRC_NUM 
	                            FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_qty_coll WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            // tbl_reservation,															-- Not Used
	        
	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RET_NUM 
	                            FROM tbl_ret_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_ret_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ret_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(51)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_riskcategory, tbl_riskdescdet, tbl_riskidentif, tbl_riskimpactdet, tbl_riskpolicydet,	-- Not Used
	            // tbl_rtflista, tbl_rtflistb, tbl_schedule, tbl_section, tbl_sementara,	-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SIC_CODE 
	                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_sicertificatedet WHERE SIC_CODE IN (SELECT SIC_CODE 
	                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_sinv_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SINV_NUM 
	                            FROM tbl_sinv_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_sinv_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sinv_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(53)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql003	= "DELETE FROM tbl_sn_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SN_NUM 
	                            FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_sn_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SO_NUM 
	                            FROM tbl_so_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_so_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_so_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_so_concl WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(55)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_sopn_concl, tbl_sopn_detail, tbl_sopn_header, tbl_spkprint,			-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SR_NUM 
	                            FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_sr_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_sr_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT STF_NUM 
	                            FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_stf_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            // tbl_stf_mtrused,															-- Not Used
	            // tbl_supplier, tbl_task_request, tbl_task_request_detail,					-- No Reset
	            // tbl_tax, tbl_tax_la, tbl_tax_ppn, tbl_tax_ppn_copy, tbl_trackcreater,	-- No Reset 

	            $sql002	= "DELETE FROM tbl_trail_tracker WHERE TTR_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

				$percent = intval(58)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_translate, tbl_trashsys,

	            $sql003	= "DELETE FROM tbl_ttk WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT TTK_NUM 
	                            FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_ttk_detail WHERE TTK_NUM IN (SELECT TTK_NUM 
	                            FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_ttk_print WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);


	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(58)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_ttkestinvoice														-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT UM_NUM 
	                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_um_detail WHERE UM_NUM IN (SELECT UM_NUM 
	                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_um_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_unittype,															-- No Reset
	            // tbl_uploadbca, tbl_uploadbca_data, tbl_uploadreceipt, tbl_uploadttkest,	-- Not Used
	            // tbl_userdoctype,															-- Not Used
	            // tbl_vehicle, tbl_vendcat, tbl_warehouse, tbl_wip, 						-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
	                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_wo_detail WHERE WO_NUM IN (SELECT WO_NUM 
	                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_wo_print WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
	                            FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_woreq_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	        	// tglobalsetting, tusermenu


	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
	            $perVal	 = 60;
				$percent = intval(60)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	                
	            // OTHERS
	                $sqloth01	= "TRUNCATE tbl_login_hist";
	                // $this->db->query($sqloth01);
	                
	            // CLEAR CHART ACCOUNT
	                /*$sqlCOA	= "UPDATE tbl_chartaccount SET 
	                                Base_Debet = 0, 
	                                Base_Debet_tax = 0, 
	                                Base_Debet2 = 0,
	                                Base_Debet_tax2 = 0,
	                                Base_Kredit = 0,
	                                Base_Kredit_tax = 0, 
	                                Base_Kredit2 = 0, 
	                                Base_Kredit_tax2 = 0
	                            WHERE PRJCODE = '$PRJCODE'";*/
	                $sqlCOA	= "UPDATE tbl_chartaccount SET 
	                                Base_Debet = 0, 
	                                Base_Debet_tax = 0, 
	                                Base_Debet2 = 0,
	                                Base_Debet_tax2 = 0,
	                                Base_Kredit = 0,
	                                Base_Kredit_tax = 0, 
	                                Base_Kredit2 = 0, 
	                                Base_Kredit_tax2 = 0";
	                $this->db->query($sqlCOA);

	            // RE-COUNT CHART ACCOUNT FROM JURNAL FOR ALL RELATION CHART ACCOUNT
					$sqlJDX		= "tbl_journaldetail A INNER JOIN tbl_journalheader B 
	                                    ON A.JournalH_Code = B.JournalH_Code
	                                WHERE B.GEJ_STAT = '3'";
					$resjdX		= $this->db->count_all($sqlJDX);
	                $totRow 	= 0;
	                $sqlJOURND	= "SELECT A.Acc_Id, A.proj_Code, A.Base_Debet, 
	                                    A.Base_Debet_tax, A.Base_Kredit, A.Base_Kredit_tax
	                                FROM tbl_journaldetail A INNER JOIN tbl_journalheader B 
	                                    ON A.JournalH_Code = B.JournalH_Code
	                                WHERE B.GEJ_STAT = '3'";
	                $resJOURND	= $this->db->query($sqlJOURND)->result();
	                foreach($resJOURND as $rowJD) :
	                	$totRow				= $totRow+1;
	                    $Acc_Id				= $rowJD->Acc_Id;
	                    $proj_Code			= $rowJD->proj_Code;
	                    $Base_Debet			= $rowJD->Base_Debet;
	                    $Base_Debet_tax		= $rowJD->Base_Debet_tax;
	                    $Base_Kredit		= $rowJD->Base_Kredit;
	                    $Base_Kredit_tax	= $rowJD->Base_Kredit_tax;
	                    
	                    // LOOP PROJECT
	                    $syncPRJ	= '';
	                    $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount
	                                    WHERE PRJCODE = '$proj_Code'
	                                        AND Account_Number = '$Acc_Id' LIMIT 1";
	                    $resISHO	= $this->db->query($sqlISHO)->result();
	                    foreach($resISHO as $rowISHO):
	                        $syncPRJ= $rowISHO->syncPRJ;
	                    endforeach;
	                    $dataPecah 	= explode("~",$syncPRJ);
	                    $jmD 		= count($dataPecah);
	                
	                    if($jmD > 0)
	                    {
	                        $SYNC_PRJ	= '';
	                        for($i=0; $i < $jmD; $i++)
	                        {
	                            $SYNC_PRJ	= $dataPecah[$i];
	                            $sqlCOA		= "UPDATE tbl_chartaccount SET 
			                                        Base_Debet = Base_Debet - $Base_Debet, 
			                                        Base_Debet_tax = Base_Debet_tax - $Base_Debet_tax, 
			                                        Base_Debet2 = Base_Debet2 - $Base_Debet,
			                                        Base_Debet_tax2 = Base_Debet_tax2 - $Base_Debet_tax,
			                                        Base_Kredit = Base_Kredit - $Base_Kredit,
			                                        Base_Kredit_tax = Base_Kredit_tax - $Base_Kredit_tax, 
			                                        Base_Kredit2 = Base_Kredit2 - $Base_Kredit, 
			                                        Base_Kredit_tax2 = Base_Kredit_tax2 - $Base_Kredit_tax 
		                                        WHERE Account_Number = '$Acc_Id' 
		                                        AND PRJCODE = '$SYNC_PRJ'";
	                            $this->db->query($sqlCOA);
	                        }
	                    }
                        $rata2 	= intval($totRow / $resjdX * 100);
                        if($rata2 == 25)
                        {
							$percent = intval(80)."%";
							echo '<script>
						    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

						    ob_flush();
						    flush();
                        }
                        elseif($rata2 == 50)
                        {
							$percent = intval(80)."%";
							echo '<script>
						    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

						    ob_flush();
						    flush();
                        }
                        elseif($rata2 == 85)
                        {
							$percent = intval(80)."%";
							echo '<script>
						    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

						    ob_flush();
						    flush();
                        }
                        elseif($rata2 > 88)
                        {
							$percent = intval(88)."%";
							echo '<script>
						    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

						    ob_flush();
						    flush();
                        }
	                endforeach;

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(100)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

			    ob_flush();
			    flush();
	        }
			elseif($RESFULL == 1)	// Bersihkan Semua Data
			{
				$s_001	= "TRUNCATE tbl_alert_list"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_amd_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_amd_header"; $this->db->query($s_001);
				
				$percent = intval(1)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

				$s_001	= "TRUNCATE tbl_apartement"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_approve_hist"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_expd"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_exph"; $this->db->query($s_001);
				
				$percent = intval(2)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

				$s_001	= "TRUNCATE tbl_asset_group"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_joblist"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_list"; $this->db->query($s_001);
				
				$percent = intval(3)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_asset_mainten"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_maintendet"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_prod"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_rcost"; $this->db->query($s_001);
				
				$percent = intval(4)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_asset_rjob"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_tsfd"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_tsfh"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_usage"; $this->db->query($s_001);
				
				$percent = intval(5)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_asset_usagedet"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_asset_usagereq"; $this->db->query($s_001);
				
				$percent = intval(6)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_assetexp_concl"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_assetexp_detail"; $this->db->query($s_001);
				
				$percent = intval(7)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    

				$s_001	= "TRUNCATE tbl_assetexp_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_auth"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_balances"; $this->db->query($s_001);
				
				$percent = intval(8)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_bgheader"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_bobot"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_bom_detail"; $this->db->query($s_001);
				
				$percent = intval(9)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_bom_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_bom_stfdetail"; $this->db->query($s_001);
				
				$percent = intval(10)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_bom_stfdetail_qrc"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_boq_hist"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_boqlist"; $this->db->query($s_001);
				
				$percent = intval(11)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_boqlistm"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_bp_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_bp_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_br_detail"; $this->db->query($s_001);
				
				$percent = intval(12)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_br_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_cashbank"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_cb_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_cb_header"; $this->db->query($s_001);
				
				$percent = intval(13)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_ccal_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ccal_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ccoa"; $this->db->query($s_001);
				
				$percent = intval(15)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_cf_report_in"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_cf_report_out"; $this->db->query($s_001);
				
				$percent = intval(16)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_chartaccount"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_chartaccountm"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_coa_uphist"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_coadetail"; $this->db->query($s_001);
				
				$percent = intval(17)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_dash_data"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_dash_hr"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_dash_sett_emp"; $this->db->query($s_001);
				
				$percent = intval(19)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_dash_sett_hr_emp"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_dash_transac"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_dash_transac_all"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_decreaseinvoice"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_doc_concl"; $this->db->query($s_001);
				
				$percent = intval(23)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_dp_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_dp_report"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_dp_report_in"; $this->db->query($s_001);
				
				$percent = intval(24)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_dp_report_out"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_dpr_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_dwlhist"; $this->db->query($s_001);
				
				$percent = intval(25)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_financial_monitor"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_financial_track"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_fpa_detail"; $this->db->query($s_001);
				
				$percent = intval(26)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_fpa_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_fuel_usage"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_genfileupload"; $this->db->query($s_001);
				
				$percent = intval(27)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_inv_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_inv_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ir_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ir_detail_tmp"; $this->db->query($s_001);
				
				$percent = intval(28)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_ir_detail_trash"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ir_header"; $this->db->query($s_001);
				
				$percent = intval(30)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_item"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_item_adjd"; $this->db->query($s_001);
				
				$percent = intval(32)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_item_adjh"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_item_colld"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_item_collh"; $this->db->query($s_001);
				
				$percent = intval(34)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_item_cutd"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_item_cuth"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_item_temp"; $this->db->query($s_001);
				
				$percent = intval(35)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_item_tsfd"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_item_tsfh"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_item_uphist"; $this->db->query($s_001);
				
				$percent = intval(37)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_item_whqty"; $this->db->query($s_001);
				//$s_001	= "TRUNCATE tbl_item_xxx"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_itemhistory"; $this->db->query($s_001);
				
				$percent = intval(39)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_janalysis_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_janalysis_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_janalysis_jlist"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_janalysis_manl"; $this->db->query($s_001);
				
				$percent = intval(40)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_jo_concl"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_jo_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_jo_detail_tmp3"; $this->db->query($s_001);
				
				$percent = intval(42)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_jo_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_jo_stfdetail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_jo_stfdetail_qrc"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_joblist"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_joblist_detail"; $this->db->query($s_001);
				
				$percent = intval(43)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_joblist_detail_temp"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_joblist_detailm"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_joblist_temp"; $this->db->query($s_001);
				
				$percent = intval(44)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_joblistm"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_jobopname"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_journaldetail"; $this->db->query($s_001);
				
				$percent = intval(46)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_journaldetail_pd"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_journaldetail_temp"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_journaldetail_vcash"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_journalheader"; $this->db->query($s_001);
				
				$percent = intval(47)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_journalheader_pd"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_journalheader_temp"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_journalheader_vcash"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_lastsync"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_lastsync_lr"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_login_concl"; $this->db->query($s_001);
				
				$percent = intval(48)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_login_hist"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_manalysis_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_manalysis_header"; $this->db->query($s_001);
				
				$percent = intval(50)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_master_item"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_mc_balance"; $this->db->query($s_001);
				
				$percent = intval(51)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_mc_conc"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_mc_plan"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_mcg_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_mcg_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_mcheader"; $this->db->query($s_001);
				
				$percent = intval(52)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_mr_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_mr_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_offering_d"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_offering_h"; $this->db->query($s_001);
				
				$percent = intval(54)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_opn_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_opn_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_opn_inv"; $this->db->query($s_001);
				
				$percent = intval(55)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_opn_invdet"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_outpay_report"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_overhead"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_pinv_detail"; $this->db->query($s_001);
				
				$percent = intval(57)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_pinv_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_po_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_po_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_pr_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_pr_detail_canc"; $this->db->query($s_001);
				
				$percent = intval(58)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_pr_detail_tmp"; $this->db->query($s_001);
				//$s_001	= "TRUNCATE tbl_pr_detail_trash"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_pr_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_pr_header_temp"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_pr_header_trash"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_printdoc"; $this->db->query($s_001);
				
				$percent = intval(60)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_printdoc_wo"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_profitloss"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_profloss_man"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_progg_uphist"; $this->db->query($s_001);
				
				$percent = intval(61)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "DELETE FROM tbl_project WHERE isHO != 1"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_project_active"; $this->db->query($s_001);
				$s_001	= "DELETE FROM tbl_project_budg WHERE isHO != 1"; $this->db->query($s_001);
				
				$percent = intval(63)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "DELETE FROM tbl_project_budgm WHERE isHO != 1"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_project_concl"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_project_doc"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_project_liveinfo"; $this->db->query($s_001);
				
				$percent = intval(65)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_project_liveinfopic"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_project_progress"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_project_progress_det"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_project_recom"; $this->db->query($s_001);
				
				$percent = intval(66)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_project_recom_hist"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_projhistory"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_projinv_detail"; $this->db->query($s_001);
				
				$percent = intval(68)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_projinv_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_projinv_realh"; $this->db->query($s_001);
				
				$percent = intval(70)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_projplan_material"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_projprogres"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_purch_report"; $this->db->query($s_001);
				
				$percent = intval(71)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_qhsedoc_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_qrc_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_qty_coll"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_reservation"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_reset_login"; $this->db->query($s_001);
				
				$percent = intval(73)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_ret_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ret_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_rhist"; $this->db->query($s_001);
				
				$percent = intval(74)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				//$s_001	= "TRUNCATE tbl_rtflista"; $this->db->query($s_001);
				//$s_001	= "TRUNCATE tbl_rtflistb"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_schedule"; $this->db->query($s_001);
				//$s_001	= "TRUNCATE tbl_sementara"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sicertificate"; $this->db->query($s_001);
				
				$percent = intval(75)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_sicertificatedet"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_siheader"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sinv_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sinv_detail_qrc"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sinv_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sn_detail"; $this->db->query($s_001);
				
				$percent = intval(78)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_sn_detail_qrc"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sn_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_so_concl"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_so_detail"; $this->db->query($s_001);
				
				$percent = intval(79)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_so_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sopn_concl"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sopn_detail"; $this->db->query($s_001);
				
				$percent = intval(80)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_sopn_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_spkprint"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sr_detail"; $this->db->query($s_001);
				
				$percent = intval(81)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_sr_detail_qrc"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_sr_header"; $this->db->query($s_001);
				
				$percent = intval(83)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_stf_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_stf_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_stf_mtrused"; $this->db->query($s_001);
				
				$percent = intval(84)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_trackcreater"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_trail_tracker"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_trans_count"; $this->db->query($s_001);
				
				$percent = intval(85)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_ttk"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ttk_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ttk_detail_itm"; $this->db->query($s_001);
				
				$percent = intval(87)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_ttk_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ttk_print"; $this->db->query($s_001);
				
				$percent = intval(89)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_ttk_tax"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_ttkestinvoice"; $this->db->query($s_001);
				
				$percent = intval(90)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_um_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_um_header"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_uploadbca"; $this->db->query($s_001);
				
				$percent = intval(94)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_uploadbca_data"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_uploadreceipt"; $this->db->query($s_001);
				
				$percent = intval(96)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_uploadttkest"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_wip"; $this->db->query($s_001);
				
				$percent = intval(97)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_wo_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_wo_header"; $this->db->query($s_001);
				
				$percent = intval(98)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
				$s_001	= "TRUNCATE tbl_wo_print"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_woreq_detail"; $this->db->query($s_001);
				$s_001	= "TRUNCATE tbl_woreq_header"; $this->db->query($s_001);
				
				$percent = intval(100)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
			    
			}
	    }
	    else
	    {
			if($RESFULL == 0)		// Hanya Data Transaksi
			{
	            $sql003	= "DELETE FROM tbl_alert_list WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	        	$s_001	= "DELETE FROM tbl_doc_concl WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($s_001);

	        	$s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AMD_NUM 
	                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_amd_detail WHERE AMD_NUM IN (SELECT AMD_NUM 
	                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_apartement			-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASEXP_NUM 
	                            FROM tbl_asset_exph WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_exph WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_expd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(2)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	        	// tbl_asset_group 			-- No Reset

	            $sql003	= "DELETE FROM tbl_asset_joblist WHERE JL_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	        	// tbl_asset_list 			-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AM_CODE 
	                            FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_maintendet WHERE AM_CODE IN (SELECT AM_CODE 
	                            FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_asset_prod WHERE AP_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_asset_rcost WHERE RASTC_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_asset_rjob WHERE RASTC_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(5)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASTSF_NUM 
	                            FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_tsfd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_asset_type			-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AU_CODE 
	                            FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_usagedet WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(8)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AUR_CODE 
	                            FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_assetexp_concl WHERE RASTXP_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RASSET_CODE 
	                            FROM tbl_assetexp_header WHERE RASSET_PROJECT = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_assetexp_detail WHERE RASSETD_PROJECT = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_assetexp_header WHERE RASSET_PROJECT = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(8)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_auth 				-- Not Used
	            // tbl_balances				-- Not Used
	            // tbl_bgheader				-- Not Used

	            $sql003	= "DELETE FROM tbl_bobot WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT BOM_NUM 
	                            FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_bom_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql004	= "DELETE FROM tbl_bom_stfdetail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql004);
	            $sql004	= "DELETE FROM tbl_bom_stfdetail_qrc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql004);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(8)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql003	= "DELETE FROM tbl_boq_hist WHERE BOQH_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_boqlist_temp WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlistm WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
	                            FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_bp_detail WHERE JournalH_Code IN (SELECT JournalH_Code 
	                            FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(11)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
	                            FROM tbl_br_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_br_detail WHERE JournalH_Code IN 
	                        (SELECT JournalH_Code FROM tbl_br_header 
	                        WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_br_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_cashbank				-- No Reset
	            // tbl_cb_detail			-- No Reset / Not Used
	            // tbl_cb_header			-- No Reset / Not Used

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(13)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT CCAL_NUM 
	                            FROM tbl_ccal_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_ccal_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ccal_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_ccoa WHERE CCOA_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(14)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_cf_report_in, tbl_cf_report_out		-- Not Used

	            // RE-COUNT CHART ACCOUNT FROM JURNAL FOR ALL RELATION CHART ACCOUNT
	                /*$sqlJOURND	= "SELECT A.Acc_Id, A.proj_Code, A.Base_Debet, 
	                                    A.Base_Debet_tax, A.Base_Kredit, A.Base_Kredit_tax
	                                FROM tbl_journaldetail A INNER JOIN tbl_journalheader B 
	                                    ON A.JournalH_Code = B.JournalH_Code
	                                WHERE B.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3";
	                $resJOURND	= $this->db->query($sqlJOURND)->result();
	                foreach($resJOURND as $rowJD) :
	                    $Acc_Id				= $rowJD->Acc_Id;
	                    $proj_Code			= $rowJD->proj_Code;
	                    $Base_Debet			= $rowJD->Base_Debet;
	                    $Base_Debet_tax		= $rowJD->Base_Debet_tax;
	                    $Base_Kredit		= $rowJD->Base_Kredit;
	                    $Base_Kredit_tax	= $rowJD->Base_Kredit_tax;
	                    
	                    // LOOP PROJECT
	                    $syncPRJ	= '';
	                    $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount
	                                    WHERE PRJCODE = '$proj_Code'
	                                        AND Account_Number = '$Acc_Id' LIMIT 1";
	                    $resISHO	= $this->db->query($sqlISHO)->result();
	                    foreach($resISHO as $rowISHO):
	                        $syncPRJ= $rowISHO->syncPRJ;
	                    endforeach;
	                    $dataPecah 	= explode("~",$syncPRJ);
	                    $jmD 		= count($dataPecah);
	                
	                    if($jmD > 0)
	                    {
	                        $SYNC_PRJ	= '';
	                        for($i=0; $i < $jmD; $i++)
	                        {
	                            $SYNC_PRJ	= $dataPecah[$i];
	                            $sqlCOA		= "UPDATE tbl_chartaccount SET 
			                                        Base_Debet = Base_Debet - $Base_Debet, 
			                                        Base_Debet_tax = Base_Debet_tax - $Base_Debet_tax, 
			                                        Base_Debet2 = Base_Debet2 - $Base_Debet,
			                                        Base_Debet_tax2 = Base_Debet_tax2 - $Base_Debet_tax,
			                                        Base_Kredit = Base_Kredit - $Base_Kredit,
			                                        Base_Kredit_tax = Base_Kredit_tax - $Base_Kredit_tax, 
			                                        Base_Kredit2 = Base_Kredit2 - $Base_Kredit, 
			                                        Base_Kredit_tax2 = Base_Kredit_tax2 - $Base_Kredit_tax 
		                                        WHERE Account_Number = '$Acc_Id' 
		                                        AND PRJCODE = '$SYNC_PRJ'";
	                            $this->db->query($sqlCOA);
	                        }
	                    }
	                endforeach;*/
	                
	            // CLEAR CHART ACCOUNT
	                /*$sqlCOA	= "UPDATE tbl_chartaccount SET 
	                                Base_Debet = 0, 
	                                Base_Debet_tax = 0, 
	                                Base_Debet2 = 0,
	                                Base_Debet_tax2 = 0,
	                                Base_Kredit = 0,
	                                Base_Kredit_tax = 0, 
	                                Base_Kredit2 = 0, 
	                                Base_Kredit_tax2 = 0
	                            WHERE PRJCODE = '$PRJCODE'";
	                $this->db->query($sqlCOA);*/

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(15)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            //, tbl_chartaccountm, 			-- Not Used
	            // tbl_chartcategory,			-- No Reset
	            // tbl_chat, tbl_chat_detail, 	-- Not Used

	            $sql003	= "DELETE FROM tbl_coa_uphist WHERE COAH_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_coadetail, tbl_cssjs, tbl_currate, tbl_currconv, tbl_currency, 	-- No Reset
	            // tbl_custcat, tbl_customer, tbl_customer_img							-- No Reset

	            $sql003	= "DELETE FROM tbl_dash_data WHERE PRJ_CODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_dash_hr, tbl_dash_sett, tbl_dash_sett_emp, tbl_dash_sett_hr, tbl_dash_sett_hr_emp,	-- No Reset

	            $sql003	= "DELETE FROM tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_dash_transac_all, tbl_decreaseinvoice, tbl_department, tbl_doc_cc, tbl_docpattern,	-- No Reset
	            // tbl_docstepapp, tbl_docstepapp_det, tbl_document, 										-- No Reset

	            $sql003	= "DELETE FROM tbl_dp_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(16)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_dp_report, tbl_dp_report_in, tbl_dp_report_out, tbl_dpr_header, tbl_driver,			-- No Reset
	            // tbl_dwlhist, tbl_emp_vers, tbl_employee, tbl_employee_acc, tbl_employee_age, 			-- No Reset
	            // tbl_employee_appauth, tbl_employee_circle, tbl_employee_docauth, tbl_employee_gol,		-- No Reset
	            // tbl_employee_img, tbl_employee_proj, 													-- No Reset

	            $sql003	= "DELETE FROM tbl_financial_monitor WHERE FM_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_financial_track WHERE FT_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FPA_NUM 
	                        FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_fpa_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(18)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FU_CODE 
	                            FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            // tbl_genfileupload, tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,					-- No Reset
	            // tbl_inv_detail, tbl_inv_header,  														-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT IR_NUM 
	                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(20)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	            
	            $sql002	= "DELETE FROM tbl_ir_detail WHERE IR_NUM IN (SELECT IR_NUM 
	                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_ir_detail_trash WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql002	= "DELETE FROM tbl_ir_detail_tmp WHERE IR_NUM IN (SELECT IR_NUM 
	                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);

	            // RESET ITEM
	                $sql003	= "UPDATE tbl_item SET 
	                                ITM_VOLMBGR = ITM_VOLMBG, ITM_VOLM = 0,
	                                ITM_REMQTY = 0, ITM_TOTALP = 0, ITM_LASTP = ITM_PRICE,
	                                ITM_IN = 0, ITM_INP = 0, ITM_OUT = 0, ITM_OUTP = 0, 
	                                PR_VOLM = 0, PR_AMOUNT = 0, MR_VOLM = 0, MR_AMOUNT = 0,
	                                PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0, 
	                                UM_VOLM = 0, UM_AMOUNT = 0, SO_VOLM = 0, SO_AMOUNT = 0,
	                                JO_VOLM = 0, JO_AMOUNT = 0, PROD_VOLM = 0, PROD_AMOUNT = 0, 
	                                RET_VOLM = 0, RET_AMOUNT = 0, ADDVOLM = 0, ADDCOST = 0, 
	                                ADDMVOLM = 0, ADDMCOST = 0, ITM_VOLMBON = 0
	                            WHERE PRJCODE = '$PRJCODE'";
	                $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(25)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ADJ_NUM 
	                            FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            /*$sql002	= "DELETE FROM tbl_item_adjd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);*/
	            $sql003	= "DELETE FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_colld WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_collh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(28)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	            
	            $sql003	= "DELETE FROM tbl_item_cutd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_cuth WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	        
	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ITMTSF_NUM 
	                            FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_item_tsfd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_item_uphist WHERE ITMH_PRJCODE = '$PRJCODE'";
	            $this->db->query($s_001);

	            $s_001	= "DELETE FROM tbl_item_whqty WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($s_001);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(30)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_itemcategory, tbl_itemgroup				-- No Reset

	            $sql003	= "DELETE FROM tbl_itemhistory WHERE proj_Code = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOC_CODE 
	                            FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	        
	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JO_NUM 
	                            FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_jo_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_jo_detail_tmp3 WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(32)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
	                            FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            
	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
	                            FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            
	            $sql002	= "DELETE FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            
	            $sql002	= "DELETE FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(33)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	            
	            // RESET JOBLIST
	                $sql004	= "UPDATE tbl_joblist SET BOQ_PROGR = 0, 
	                                ADD_VOLM = 0, ADD_PRICE = 0, 
	                                ADD_JOBCOST = 0, ADDM_VOLM = 0, ADDM_JOBCOST = 0 
	                            WHERE PRJCODE = '$PRJCODE'";
	                $this->db->query($sql004);
	                
	            // RESET JOBLIST DETAIL
	                $sql005	= "UPDATE tbl_joblist_detail SET ITM_LASTP = ITM_PRICE,
	                                BOQ_AMDVOLM = 0, BOQ_AMDPRICE = 0, BOQ_AMDTOTAL = 0, 
	                                ADD_VOLM = 0, ADD_PRICE = 0, ADD_JOBCOST = 0, 
	                                ADDM_VOLM = 0, ADDM_JOBCOST = 0, REQ_VOLM = 0, REQ_AMOUNT = 0, 
	                                PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0, 
	                                WO_QTY = 0, WO_AMOUNT = 0, OPN_QTY = 0, OPN_AMOUNT = 0, 
	                                ITM_USED = 0, ITM_USED_AM = 0, ITM_RET = 0, ITM_RET_AM = 0, 
	                                ITM_STOCK = 0, ITM_STOCK_AM = 0
	                            WHERE PRJCODE = '$PRJCODE'";
	                $this->db->query($sql005);

	            // tbl_joblist_detailm, tbl_joblistm, tbl_jobopname,	-- Not Used 
	            
	            // CLEAR JOURNAL
	                $s_001	= "DELETE FROM tbl_journalheader WHERE proj_Code = '$PRJCODE'";
	                $this->db->query($s_001);
	                
	                $sql002	= "DELETE FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE'";
	                $this->db->query($sql002);

	            // tbl_language, tbl_lastsync, tbl_link_account, tbl_login_concl, tbl_login_hist,		-- No Reset
	            // tbl_machine, tbl_machine_itm, tbl_mail_dept, tbl_mail_dept_emp, tbl_mail_detail, 	-- No Reset
	            // tbl_mail_header, tbl_mail_type, tbl_mailbox, tbl_mailbox_reply, tbl_mailbox_send,	-- No Reset
	            // tbl_mailbox_trash, tbl_mailbox_trash_ext, tbl_mailgroup_detail, tbl_mailgroup_header,-- No Reset
	            // tbl_major_app, tbl_master_item, tbl_mc_balance										-- No Reset


	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(35)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql003	= "DELETE FROM tbl_mc_conc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_mc_plan WHERE MCP_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MCH_CODE 
	                            FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_mcg_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	        
	            $sql003	= "DELETE FROM tbl_mcheader WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(38)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_meeting_room						-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MR_NUM 
	                            FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_mr_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_news_detail, tbl_news_header		-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OFF_NUM 
	                            FROM tbl_offering_h WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_offering_d WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_offering_h WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(40)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OPNH_NUM 
	                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_opn_detail WHERE OPNH_NUM IN (SELECT OPNH_NUM 
	                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_opn_inv, tbl_opn_invdet, 		-- Not Used
	            // tbl_outpay_report, tbl_overhead, 	-- Not Used
	            // tbl_owner, tbl_owner_img,			-- No Reset
	            // tbl_payterm							-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT INV_NUM 
	                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_pinv_detail WHERE INV_NUM IN (SELECT INV_NUM 
	                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PO_NUM 
	                            FROM tbl_po_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_po_detail WHERE PO_NUM IN (SELECT PO_NUM 
	                            FROM tbl_po_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_po_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(45)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_position, tbl_position_func, tbl_position_str, 						-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PR_NUM 
	                            FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_pr_detail WHERE PR_NUM IN (SELECT PR_NUM 
	                            FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_pr_detail_trash WHERE PR_NUM IN (SELECT PR_NUM 
	                            FROM tbl_pr_header_trash WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_pr_header_trash WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_pr_detail_canc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_printdoc, tbl_printdoc_wo,											-- Not Used
	            // tbl_prodstep, 															-- No Reset

	            $sql003	= "DELETE FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_profloss_man,														-- Not Used
	            // tbl_progg_uphist,														-- Not Used
	            // tbl_project, tbl_project_active, tbl_project_budg, tbl_project_budgm, 	-- No Reset

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(48)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql002	= "DELETE FROM tbl_project_concl WHERE PROGG_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PRJP_NUM 
	                            FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_project_progress_det WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_project_recom, tbl_project_recom_hist,								-- Not Used
	            // tbl_projhistory, tbl_projinv_detail										-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PINV_CODE 
	                            FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql003	= "DELETE FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_projinv_realh, tbl_projplan_material									-- Not Used

	            $sql002	= "UPDATE tbl_projprogres SET Prg_Real = 0, Prg_RealAkum = 0, 
	                            Prg_Dev = 0, isShowRA = 0, isShowDev = 0,
	                            isShowRA = 0, isShowDev = 0, lastStepPS = 0
	                        WHERE proj_Code = '$PRJCODE'";
	            $this->db->query($sql002);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(50)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_purch_report, tbl_qhsedoc_header, 									-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT QRC_NUM 
	                            FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_qty_coll WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_reservation,															-- Not Used
	        
	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RET_NUM 
	                            FROM tbl_ret_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_ret_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ret_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(51)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_riskcategory, tbl_riskdescdet, tbl_riskidentif, tbl_riskimpactdet, tbl_riskpolicydet,	-- Not Used
	            // tbl_rtflista, tbl_rtflistb, tbl_schedule, tbl_section, tbl_sementara,	-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SIC_CODE 
	                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_sicertificatedet WHERE SIC_CODE IN (SELECT SIC_CODE 
	                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_sinv_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SINV_NUM 
	                            FROM tbl_sinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_sinv_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sinv_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(53)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql003	= "DELETE FROM tbl_sn_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SN_NUM 
	                            FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_sn_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SO_NUM 
	                            FROM tbl_so_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_so_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_so_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_so_concl WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(55)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_sopn_concl, tbl_sopn_detail, tbl_sopn_header, tbl_spkprint,			-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SR_NUM 
	                            FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_sr_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_sr_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT STF_NUM 
	                            FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_stf_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_stf_mtrused,															-- Not Used
	            // tbl_supplier, tbl_task_request, tbl_task_request_detail,					-- No Reset
	            // tbl_tax, tbl_tax_la, tbl_tax_ppn, tbl_tax_ppn_copy, tbl_trackcreater,	-- No Reset 

	            $sql002	= "DELETE FROM tbl_trail_tracker WHERE TTR_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

				$percent = intval(58)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_translate, tbl_trashsys,

	            $sql003	= "DELETE FROM tbl_ttk WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT TTK_NUM 
	                            FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_ttk_detail WHERE TTK_NUM IN (SELECT TTK_NUM 
	                            FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_ttk_print WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);


	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(59)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_ttkestinvoice														-- Not Used

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT UM_NUM 
	                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_um_detail WHERE UM_NUM IN (SELECT UM_NUM 
	                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_um_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_unittype,															-- No Reset
	            // tbl_uploadbca, tbl_uploadbca_data, tbl_uploadreceipt, tbl_uploadttkest,	-- Not Used
	            // tbl_userdoctype,															-- Not Used
	            // tbl_vehicle, tbl_vendcat, tbl_warehouse, tbl_wip, 						-- No Reset

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
	                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_wo_detail WHERE WO_NUM IN (SELECT WO_NUM 
	                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_wo_print WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $s_001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
	                            FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($s_001);
	            $sql002	= "DELETE FROM tbl_woreq_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	        	// tglobalsetting, tusermenu


	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
	            $perVal	 = 60;
				$percent = intval(60)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	                
	            // OTHERS
	                $sqloth01	= "TRUNCATE tbl_login_hist";
	                // $this->db->query($sqloth01);
	                
	            // CLEAR CHART ACCOUNT
	                /*$sqlCOA	= "UPDATE tbl_chartaccount SET 
	                                Base_Debet = 0, 
	                                Base_Debet_tax = 0, 
	                                Base_Debet2 = 0,
	                                Base_Debet_tax2 = 0,
	                                Base_Kredit = 0,
	                                Base_Kredit_tax = 0, 
	                                Base_Kredit2 = 0, 
	                                Base_Kredit_tax2 = 0
	                            WHERE PRJCODE = '$PRJCODE'";*/
	                $sqlCOA	= "UPDATE tbl_chartaccount SET 
	                                Base_Debet = 0, 
	                                Base_Debet_tax = 0, 
	                                Base_Debet2 = 0,
	                                Base_Debet_tax2 = 0,
	                                Base_Kredit = 0,
	                                Base_Kredit_tax = 0, 
	                                Base_Kredit2 = 0, 
	                                Base_Kredit_tax2 = 0";
	                $this->db->query($sqlCOA);

	            // RE-COUNT CHART ACCOUNT FROM JURNAL FOR ALL RELATION CHART ACCOUNT
					$sqlJDX		= "tbl_journaldetail A INNER JOIN tbl_journalheader B 
	                                    ON A.JournalH_Code = B.JournalH_Code
	                                WHERE B.GEJ_STAT = '3'";
					$resjdX		= $this->db->count_all($sqlJDX);
	                $totRow 	= 0;
	                $sqlJOURND	= "SELECT A.Acc_Id, A.proj_Code, A.Base_Debet, 
	                                    A.Base_Debet_tax, A.Base_Kredit, A.Base_Kredit_tax
	                                FROM tbl_journaldetail A INNER JOIN tbl_journalheader B 
	                                    ON A.JournalH_Code = B.JournalH_Code
	                                WHERE B.GEJ_STAT = 3";
	                $resJOURND	= $this->db->query($sqlJOURND)->result();
	                foreach($resJOURND as $rowJD) :
	                	$totRow				= $totRow+1;
	                    $Acc_Id				= $rowJD->Acc_Id;
	                    $proj_Code			= $rowJD->proj_Code;
	                    $Base_Debet			= $rowJD->Base_Debet;
	                    $Base_Debet_tax		= $rowJD->Base_Debet_tax;
	                    $Base_Kredit		= $rowJD->Base_Kredit;
	                    $Base_Kredit_tax	= $rowJD->Base_Kredit_tax;
	                    
	                    // LOOP PROJECT
	                    $syncPRJ	= '';
	                    $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount
	                                    WHERE PRJCODE = '$proj_Code'
	                                        AND Account_Number = '$Acc_Id' LIMIT 1";
	                    $resISHO	= $this->db->query($sqlISHO)->result();
	                    foreach($resISHO as $rowISHO):
	                        $syncPRJ= $rowISHO->syncPRJ;
	                    endforeach;
	                    $dataPecah 	= explode("~",$syncPRJ);
	                    $jmD 		= count($dataPecah);
	                
	                    if($jmD > 0)
	                    {
	                        $SYNC_PRJ	= '';
	                        for($i=0; $i < $jmD; $i++)
	                        {
	                            $SYNC_PRJ	= $dataPecah[$i];
	                            $sqlCOA		= "UPDATE tbl_chartaccount SET 
			                                        Base_Debet = Base_Debet - $Base_Debet, 
			                                        Base_Debet_tax = Base_Debet_tax - $Base_Debet_tax, 
			                                        Base_Debet2 = Base_Debet2 - $Base_Debet,
			                                        Base_Debet_tax2 = Base_Debet_tax2 - $Base_Debet_tax,
			                                        Base_Kredit = Base_Kredit - $Base_Kredit,
			                                        Base_Kredit_tax = Base_Kredit_tax - $Base_Kredit_tax, 
			                                        Base_Kredit2 = Base_Kredit2 - $Base_Kredit, 
			                                        Base_Kredit_tax2 = Base_Kredit_tax2 - $Base_Kredit_tax 
		                                        WHERE Account_Number = '$Acc_Id' 
		                                        AND PRJCODE = '$SYNC_PRJ'";
	                            $this->db->query($sqlCOA);
	                        }
	                        $rata2 	= intval($totRow / $resjdX * 100);
	                        if($rata2 == 25)
	                        {
								$percent = intval(80)."%";
								echo '<script>
							    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

							    ob_flush();
							    flush();
	                        }
	                        elseif($rata2 == 50)
	                        {
								$percent = intval(80)."%";
								echo '<script>
							    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

							    ob_flush();
							    flush();
	                        }
	                        elseif($rata2 == 85)
	                        {
								$percent = intval(80)."%";
								echo '<script>
							    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

							    ob_flush();
							    flush();
	                        }
	                        elseif($rata2 > 88)
	                        {
								$percent = intval(88)."%";
								echo '<script>
							    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

							    ob_flush();
							    flush();
	                        }
	                    }
	                endforeach;

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(100)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

			    ob_flush();
			    flush();
	        }
			elseif($RESFULL == 1)	// Bersihkan Semua Data
			{
		            $s_001	= "TRUNCATE tbl_doc_concl"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_alert_list"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_approve_hist"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_amd_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_amd_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_apartement"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_exph"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_expd"; $this->db->query($s_001);
		        	// tbl_asset_group 			-- No Reset
		            $s_001	= "TRUNCATE tbl_asset_joblist"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_list"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_maintendet"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(1)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_asset_mainten"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_prod"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_rcost"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_rjob"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_tsfh"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_tsfd"; $this->db->query($s_001);
		            // tbl_asset_type			-- No Reset
		            $s_001	= "TRUNCATE tbl_asset_usagedet"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_usage"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_asset_usagereq"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_assetexp_concl"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(10)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_assetexp_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_assetexp_detail"; $this->db->query($s_001);
		            // tbl_auth 				-- Not Used
		            $s_001	= "TRUNCATE tbl_balances"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_bgheader"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_bobot"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_bom_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_bom_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_bom_stfdetail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_bom_stfdetail_qrc"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_boq_hist"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(15)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_boqlist"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_boqlistm"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_bp_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_bp_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_br_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_br_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_cashbank"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_cb_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_cb_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ccal_detail"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(20)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_ccal_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ccoa"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_cf_report_in"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_cf_report_out"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_chartaccount"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_chartaccountm"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_chat"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_chat_detail"; $this->db->query($s_001);
		            // tbl_chartcategory,			-- No Reset
		            $s_001	= "TRUNCATE tbl_coa_uphist"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_coadetail"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				    $percent = intval(25)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            // tbl_cssjs, tbl_currate, tbl_currconv, tbl_currency, 			-- No Reset
		            // tbl_custcat,													-- No Reset
		            $s_001	= "TRUNCATE tbl_customer"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_customer_img"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dash_data"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dash_hr"; $this->db->query($s_001);
		            // , tbl_dash_sett, , tbl_dash_sett_hr, tbl_dash_sett_hr_emp,	-- No Reset
		            //$s_001	= "TRUNCATE tbl_dash_sett_emp"; $this->db->query($s_001);
		            $sql003	= "DELETE FROM tbl_dash_sett_emp WHERE EMP_ID != '$EMPID'";
	            	$this->db->query($sql003);
		            $s_001	= "TRUNCATE tbl_dash_transac"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dash_transac_all"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_decreaseinvoice"; $this->db->query($s_001);
		            // tbl_department, tbl_doc_cc, tbl_docpattern,					-- No Reset
		            $s_001	= "TRUNCATE tbl_docstepapp"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_docstepapp_det"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(28)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_document"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_doc_concl"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dp_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dp_report"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dp_report_in"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dp_report_out"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dpr_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_driver"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_dwlhist"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_emp_vers"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_employee_acc"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(30)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            // tbl_employee, tbl_employee_age, 								-- No Reset
		            $s_001	= "TRUNCATE tbl_employee_appauth"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_employee_docauth"; $this->db->query($s_001);
		            // tbl_employee_circle, tbl_employee_gol,						-- No Reset
		            // tbl_employee_img,											-- No Reset
					$sql002	= "DELETE FROM tbl_employee_proj WHERE proj_Code NOT IN (SELECT PRJCODE FROM tbl_project WHERE PRJTYPE = 1)";
		            $this->db->query($sql002);
		            $s_001	= "TRUNCATE tbl_financial_monitor"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_financial_track"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_fpa_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_fpa_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_fuel_usage"; $this->db->query($s_001);
		            // tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,		-- No Reset
		            $s_001	= "TRUNCATE tbl_genfileupload"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_inv_detail"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				    $percent = intval(40)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_inv_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ir_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ir_detail_tmp"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ir_detail_trash"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ir_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item"; $this->db->query($s_001);
		            //$s_001	= "TRUNCATE tbl_item_adjd"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item_adjh"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item_colld"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item_collh"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item_cutd"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(46)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_item_cuth"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item_tsfd"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item_tsfh"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item_uphist"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_item_whqty"; $this->db->query($s_001);
		            // tbl_itemcategory, tbl_itemgroup								-- No Reset
		            $s_001	= "TRUNCATE tbl_itemhistory"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_jo_concl"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_jo_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_jo_detail_tmp3"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_jo_header"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(50)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_jo_stfdetail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_jo_stfdetail_qrc"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_joblist"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_joblist_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_joblist_detailm"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_joblistm"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_jobopname"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_journaldetail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_journalheader"; $this->db->query($s_001);
		            // tbl_language, , tbl_link_account,							-- No Reset
		            $s_001	= "TRUNCATE tbl_lastsync"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(55)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_login_concl"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_login_hist"; $this->db->query($s_001);
		            // tbl_machine, tbl_mail_dept,									-- No Reset
		            $s_001	= "TRUNCATE tbl_machine_itm"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mail_dept_emp"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mail_detail"; $this->db->query($s_001);
		            // tbl_mail_type,												-- No Reset
		            $s_001	= "TRUNCATE tbl_mail_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mailbox"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mailbox_reply"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mailbox_send"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mailbox_trash"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(55)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_mailbox_trash_ext"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mailgroup_detail"; $this->db->query($s_001);
		            // tbl_mailgroup_header,										-- No Reset
		            $s_001	= "TRUNCATE tbl_major_app"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_master_item"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mc_balance"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mc_conc"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mc_plan"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mcg_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mcg_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mcheader"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(60)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_meeting_room"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mr_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_mr_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_news_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_news_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_offering_d"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_offering_h"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_opn_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_opn_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_opn_inv"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(65)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_opn_invdet"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_outpay_report"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_overhead"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_owner"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_owner_img"; $this->db->query($s_001);
		            // tbl_payterm													-- No Reset
		            $s_001	= "TRUNCATE tbl_pinv_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_pinv_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_po_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_po_header"; $this->db->query($s_001);
		            // tbl_position, tbl_position_func, tbl_position_str, 			-- No Reset
		            $s_001	= "TRUNCATE tbl_pr_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_pr_detail_canc"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				    $percent = intval(80)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_pr_detail_trash"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_pr_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_pr_header_trash"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_printdoc"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_printdoc_wo"; $this->db->query($s_001);
		            // tbl_prodstep, 												-- No Reset
		            $s_001	= "TRUNCATE tbl_profitloss"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_profloss_man"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_progg_uphist"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(80)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql002	= "DELETE FROM tbl_project WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $s_001	= "TRUNCATE tbl_project_active"; $this->db->query($s_001);
		            $sql002	= "DELETE FROM tbl_project_budg WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $sql002	= "DELETE FROM tbl_project_budgm WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $s_001	= "TRUNCATE tbl_project_progress"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_project_progress_det"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_project_recom"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_project_recom_hist"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_projhistory"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_projinv_detail"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(85)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_projinv_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_projinv_realh"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_projplan_material"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_projprogres"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_purch_report"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_qhsedoc_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_qrc_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_qty_coll"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_reservation"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ret_detail"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(80)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $s_001	= "TRUNCATE tbl_ret_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_riskcategory"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_riskdescdet"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_riskidentif"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_riskimpactdet"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_riskpolicydet"; $this->db->query($s_001);
		            //$s_001	= "TRUNCATE tbl_rtflista"; $this->db->query($s_001);
		            //$s_001	= "TRUNCATE tbl_rtflistb"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_schedule"; $this->db->query($s_001);

	            	// tbl_section,													-- Not Used

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(85)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            //$s_001	= "TRUNCATE tbl_sementara"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sicertificate"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sicertificatedet"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_siheader"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sinv_detail_qrc"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sinv_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sinv_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sn_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sn_detail_qrc"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sn_header"; $this->db->query($s_001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(88)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_so_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_so_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_so_concl"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sopn_concl"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sopn_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sopn_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_spkprint"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sr_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sr_detail_qrc"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_sr_header"; $this->db->query($s_001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(82)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_stf_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_stf_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_stf_mtrused"; $this->db->query($s_001);
		            // tbl_supplier,												-- No Reset
		            $s_001	= "TRUNCATE tbl_task_request"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_task_request_detail"; $this->db->query($s_001);
		            // tbl_tax, tbl_tax_la, tbl_tax_ppn, tbl_tax_ppn_copy,			-- No Reset
		            $s_001	= "TRUNCATE tbl_trackcreater"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_trail_tracker"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_trans_count"; $this->db->query($s_001);
		            // tbl_translate, tbl_trashsys,
		            $s_001	= "TRUNCATE tbl_ttk"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ttk_detail"; $this->db->query($s_001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(86)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $s_001	= "TRUNCATE tbl_ttk_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ttk_print"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_ttkestinvoice"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_um_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_um_header"; $this->db->query($s_001);
		            // tbl_unittype,												-- No Reset
		            $s_001	= "TRUNCATE tbl_uploadbca"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_uploadbca_data"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_uploadreceipt"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_uploadttkest"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_userdoctype"; $this->db->query($s_001);

		            // tbl_vehicle, tbl_vendcat,		 							-- No Reset
		            $s_001	= "TRUNCATE tbl_warehouse"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_wip"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_wo_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_wo_header"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_wo_print"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_woreq_detail"; $this->db->query($s_001);
		            $s_001	= "TRUNCATE tbl_woreq_header"; $this->db->query($s_001);

		        	// tglobalsetting, tusermenu

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(100)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

				    ob_flush();
				    flush();
			}
		}

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Sistem telah selesai melakukan pengaturan ulang sistem oleh $ipaddress - $host_name melalui $browser. Anda akan keluar.";
		}
		else
		{
			$alert1	= "The system has finished system reset by $ipaddress - $host_name in $browser. You will log out.";
		}
		echo '<script>parent.updStat("'.$alert1.'");</script>';
	}
	else if($IMP_TYPE == 'REODERCOA')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');

		$SYNC_PRJ	= $PRJCODE;

		$sqlCOAC	= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ'";
		$resCOAC	= $this->db->count_all($sqlCOAC);

		$sqlICCOA	= "INSERT INTO tbl_ccoa (CCOA_PRJCODE, CCOA_ISCHECK, CCOA_ISCHECKED, CCOA_ISCHECKER)
						VALUES ('$SYNC_PRJ', 1, '$dateNow1', '$DefEmp_ID')";
		$this->db->query($sqlICCOA);
		
		// START : PREPARE PROCEDUR - RESET ORDER
			// 1.	CEK POSISI LEVEL 0, DARI KODE INDUK YANG TIDAK ADA DI DALAM DAFTAR COA
					$sql_01	= "SELECT A.Account_Number AS AccNumb FROM tbl_chartaccount A
								WHERE A.Acc_DirParent NOT IN (SELECT B.Account_Number FROM tbl_chartaccount B WHERE B.PRJCODE = '$SYNC_PRJ')
									AND A.PRJCODE = '$SYNC_PRJ' AND A.Account_Level > 1";
					$res_01 = $this->db->query($sql_01)->result();
					foreach($res_01 as $row_01) :
						$AccNumb	= $row_01->AccNumb;

						$sql_01A	= "UPDATE tbl_chartaccount SET ORD_ID = 8888888, Account_Level = 0
										WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01A);
						// 8888888 = damage account
					endforeach;

					$sql_01B	= "UPDATE tbl_chartaccount SET ORD_ID = 8888888, Account_Level = 0
									WHERE Account_Number = Acc_DirParent AND PRJCODE = '$SYNC_PRJ'";
					$this->db->query($sql_01B);
					// 8888888 = damage account

					$percent = intval(0)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

				    ob_flush();
				    flush();

			// 2. 	CEK APAKAH MEMILIKI HEADER, JIKA TIDAK, PASTI LEVEL 0
					$sql_02		= "UPDATE tbl_chartaccount SET Account_Level = 0 WHERE PRJCODE = '$SYNC_PRJ' AND Acc_DirParent = ''";
					$this->db->query($sql_02);

			// 3. 	RESET SUSUNAN COA
					$sql_03 	= "UPDATE tbl_chartaccount SET ORD_ID = 0 WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != '8888888'";
					$this->db->query($sql_03);

			// 4.	TAMBAHAN
					$sql_01X= "SELECT A.Account_Number AS AccNumb FROM tbl_chartaccount A
								WHERE A.Acc_DirParent NOT IN (SELECT B.Account_Number FROM tbl_chartaccount B WHERE B.PRJCODE = '$SYNC_PRJ')
									AND A.PRJCODE = '$SYNC_PRJ' AND A.Acc_DirParent != '' AND ORD_ID != '8888888'";
					$res_01X= $this->db->query($sql_01X)->result();
					foreach($res_01X as $row_01X) :
						$AccNumb	= $row_01X->AccNumb;

						$sql_01AX	= "UPDATE tbl_chartaccount SET Account_Level = 0 WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01AX);
					endforeach;

					$sql_01Y= "SELECT Account_Number AS AccNumb FROM tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND isLast = 0
								AND Account_Number NOT IN (SELECT B.Acc_DirParent FROM tbl_chartaccount B WHERE B.Acc_DirParent AND B.PRJCODE = '$SYNC_PRJ')";
					$res_01Y= $this->db->query($sql_01Y)->result();
					foreach($res_01Y as $row_01Y) :
						$AccNumb	= $row_01Y->AccNumb;

						$sql_01AY	= "UPDATE tbl_chartaccount SET ORD_ID = 8888888 WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01AY);
					endforeach;

					$percent = intval(0)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

				    ob_flush();
				    flush();
		// END : PREPARE PROCEDUR - RESET ORDER

		// START : PROSES PROCEDUR - RESET ORDER
			$percentN 	= 8;
			$ORD_ID		= 0;
			$sql_04		= "SELECT Account_Number, isLast FROM tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Account_Level = 0
								AND ORD_ID != '8888888'
							ORDER BY ORD_ID, Account_Number, Acc_ID";
			$res_04 	= $this->db->query($sql_04)->result();
			foreach($res_04 as $row_04) :
				$ORD_ID			= $ORD_ID+1;
				$Account_N03	= $row_04->Account_Number; // 1
				$isLast03		= $row_04->isLast;

				// CEK APAKAH MEMILIKI TURUNAN ATAU TIDAK
				$isLast03 		= 0;
				$sqlCHLD		= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Acc_DirParent = '$Account_N03'";
				$resCHLD		= $this->db->count_all($sqlCHLD);
				if($resCHLD == 0)
					$isLast03 	= 1;

				$sql_04RO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, isLast = $isLast03 WHERE Account_Number = '$Account_N03'
									AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($sql_04RO);

				$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
				$resORD			= $this->db->count_all($sqlORD);
				$percent 		= intval($resORD/$resCOAC * 100)."%";
				echo '<script>
				parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
				echo '<script>
				parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

			    ob_flush();
			    flush();

				if($isLast03 == 0)
				{
					$sql_04RO1 	= "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
										Base_Debet2 = 0, Base_Kredit2 = 0
									WHERE Account_Number = '$Account_N03' AND PRJCODE = '$SYNC_PRJ'";
					$this->db->query($sql_04RO1);

					$sql_04A	= "SELECT Account_Number, isLast FROM tbl_chartaccount
									WHERE Acc_DirParent = '$Account_N03' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";
					$res_04A	= $this->db->query($sql_04A)->result();

					foreach($res_04A as $row_04A):
						$ORD_ID			= $ORD_ID+1;
						$Account_N3A	= $row_04A->Account_Number;
						$isLast3A		= $row_04A->isLast;

						// CEK APAKAH MEMILIKI TURUNAN ATAU TIDAK
						$isLast3A 		= 0;
						$sqlCHLD		= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Acc_DirParent = '$Account_N3A'";
						$resCHLD		= $this->db->count_all($sqlCHLD);
						if($resCHLD == 0)
							$isLast3A 	= 1;
						
						$sql_04ARO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 1, isLast = $isLast3A
											WHERE Account_Number = '$Account_N3A' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_04ARO);

						$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
						$resORD			= $this->db->count_all($sqlORD);
						$percent 		= intval($resORD/$resCOAC * 100)."%";
						echo '<script>
						parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
						echo '<script>
						parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

					    ob_flush();
					    flush();

						if($isLast3A == 0)
						{
							$sql_04ARO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0, Base_Debet2 = 0, Base_Kredit2 = 0
											WHERE Account_Number = '$Account_N3A' AND PRJCODE = '$SYNC_PRJ'";
							$this->db->query($sql_04ARO1);

							$sql_04B	= "SELECT Account_Number, isLast FROM tbl_chartaccount
											WHERE Acc_DirParent = '$Account_N3A' AND PRJCODE = '$SYNC_PRJ'
											ORDER BY Account_Number";											
							$res_04B	= $this->db->query($sql_04B)->result();

							$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
							$resORD			= $this->db->count_all($sqlORD);
							$percent 		= intval($resORD/$resCOAC * 100)."%";
							echo '<script>
							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
							echo '<script>
							parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

						    ob_flush();
						    flush();

							foreach($res_04B as $row_04B):
								$ORD_ID			= $ORD_ID+1;
								$Account_N3B	= $row_04B->Account_Number;
								$isLast3B		= $row_04B->isLast;
								echo "$ORD_ID = $Account_N3B<br>";

								// CEK APAKAH MEMILIKI TURUNAN ATAU TIDAK
								$isLast3B 		= 0;
								$sqlCHLD		= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Acc_DirParent = '$Account_N3B'";
								$resCHLD		= $this->db->count_all($sqlCHLD);
								if($resCHLD == 0)
									$isLast3B 	= 1;
								
								$sql_04BRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 2, isLast = $isLast3B
													WHERE Account_Number = '$Account_N3B' AND PRJCODE = '$SYNC_PRJ'";
								$this->db->query($sql_04BRO);

								$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
								$resORD			= $this->db->count_all($sqlORD);
								$percent 		= intval($resORD/$resCOAC * 100)."%";
								echo '<script>
								parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
								echo '<script>
								parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

							    ob_flush();
							    flush();

								if($isLast3B == 0)
								{
									$sql_04BRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0, 
														Base_Debet2 = 0, Base_Kredit2 = 0
													WHERE Account_Number = '$Account_N3B' AND PRJCODE = '$SYNC_PRJ'";
									$this->db->query($sql_04BRO1);

									$sql_04C	= "SELECT Account_Number, isLast FROM tbl_chartaccount
													WHERE Acc_DirParent = '$Account_N3B' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";											
									$res_04C	= $this->db->query($sql_04C)->result();

									$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
									$resORD			= $this->db->count_all($sqlORD);
									$percent 		= intval($resORD/$resCOAC * 100)."%";
									echo '<script>
									parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
									echo '<script>
									parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

								    ob_flush();
								    flush();

									foreach($res_04C as $row_04C):
										$ORD_ID			= $ORD_ID+1;
										$Account_N3C	= $row_04C->Account_Number;
										$isLast3C		= $row_04C->isLast;
										echo "$ORD_ID = $Account_N3C<br>";

										// CEK APAKAH MEMILIKI TURUNAN ATAU TIDAK
										$isLast3C 		= 0;
										$sqlCHLD		= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Acc_DirParent = '$Account_N3C'";
										$resCHLD		= $this->db->count_all($sqlCHLD);
										if($resCHLD == 0)
											$isLast3C 	= 1;
										
										$sql_04CRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 3, isLast = $isLast3C
															WHERE Account_Number = '$Account_N3C' AND PRJCODE = '$SYNC_PRJ'";
										$this->db->query($sql_04CRO);

										$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
										$resORD			= $this->db->count_all($sqlORD);
										$percent 		= intval($resORD/$resCOAC * 100)."%";
										echo '<script>
										parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

										echo '<script>
										parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

									    ob_flush();
									    flush();

										if($isLast3C == 0)
										{
											$sql_04CRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
																Base_Debet2 = 0, Base_Kredit2 = 0
															WHERE Account_Number = '$Account_N3C' AND PRJCODE = '$SYNC_PRJ'";
											$this->db->query($sql_04CRO1);

											$sql_04D	= "SELECT Account_Number, isLast FROM tbl_chartaccount
															WHERE Acc_DirParent = '$Account_N3C' AND PRJCODE = '$SYNC_PRJ'
															ORDER BY Account_Number";											
											$res_04D	= $this->db->query($sql_04D)->result();

											$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
											$resORD			= $this->db->count_all($sqlORD);
											$percent 		= intval($resORD/$resCOAC * 100)."%";
											echo '<script>
											parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
											echo '<script>
											parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

										    ob_flush();
										    flush();

											foreach($res_04D as $row_04D):
												$ORD_ID			= $ORD_ID+1;
												$Account_N3D	= $row_04D->Account_Number;
												$isLast3D		= $row_04D->isLast;
												echo "$ORD_ID = $Account_N3D<br>";

												// CEK APAKAH MEMILIKI TURUNAN ATAU TIDAK
												$isLast3D 		= 0;
												$sqlCHLD		= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Acc_DirParent = '$Account_N3D'";
												$resCHLD		= $this->db->count_all($sqlCHLD);
												if($resCHLD == 0)
													$isLast3D 	= 1;
												
												$sql_04DRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 4, isLast = $isLast3D
																	WHERE Account_Number = '$Account_N3D' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($sql_04DRO);

												$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
												$resORD			= $this->db->count_all($sqlORD);
												$percent 		= intval($resORD/$resCOAC * 100)."%";
												echo '<script>
												parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
												echo '<script>
												parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

											    ob_flush();
											    flush();

												if($isLast3D == 0)
												{
													$sql_04DRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
																		Base_Debet2 = 0, Base_Kredit2 = 0
																	WHERE Account_Number = '$Account_N3D' AND PRJCODE = '$SYNC_PRJ'";
													$this->db->query($sql_04DRO1);

													$sql_04E	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																	WHERE Acc_DirParent = '$Account_N3D' AND PRJCODE = '$SYNC_PRJ'
																	ORDER BY Account_Number";											
													$res_04E	= $this->db->query($sql_04E)->result();

													$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
													$resORD			= $this->db->count_all($sqlORD);
													$percent 		= intval($resORD/$resCOAC * 100)."%";
													echo '<script>
													parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
													echo '<script>
													parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

												    ob_flush();
												    flush();
												    
													foreach($res_04E as $row_04E):
														$ORD_ID			= $ORD_ID+1;
														$Account_N3E	= $row_04E->Account_Number;
														$isLast3E		= $row_04E->isLast;
														echo "$ORD_ID = $Account_N3E<br>";

														// CEK APAKAH MEMILIKI TURUNAN ATAU TIDAK
														$isLast3E 		= 0;
														$sqlCHLD		= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Acc_DirParent = '$Account_N3E'";
														$resCHLD		= $this->db->count_all($sqlCHLD);
														if($resCHLD == 0)
															$isLast3E 	= 1;
														
														$sql_04ERO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 5, isLast = $isLast3E
																			WHERE Account_Number = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($sql_04ERO);

														$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
														$resORD			= $this->db->count_all($sqlORD);
														$percent 		= intval($resORD/$resCOAC * 100)."%";
														echo '<script>
														parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
														echo '<script>
														parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

													    ob_flush();
													    flush();
													    
														if($isLast3E == 0)
														{
															$sql_04ERO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
																				Base_Debet2 = 0, Base_Kredit2 = 0
																			WHERE Account_Number = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'";
															$this->db->query($sql_04ERO1);

															$sql_04F	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																			WHERE Acc_DirParent = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'
																			ORDER BY Account_Number";											
															$res_04F	= $this->db->query($sql_04F)->result();

															$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
															$resORD			= $this->db->count_all($sqlORD);
															$percent 		= intval($resORD/$resCOAC * 100)."%";
															echo '<script>
															parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
															echo '<script>
															parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

														    ob_flush();
														    flush();
														    
															foreach($res_04F as $row_04F):
																$ORD_ID			= $ORD_ID+1;
																$Account_N3F	= $row_04F->Account_Number;
																$isLast3F		= $row_04F->isLast;
																echo "$ORD_ID = $Account_N3F<br>";
										
																// CEK APAKAH MEMILIKI TURUNAN ATAU TIDAK
																$isLast3F 		= 0;
																$sqlCHLD		= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ'
																						AND Acc_DirParent = '$Account_N3F'";
																$resCHLD		= $this->db->count_all($sqlCHLD);
																if($resCHLD == 0)
																	$isLast3F 	= 1;
																
																$sql_04FRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 6,
																						isLast = $isLast3F
																					WHERE Account_Number = '$Account_N3F' AND PRJCODE = '$SYNC_PRJ'";
																$this->db->query($sql_04FRO);

																$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
																$resORD			= $this->db->count_all($sqlORD);
																$percent 		= intval($resORD/$resCOAC * 100)."%";
																echo '<script>
																parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
																echo '<script>
																parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

															    ob_flush();
															    flush();
															    
																if($isLast3F == 0)
																{
																	$sql_04FRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
																						Base_Debet2 = 0, Base_Kredit2 = 0
																					WHERE Account_Number = '$Account_N3F' AND PRJCODE = '$SYNC_PRJ'";
																	$this->db->query($sql_04FRO1);
																	
																	$sql_04G	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																					WHERE Acc_DirParent = '$Account_N3F' AND PRJCODE = '$SYNC_PRJ'
																					ORDER BY Account_Number";											
																	$res_04G	= $this->db->query($sql_04G)->result();
																	foreach($res_04G as $row_04G):
																		$ORD_ID			= $ORD_ID+1;
																		$Account_N3G	= $row_04G->Account_Number;
																		$isLast3G		= $row_04G->isLast;
																		//echo "$ORD_ID = $Account_N3G<br>";
																		
																		$sql_04GRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 8
																							WHERE Account_Number = '$Account_N3G' AND PRJCODE = '$SYNC_PRJ'";
																		$this->db->query($sql_04GRO);
																	endforeach;

																	$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
																	$resORD			= $this->db->count_all($sqlORD);
																	$percent 		= intval($resORD/$resCOAC * 100)."%";
																	echo '<script>
																	parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';
																	echo '<script>
																	parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

																    ob_flush();
																    flush();
																    
																}
															endforeach;
														}
													endforeach;
												}
											endforeach;
										}
									endforeach;
								}
							endforeach;
						}
					endforeach;
				}
			endforeach;

			$sql_05	= "SELECT Account_Number, isLast FROM tbl_chartaccount WHERE ORD_ID = '8888888' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";
			$res_05	= $this->db->query($sql_05)->result();
			foreach($res_05 as $row_05):
				$Account_N5	= $row_05->Account_Number;
				$isLast5	= $row_05->isLast;
				if($isLast5 == 0)
				{
					$sql_05A = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
										Base_Debet2 = 0, Base_Kredit2 = 0, COGSReportID = ''
									WHERE Account_Number = '$Account_N5' AND PRJCODE = '$SYNC_PRJ'";
					$this->db->query($sql_05A);
				}
			endforeach;

			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'REODERSYNC')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');

		$SYNC_PRJ	= $PRJCODE;

		$sql_0		= "UPDATE tbl_chartaccount SET syncPRJ = '' WHERE PRJCODE = '$SYNC_PRJ'";
		$this->db->query($sql_0);

		$sqlCOAC	= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ'";
		$resCOAC	= $this->db->count_all($sqlCOAC);

		$PRJCODE_HO	= '';
		$sql_PRJHO	= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$SYNC_PRJ'";
		$res_PRJHO 	= $this->db->query($sql_PRJHO)->result();
		foreach($res_PRJHO as $row_PRJHO) :
			$PRJCODE_HO	= $row_PRJHO->PRJCODE_HO;
		endforeach;
		$syncPRJ 	= "$SYNC_PRJ~$PRJCODE_HO";
		
		// START : PROCEDUR - RESET ORDER
			$sql_01	= "SELECT A.Account_Number AS AccNumb FROM tbl_chartaccount A WHERE PRJCODE = '$SYNC_PRJ'";
			$res_01 = $this->db->query($sql_01)->result();
			foreach($res_01 as $row_01) :
				$AccNumb	= $row_01->AccNumb;

				$sql_01A	= "UPDATE tbl_chartaccount SET syncPRJ = '$syncPRJ'
								WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($sql_01A);

				$sqlORD			= "tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND syncPRJ != ''";
				$resORD			= $this->db->count_all($sqlORD);
				$percent 		= intval($resORD/$resCOAC * 100)."%";
				echo '<script>
				parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resORD. ' / '.$resCOAC.') processed</span></div>";</script>';

			    ob_flush();
			    flush();
			endforeach;
		// END : PROCEDUR - RESET ORDER

		$percent = intval(100)."%";
		echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
		echo '<script>
		    parent.document.getElementById("loading_1").style.display ="none";
			    parent.updStat();</script>';
	}
	else if($IMP_TYPE == 'REODERBOQ')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');

		$SYNC_PRJ	= $PRJCODE;

		// PEMBERSIHAN DATA TIDAK BERGUNA
			$sql1	= "DELETE FROM tbl_boqlist WHERE JOBCODEID = ''";
			$this->db->query($sql1);

            $sql1	= "DELETE FROM tbl_boqlist_temp WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql1);
			
			$sql1	= "DELETE FROM tbl_joblist WHERE JOBCODEID = ''";
			$this->db->query($sql1);
			
			$sql1	= "DELETE FROM tbl_joblist_detail WHERE JOBCODEID = ''";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_boqlist SET ORD_ID = 8888888, BOQ_STAT = 0 WHERE JOBPARENT = '' AND JOBLEV != 1";
			$this->db->query($sql2);
			
			$sql2	= "UPDATE tbl_joblist SET ORD_ID = 8888888, WBS_STAT = 0 WHERE JOBPARENT = '' AND JOBLEV != 1";
			$this->db->query($sql2);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ORD_ID = 8888888, WBSD_STAT = 0 WHERE JOBPARENT = '' AND IS_LEVEL != 1";
			$this->db->query($sql2);

			$percent = intval(0)."%";
			echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';
			echo '<script>
		    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

		    ob_flush();
		    flush();

		    // RESET ORDER
				$sql_0		= "UPDATE tbl_boqlist SET ORD_ID = 0 WHERE PRJCODE = '$SYNC_PRJ'";
				$this->db->query($sql_0);

				$sql_0		= "UPDATE tbl_joblist SET ORD_ID = 0 WHERE PRJCODE = '$SYNC_PRJ'";
				$this->db->query($sql_0);

				$sql_0		= "UPDATE tbl_joblist_detail SET ORD_ID = 0 WHERE PRJCODE = '$SYNC_PRJ'";
				$this->db->query($sql_0);

		    // TOTAL BARIS HEADER
				//$sqlJLT		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID = 0";
				$sqlJLT		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ'";
				$resJLT		= $this->db->count_all($sqlJLT);

		// START : PROCEDUR - RESET ORDER
			// 1.	MENAMPILKAN SEMUA LEVEL 1. MINIMAL LEVEL 1 STRUKTURNYA HARUS / WAJIB BENAR. MEMANGGIL DARI TBL_JOBLIST AGAR HEADER SAJA
				$ORR_ID	= 0;
				$sql_01	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE IS_LEVEL = 1 AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
				$res_01 = $this->db->query($sql_01)->result();
				foreach($res_01 as $row_01) :
					$ORR_ID		= $ORR_ID + 1;
					$JOBC_01	= $row_01->JOBCODEID;
					$ORD_01		= $row_01->ORD_ID;

					if($ORD_01 != $ORR_ID)
					{
						$sql_01A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID WHERE JOBCODEID = '$JOBC_01' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01A);

						$sql_01A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID WHERE JOBCODEID = '$JOBC_01' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01A);

						$sql_01A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID WHERE JOBCODEID = '$JOBC_01' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01A);
					}

					$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
					$resORD		= $this->db->count_all($sqlORD);
					$percent 	= intval($ORR_ID/$resJLT * 100)."%";
					echo '<script>
					parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
					echo '<script>
					parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

				    ob_flush();
				    flush();

				    // LEVEL _02 PER HEADER
				    $sql_02	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_01' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
					$res_02 = $this->db->query($sql_02)->result();
					foreach($res_02 as $row_02) :
						$ORR_ID		= $ORR_ID + 1;
						$JOBC_02	= $row_02->JOBCODEID;
						$ORD_02		= $row_02->ORD_ID;

						if($ORD_02 != $ORR_ID)
						{
							$sql_02A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 2 WHERE JOBCODEID = '$JOBC_02' AND PRJCODE = '$SYNC_PRJ'";
							$this->db->query($sql_02A);

							$sql_02A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 2 WHERE JOBCODEID = '$JOBC_02' AND PRJCODE = '$SYNC_PRJ'";
							$this->db->query($sql_02A);

							$sql_02A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 2 WHERE JOBCODEID = '$JOBC_02' AND PRJCODE = '$SYNC_PRJ'";
							$this->db->query($sql_02A);
						}

						$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
						$resORD		= $this->db->count_all($sqlORD);
						$percent 	= intval($ORR_ID/$resJLT * 100)."%";
						echo '<script>
						parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
						echo '<script>
						parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

					    ob_flush();
					    flush();

					    // LEVEL _03 PER HEADER
					    $sql_03	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_02' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
						$res_03 = $this->db->query($sql_03)->result();
						foreach($res_03 as $row_03) :
							$ORR_ID		= $ORR_ID + 1;
							$JOBC_03	= $row_03->JOBCODEID;
							$ORD_03		= $row_03->ORD_ID;

							if($ORD_03 != $ORR_ID)
							{
								$sql_03A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 3 WHERE JOBCODEID = '$JOBC_03' AND PRJCODE = '$SYNC_PRJ'";
								$this->db->query($sql_03A);

								$sql_03A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 3 WHERE JOBCODEID = '$JOBC_03' AND PRJCODE = '$SYNC_PRJ'";
								$this->db->query($sql_03A);

								$sql_03A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 3 WHERE JOBCODEID = '$JOBC_03' AND PRJCODE = '$SYNC_PRJ'";
								$this->db->query($sql_03A);
							}

							$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
							$resORD		= $this->db->count_all($sqlORD);
							$percent 	= intval($ORR_ID/$resJLT * 100)."%";
							echo '<script>
							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
							echo '<script>
							parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

						    ob_flush();
						    flush();

						    // LEVEL _04 PER HEADER
						    $sql_04	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_03' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
							$res_04 = $this->db->query($sql_04)->result();
							foreach($res_04 as $row_04) :
								$ORR_ID		= $ORR_ID + 1;
								$JOBC_04	= $row_04->JOBCODEID;
								$ORD_04		= $row_04->ORD_ID;

								if($ORD_04 != $ORR_ID)
								{
									$sql_04A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 4 WHERE JOBCODEID = '$JOBC_04' AND PRJCODE = '$SYNC_PRJ'";
									$this->db->query($sql_04A);

									$sql_04A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 4 WHERE JOBCODEID = '$JOBC_04' AND PRJCODE = '$SYNC_PRJ'";
									$this->db->query($sql_04A);

									$sql_04A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 4 WHERE JOBCODEID = '$JOBC_04' AND PRJCODE = '$SYNC_PRJ'";
									$this->db->query($sql_04A);
								}

								$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
								$resORD		= $this->db->count_all($sqlORD);
								$percent 	= intval($ORR_ID/$resJLT * 100)."%";
								echo '<script>
								parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
								echo '<script>
								parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

							    ob_flush();
							    flush();

							    // LEVEL _05 PER HEADER
							    $sql_05	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_04' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
								$res_05 = $this->db->query($sql_05)->result();
								foreach($res_05 as $row_05) :
									$ORR_ID		= $ORR_ID + 1;
									$JOBC_05	= $row_05->JOBCODEID;
									$ORD_05		= $row_05->ORD_ID;

									if($ORD_05 != $ORR_ID)
									{
										$sql_05A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 5 WHERE JOBCODEID = '$JOBC_05' AND PRJCODE = '$SYNC_PRJ'";
										$this->db->query($sql_05A);

										$sql_05A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 5 WHERE JOBCODEID = '$JOBC_05' AND PRJCODE = '$SYNC_PRJ'";
										$this->db->query($sql_05A);

										$sql_05A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 5 WHERE JOBCODEID = '$JOBC_05' AND PRJCODE = '$SYNC_PRJ'";
										$this->db->query($sql_05A);
									}

									$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
									$resORD		= $this->db->count_all($sqlORD);
									$percent 	= intval($ORR_ID/$resJLT * 100)."%";
									echo '<script>
									parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
									echo '<script>
									parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

								    ob_flush();
								    flush();

								    // LEVEL _06 PER HEADER
								    $sql_06	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_05' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
									$res_06 = $this->db->query($sql_06)->result();
									foreach($res_06 as $row_06) :
										$ORR_ID		= $ORR_ID + 1;
										$JOBC_06	= $row_06->JOBCODEID;
										$ORD_06		= $row_06->ORD_ID;

										if($ORD_06 != $ORR_ID)
										{
											$sql_06A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 6 WHERE JOBCODEID = '$JOBC_06' AND PRJCODE = '$SYNC_PRJ'";
											$this->db->query($sql_06A);

											$sql_06A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 6 WHERE JOBCODEID = '$JOBC_06' AND PRJCODE = '$SYNC_PRJ'";
											$this->db->query($sql_06A);

											$sql_06A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 6 WHERE JOBCODEID = '$JOBC_06' AND PRJCODE = '$SYNC_PRJ'";
											$this->db->query($sql_06A);
										}

										$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
										$resORD		= $this->db->count_all($sqlORD);
										$percent 	= intval($ORR_ID/$resJLT * 100)."%";
										echo '<script>
										parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
										echo '<script>
										parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

									    ob_flush();
									    flush();

									    // LEVEL _08 PER HEADER
									    $sql_08	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_06' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
										$res_08 = $this->db->query($sql_08)->result();
										foreach($res_08 as $row_08) :
											$ORR_ID		= $ORR_ID + 1;
											$JOBC_08	= $row_08->JOBCODEID;
											$ORD_08		= $row_08->ORD_ID;

											if($ORD_08 != $ORR_ID)
											{
												$sql_08A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($sql_08A);

												$sql_08A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($sql_08A);

												$sql_08A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($sql_08A);
											}

											$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
											$resORD		= $this->db->count_all($sqlORD);
											$percent 	= intval($ORR_ID/$resJLT * 100)."%";
											echo '<script>
											parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
											echo '<script>
											parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

										    ob_flush();
										    flush();

										    // LEVEL _08 PER HEADER
										    $sql_08	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
											$res_08 = $this->db->query($sql_08)->result();
											foreach($res_08 as $row_08) :
												$ORR_ID		= $ORR_ID + 1;
												$JOBC_08	= $row_08->JOBCODEID;
												$ORD_08		= $row_08->ORD_ID;

												if($ORD_08 != $ORR_ID)
												{
													$sql_08A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
													$this->db->query($sql_08A);

													$sql_08A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
													$this->db->query($sql_08A);

													$sql_08A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
													$this->db->query($sql_08A);
												}

												$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
												$resORD		= $this->db->count_all($sqlORD);
												$percent 	= intval($ORR_ID/$resJLT * 100)."%";
												echo '<script>
												parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
												echo '<script>
												parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

											    ob_flush();
											    flush();

											    // LEVEL _08 PER HEADER
											    $sql_08	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
												$res_08 = $this->db->query($sql_08)->result();
												foreach($res_08 as $row_08) :
													$ORR_ID		= $ORR_ID + 1;
													$JOBC_08	= $row_08->JOBCODEID;
													$ORD_08		= $row_08->ORD_ID;

													if($ORD_08 != $ORR_ID)
													{
														$sql_08A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($sql_08A);

														$sql_08A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($sql_08A);

														$sql_08A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 8 WHERE JOBCODEID = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($sql_08A);
													}

													$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
													$resORD		= $this->db->count_all($sqlORD);
													$percent 	= intval($ORR_ID/$resJLT * 100)."%";
													echo '<script>
													parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
													echo '<script>
													parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

												    ob_flush();
												    flush();

												    // LEVEL _10 PER HEADER
												    $sql_10	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
													$res_10 = $this->db->query($sql_10)->result();
													foreach($res_10 as $row_10) :
														$ORR_ID		= $ORR_ID + 1;
														$JOBC_10	= $row_10->JOBCODEID;
														$ORD_10		= $row_10->ORD_ID;

														if($ORD_10 != $ORR_ID)
														{
															$sql_10A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 10 WHERE JOBCODEID = '$JOBC_10' AND PRJCODE = '$SYNC_PRJ'";
															$this->db->query($sql_10A);

															$sql_10A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 10 WHERE JOBCODEID = '$JOBC_10' AND PRJCODE = '$SYNC_PRJ'";
															$this->db->query($sql_10A);

															$sql_10A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 10 WHERE JOBCODEID = '$JOBC_10' AND PRJCODE = '$SYNC_PRJ'";
															$this->db->query($sql_10A);
														}

														$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
														$resORD		= $this->db->count_all($sqlORD);
														$percent 	= intval($ORR_ID/$resJLT * 100)."%";
														echo '<script>
														parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
														echo '<script>
														parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

													    ob_flush();
													    flush();

													    // LEVEL _11 PER HEADER
													    $sql_11	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_10' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
														$res_11 = $this->db->query($sql_11)->result();
														foreach($res_11 as $row_11) :
															$ORR_ID		= $ORR_ID + 1;
															$JOBC_11	= $row_11->JOBCODEID;
															$ORD_11		= $row_11->ORD_ID;

															if($ORD_11 != $ORR_ID)
															{
																$sql_11A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 11 WHERE JOBCODEID = '$JOBC_11' AND PRJCODE = '$SYNC_PRJ'";
																$this->db->query($sql_11A);

																$sql_11A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 11 WHERE JOBCODEID = '$JOBC_11' AND PRJCODE = '$SYNC_PRJ'";
																$this->db->query($sql_11A);

																$sql_11A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 11 WHERE JOBCODEID = '$JOBC_11' AND PRJCODE = '$SYNC_PRJ'";
																$this->db->query($sql_11A);
															}

															$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
															$resORD		= $this->db->count_all($sqlORD);
															$percent 	= intval($ORR_ID/$resJLT * 100)."%";
															echo '<script>
															parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
															echo '<script>
															parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

														    ob_flush();
														    flush();

														    // LEVEL _12 PER HEADER
														    $sql_12	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_11' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
															$res_12 = $this->db->query($sql_12)->result();
															foreach($res_12 as $row_12) :
																$ORR_ID		= $ORR_ID + 1;
																$JOBC_12	= $row_12->JOBCODEID;
																$ORD_12		= $row_12->ORD_ID;

																if($ORD_12 != $ORR_ID)
																{
																	$sql_12A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 12 WHERE JOBCODEID = '$JOBC_12' AND PRJCODE = '$SYNC_PRJ'";
																	$this->db->query($sql_12A);

																	$sql_12A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 12 WHERE JOBCODEID = '$JOBC_12' AND PRJCODE = '$SYNC_PRJ'";
																	$this->db->query($sql_12A);

																	$sql_12A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 12 WHERE JOBCODEID = '$JOBC_12' AND PRJCODE = '$SYNC_PRJ'";
																	$this->db->query($sql_12A);
																}

																$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
																$resORD		= $this->db->count_all($sqlORD);
																$percent 	= intval($ORR_ID/$resJLT * 100)."%";
																echo '<script>
																parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';
																echo '<script>
																parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

															    ob_flush();
															    flush();
															endforeach;
														endforeach;
													endforeach;
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// 3.	FINISHING INFO
				/*$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Finishing ...</span></div>";</script>';*/

			// 2.	UPDATE IS HEADER OR IS LAST
				/*$sql_ISHD	= "UPDATE tbl_joblist_detail SET ISLAST = 0 WHERE PRJCODE = '$SYNC_PRJ'
								AND JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ')";
				$this->db->query($sql_ISHD);

				$sql_ISD	= "UPDATE tbl_joblist_detail SET ISLAST = 1 WHERE PRJCODE = '$SYNC_PRJ'
								AND JOBCODEID NOT IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ')";
				$this->db->query($sql_ISD);*/

			// 3.	COMPLETE INFO
				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
					    parent.updStat();parent.clsBarX();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'RESETWBD')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];

		$PRJNAME 	= '';
		$sqlPRJNM	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPRJNM	= $this->db->query($sqlPRJNM)->result();
		foreach($resPRJNM as $rowPRJNM) :
			$PRJNAME= $rowPRJNM->PRJNAME;
		endforeach;

	    $percent = intval(0)."%";
		echo '<script>parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

	    ob_flush();
	    flush();

		// UPDATE Journal Header
		$resGEJH 	= "UPDATE tbl_journaldetail A, tbl_journalheader B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date, A.JournalType = B.JournalType
						WHERE A.JournalH_Code = B.JournalH_Code";
		$this->db->query($resGEJH);

		$s_TOTJD	= "tbl_journaldetail A
						WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = '3' AND A.ITM_CODE != '' AND JournalType IN ('VCASH', 'CPRJ', 'CHO', 'OPN', 'UM')
						AND A.isSyncY = '0'";
		$r_TOTJD	= $this->db->count_all($s_TOTJD);

		// START : RESET SELURUH PENGGUNAAN JOBLIS_DETAIL
			/*$updJLD = "UPDATE tbl_joblist_detail SET OPN_QTY=0, OPN_AMOUNT=0, ITM_USED=0, ITM_USED_AM=0
						WHERE PRJCODE = '$PRJCODE'";*/
			$updJLD = "UPDATE tbl_joblist_detail SET OPN_QTY = 0, OPN_AMOUNT = 0, ITM_USED = 0, ITM_USED_AM = 0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updJLD);

			$updJLD = "UPDATE tbl_profitloss SET BPP_UPH_REAL = 0, BPP_SUBK_REAL = 0, BPP_ALAT_REAL = 0, BPP_I_REAL = 0, BPP_OTH_REAL = 0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updJLD);

			// GET LAST BALANCE SAMPAI BULAN SEBELUM BULAN SAAT INI
				$C_YEAR 		= date('Y');
				$C_MONTH 		= date('m');
				$LS_YEAR 		= date('Y');
				$LS_MONTH 		= date('m', strtotime('first day of last month'));
				if($LS_MONTH == 12)
					$LS_YEAR	= $LS_YEAR-1;

			// RESET COA
				$resCOA	= "UPDATE tbl_chartaccount SET Base_Debet = 0, Base_Debet2 = 0, BaseD_$C_YEAR = 0,
								Base_Kredit = 0, Base_Kredit2 = 0, BaseK_$C_YEAR = 0";
				$this->db->query($resCOA);

			// START : BREAK DOWN ALLL JOURNAL BY ACC_ID
				$s_ACCID 		= "SELECT DISTINCT Acc_Id FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE' AND GEJ_STAT = 3 AND isSyncY = 0";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID):
					$AccId		= $rw_ACCID->Acc_Id;
					$LSDEBET	= 0;
					$LSKREDIT	= 0;
					$s_LSYNC 	= "SELECT LS_DEBET, LS_KREDIT FROM tbl_lastsync
										WHERE PRJCODE = '$PRJCODE' AND LS_YEAR = '$LS_YEAR' AND LS_MONTH = '$LS_MONTH' AND LS_ACC_ID = '$AccId'";
					$r_LSYNC		= $this->db->query($s_LSYNC)->result();
					foreach($r_LSYNC as $rw_LSYNC):
						$LSDEBET	= $rw_LSYNC->LS_DEBET;
						$LSKREDIT	= $rw_LSYNC->LS_KREDIT;

						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId' LIMIT 1";
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
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = $LSDEBET, Base_Debet2 = $LSDEBET, BaseD_$accYr = $LSDEBET,
													Base_Kredit = $LSKREDIT, Base_Kredit2 = $LSKREDIT, BaseK_$accYr = $LSKREDIT
													WHERE PRJCODE = '$PRJCODE' AND LS_YEAR = '$C_YEAR' AND LS_MONTH = '$C_MONTH' AND LS_ACC_ID = '$AccId'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					endforeach;
				endforeach;
			// END : BREAK DOWN ALLL JOURNAL BY ACC_ID

			$rowRes 	= 0;
			$sqlJDet	= "SELECT A.JournalD_Id, A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.JOBCODEID, A.ITM_CODE, A.ITM_CATEG,
								A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.Base_Debet, A.Base_Kredit
							FROM tbl_journaldetail A
							WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND A.ITM_CODE != '' AND JournalType IN ('VCASH', 'CPRJ', 'CHO', 'OPN', 'UM')
							AND A.isSyncY = '0'";
			$resJDet	= $this->db->query($sqlJDet)->result();
			foreach($resJDet as $rowJDet) :
				$rowRes 		= $rowRes+1;
				$JournalD_Id	= $rowJDet->JournalD_Id;
				$JournalH_Code	= $rowJDet->JournalH_Code;
				$JournalH_Date	= $rowJDet->JournalH_Date;
				$PERIODM		= date('m', strtotime($JournalH_Date));
				$PERIODY		= date('Y', strtotime($JournalH_Date));
				$accYr			= date('Y', strtotime($JournalH_Date));
				$ACC_NUM		= $rowJDet->Acc_Id;
				$JOBCODEID		= $rowJDet->JOBCODEID;
				$ITM_CODE		= $rowJDet->ITM_CODE;
				$ITM_GROUP 		= $rowJDet->ITM_CATEG;
				$ITM_VOLM		= $rowJDet->ITM_VOLM;
				$ITM_PRICE		= $rowJDet->ITM_PRICE;
				$Base_Debet		= $rowJDet->Base_Debet;
				$Base_Kredit	= $rowJDet->Base_Kredit;
				$JournalType	= $rowJDet->JournalType;
				$ITM_UNIT		= strtoupper($rowJDet->ITM_UNIT); 

				/*$up_00 		= "UPDATE tbl_journaldetail SET isSyncY = 1 WHERE proj_Code = '$PRJCODE'
									AND JournalD_Id = '$JournalD_Id' AND Acc_Id = '$ACC_NUM'";*/
				$up_00 			= "UPDATE tbl_journaldetail SET isSyncY = 1 WHERE JournalD_Id = '$JournalD_Id'";
				$this->db->query($up_00);

				if($JournalType == 'OPN')
				{
					// UPDATE KE PEKERJAAN
						$up_01 		= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED+$ITM_VOLM, ITM_USED_AM = ITM_USED_AM+$Base_Debet-$Base_Kredit,
											OPN_QTY = OPN_QTY+$ITM_VOLM, OPN_AMOUNT = OPN_AMOUNT+$Base_Debet
										WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
						$this->db->query($up_01);
				}
				else
				{
					if(strtoupper($ITM_UNIT) == 'LS')
					{
						// UPDATE KE PEKERJAAN
							$up_01 		= "UPDATE tbl_joblist_detail SET ITM_USED = 1, ITM_USED_AM = ITM_USED_AM+$Base_Debet-$Base_Kredit
											WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($up_01);
					}
					else
					{
						// UPDATE KE PEKERJAAN
							$up_01 		= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED+$ITM_VOLM, ITM_USED_AM = ITM_USED_AM+$Base_Debet-$Base_Kredit
											WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($up_01);
					}
				}

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
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet,
												Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOA);

							$s_00 		= "tbl_lastsync WHERE PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$ACC_NUM'
											AND LS_YEAR = '$PERIODY' AND LS_MONTH = '$PERIODM'";
							$r_00 		= $this->db->count_all($s_00);
							if($r_00 == 0)
							{
								$s_ins 	= "INSERT INTO tbl_lastsync (LS_YEAR, LS_MONTH, PRJCODE, LS_ACC_ID, LS_DEBET, LS_KREDIT)
											VALUES ('$PERIODY', '$PERIODM', '$SYNC_PRJ', '$ACC_NUM', $Base_Debet, 0)";
								$this->db->query($s_ins);
							}
							else
							{
								$s_ins 	= "UPDATE tbl_lastsync SET LS_DEBET = LS_DEBET+$Base_Debet
											WHERE PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$ACC_NUM' AND LS_YEAR = '$PERIODY' AND LS_MONTH = '$PERIODM'";
								$this->db->query($s_ins);
							}
						}
					}
				// END : Update to COA - Debit

				// START : UPDATE L/R
					if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'U')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'SC')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'I')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'O')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'GE')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}

					if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
					{
						// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
						if($ITM_TYPE == 1)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$Base_Debet 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$Base_Debet 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$Base_Debet
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 9)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$Base_Debet
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 10)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$Base_Debet
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
					}
					elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				// END : UPDATE L/R

				// START : SENT PROGRESS BAR
					$percent 	= intval($rowRes/$r_TOTJD * 100)."%";
					echo '<script>
					parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rowRes. ' / '.$r_TOTJD.') processed (Step 1 / 2)</span></div>";</script>';

				    ob_flush();
				    flush();
				// END : SENT PROGRESS BAR
			endforeach;
		// END : RESET SELURUH PENGGUNAAN JOBLIS_DETAIL

		// START : RESET OTHER JOURNAL
		    $percent = intval(0)."%";
			echo '<script>parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

		    ob_flush();
		    flush();

			$s_TOTJD	= "tbl_journaldetail WHERE GEJ_STAT = '3' AND proj_Code = '$PRJCODE' AND isSyncY = '0'";
			$r_TOTJD	= $this->db->count_all($s_TOTJD);

			$rowRes 	= 0;
			$sqlJDet	= "SELECT A.JournalD_Id, A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.Base_Debet, A.Base_Kredit
							FROM tbl_journaldetail A
							WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND A.isSyncY = 0";
			$resJDet	= $this->db->query($sqlJDet)->result();
			foreach($resJDet as $rowJDet) :
				$rowRes 		= $rowRes+1;
				$JournalD_Id	= $rowJDet->JournalD_Id;
				$JournalH_Code	= $rowJDet->JournalH_Code;
				$JournalH_Date	= $rowJDet->JournalH_Date;
				$PERIODM		= date('m', strtotime($JournalH_Date));
				$PERIODY		= date('Y', strtotime($JournalH_Date));
				$accYr			= date('Y', strtotime($JournalH_Date));
				$ACC_NUM		= $rowJDet->Acc_Id;
				$Base_Debet		= $rowJDet->Base_Debet;
				$Base_Kredit	= $rowJDet->Base_Kredit;

				/*$up_00 			= "UPDATE tbl_journaldetail SET isSyncY = 1 WHERE proj_Code = '$PRJCODE'
									AND JournalH_Code = '$JournalH_Code' AND Acc_Id = '$ACC_NUM'";*/
				$up_00 			= "UPDATE tbl_journaldetail SET isSyncY = 1 WHERE JournalD_Id = '$JournalD_Id'";
				$this->db->query($up_00);

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
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet,
												Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet,
												Base_Kredit = Base_Kredit+$Base_Kredit, 
												Base_Kredit2 = Base_Kredit2+$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Kredit
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOA);

							$s_00 		= "tbl_lastsync WHERE PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$ACC_NUM'
											AND LS_YEAR = '$PERIODY' AND LS_MONTH = '$PERIODM'";
							$r_00 		= $this->db->count_all($s_00);
							if($r_00 == 0)
							{
								$s_ins 	= "INSERT INTO tbl_lastsync (LS_YEAR, LS_MONTH, PRJCODE, LS_ACC_ID, LS_DEBET, LS_KREDIT)
											VALUES ('$PERIODY', '$PERIODM', '$SYNC_PRJ', '$ACC_NUM', $Base_Debet, $Base_Kredit)";
								$this->db->query($s_ins);
							}
							else
							{
								$s_ins 	= "UPDATE tbl_lastsync SET LS_DEBET = LS_DEBET+$Base_Debet,  LS_KREDIT = LS_KREDIT+$Base_Kredit
											WHERE PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$ACC_NUM' AND LS_YEAR = '$PERIODY' AND LS_MONTH = '$PERIODM'";
								$this->db->query($s_ins);
							}
						}
					}
				// END : Update to COA - Debit

				// START : SENT PROGRESS BAR
					$percent 	= intval($rowRes/$r_TOTJD * 100)."%";
					echo '<script>
					parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rowRes. ' / '.$r_TOTJD.') processed (Step 2 / 2)</span></div>";</script>';

				    ob_flush();
				    flush();
				// END : SENT PROGRESS BAR
			endforeach;
		// END : RESET OTHER JOURNAL

		// 3.	COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'RECOUNTJRN_220526')	// READY TO ADD ON
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];

		$PRJNAME 	= '';
		$sqlPRJNM	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPRJNM	= $this->db->query($sqlPRJNM)->result();
		foreach($resPRJNM as $rowPRJNM) :
			$PRJNAME= $rowPRJNM->PRJNAME;
		endforeach;

	    $percent = intval(0)."%";
		echo '<script>parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';
		echo '<script>parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

	    ob_flush();
	    flush();

		// UPDATE Journal Header
		$resGEJH 	= "UPDATE tbl_journaldetail A, tbl_journalheader B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date, A.JournalType = B.JournalType
						WHERE A.JournalH_Code = B.JournalH_Code";
		$this->db->query($resGEJH);

		// RESET SELURUH PENGGUNAAN JOBLISDETAIL
			/*$updJLD = "UPDATE tbl_joblist_detail SET OPN_QTY=0, OPN_AMOUNT=0, ITM_USED=0, ITM_USED_AM=0
						WHERE PRJCODE = '$PRJCODE'";*/
			$updJLD = "UPDATE tbl_joblist_detail SET ADD_VOLM=0, ADD_PRICE=0, ADD_JOBCOST=0, ADDM_VOLM=0, ADDM_JOBCOST=0,
							REQ_VOLM=0, REQ_AMOUNT=0, PO_VOLM=0, PO_AMOUNT=0, IR_VOLM=0, IR_AMOUNT=0,
							WO_QTY=0, WO_AMOUNT=0, OPN_QTY=0, OPN_AMOUNT=0, ITM_USED=0, ITM_USED_AM=0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updJLD);

			$updJLD = "UPDATE tbl_profitloss SET BPP_MTR_REAL = 0, BPP_UPH_REAL = 0, BPP_SUBK_REAL = 0,
							BPP_ALAT_REAL = 0, BPP_I_REAL = 0, BPP_I_REAL = 0
						WHERE PRJCODE = '$PRJCODE' AND LR_STAT = 0";
			$this->db->query($updJLD);

		// START 	: RESET STATUS
			$SUP_00		= "UPDATE tbl_journaldetail A, tbl_journalheader B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);

			$SUP_00		= "UPDATE tbl_journaldetail_pd A, tbl_journalheader_pd B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);

			$SUP_00		= "UPDATE tbl_journaldetail_cprj A, tbl_journalheader_cprj B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);

			$SUP_00		= "UPDATE tbl_journaldetail_pd A, tbl_journalheader_pd B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);
			
			$SUP_00		= "UPDATE tbl_journaldetail_vcash A, tbl_journalheader_vcash B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);
			
			$SUP_00		= "UPDATE tbl_pr_detail A, tbl_pr_header B
							SET A.PR_STAT = B.PR_STAT, A.PR_DATE = B.PR_DATE WHERE A.PR_NUM = B.PR_NUM";
			$this->db->query($SUP_00);
			
			$SUP_00		= "UPDATE tbl_po_detail A, tbl_po_header B
							SET A.PO_STAT = B.PO_STAT WHERE A.PO_NUM = B.PO_NUM";
			$this->db->query($SUP_00);
		// END 		: RESET STATUS

		// START 	: RE-COUNT REQ
			$SPR_00C	= "tbl_pr_detail WHERE PR_STAT IN (3,6) AND PRJCODE = '$PRJCODE'";
			$RPR_00C 	= $this->db->count_all($SPR_00C);

			$SPR_01C	= "tbl_journaldetail_vcash WHERE GEJ_STAT = 3 AND proj_Code = '$PRJCODE'";
			$RPR_01C 	= $this->db->count_all($SPR_01C);

			$SPR_02C	= "tbl_journaldetail_pd WHERE GEJ_STAT = 3 AND ISPERSL_REALIZ = '1' AND proj_Code = '$PRJCODE'";
			$RPR_02C 	= $this->db->count_all($SPR_02C);

			$SPR_03C	= "tbl_journaldetail_cprj WHERE GEJ_STAT = 3 AND proj_Code = '$PRJCODE'";
			$RPR_03C 	= $this->db->count_all($SPR_03C);

			$TOTPR 		= $RPR_00C+$RPR_01C+$RPR_02C+$RPR_03C;

			$PR_RW 		= 0;
			$SPR_00 	= "SELECT JOBCODEID, ITM_CODE, ITM_UNIT, PR_VOLM, PR_PRICE FROM tbl_pr_detail
							WHERE PRJCODE = '$PRJCODE' AND PR_STAT IN (3,6)";
			$RPR_00	= $this->db->query($SPR_00)->result();
			foreach($RPR_00 as $RPRW_00) :
				$PR_RW 			= $PR_RW+1;
				$JOBCODEID		= $RPRW_00->JOBCODEID;
				$ITM_CODE		= $RPRW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RPRW_00->ITM_UNIT);
				$PR_VOLM		= $RPRW_00->PR_VOLM;
				$PR_PRICE		= $RPRW_00->PR_PRICE;
				$PR_AMOUNT 		= $PR_VOLM * $PR_PRICE;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM + $PR_VOLM,
									REQ_AMOUNT = REQ_AMOUNT + $PR_AMOUNT
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LOT' || $ITM_UNIT == 'BLN')
				{
					$UPD_JLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = 1, REQ_AMOUNT = REQ_AMOUNT + $PR_AMOUNT
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($UPD_JLD);
				}
				else
				{
					$UPD_JLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$ITM_VOLM,
										REQ_AMOUNT = REQ_AMOUNT + $PR_AMOUNT
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($UPD_JLD);
				}

				// START : SENDING PROCESS
					$percent 	= intval($PR_RW/$TOTPR * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PR_RW. ' / '.$TOTPR.') SPP, VC, VLK PPD processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PR_RW. ' / '.$TOTPR.') SPP, VC, VLK PPD processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;

			$SPR_00 	= "SELECT JournalH_Code,JournalH_Date,Acc_Name,proj_Code,JOBCODEID,Base_Debet,
								ITM_CODE,ITM_VOLM,ITM_PRICE,ITM_UNIT FROM tbl_journaldetail
							WHERE JOBCODEID != '' AND ITM_CODE != '' AND Base_Debet > 0 AND proj_Code ='$PRJCODE'
								AND GEJ_STAT = 3 AND PPN_Amount = 0 AND PPH_Amount=0  AND JournalType NOT IN ('IR', 'PINV')";
			$RPR_00	= $this->db->query($SPR_00)->result();
			foreach($RPR_00 as $RPRW_00) :
				$PR_RW 			= $PR_RW+1;
				$PERIODM		= date('m', strtotime($RPRW_00->JournalH_Date));
				$PERIODY		= date('Y', strtotime($RPRW_00->JournalH_Date));
				$JOBCODEID		= $RPRW_00->JOBCODEID;
				$ITM_CODE		= $RPRW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RPRW_00->ITM_UNIT);
				$BASE_DEBET		= $RPRW_00->Base_Debet;
				$ITM_VOLM		= $RPRW_00->ITM_VOLM;

				if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LOT' || $ITM_UNIT == 'BLN')
				{
					$UPD_JLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = 1, REQ_AMOUNT = REQ_AMOUNT + $BASE_DEBET,
										ITM_USED = 1, ITM_USED_AM = ITM_USED_AM+$BASE_DEBET
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($UPD_JLD);
				}
				else
				{
					$UPD_JLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$ITM_VOLM,
										REQ_AMOUNT = REQ_AMOUNT + $BASE_DEBET,
										ITM_USED = ITM_USED+$ITM_VOLM, ITM_USED_AM = ITM_USED_AM+$BASE_DEBET
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($UPD_JLD);
				}

				$ITM_GROUP 		= "";
				$SITM_GRP 		= "SELECT ITM_GROUP FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE' LIMIT 1";
				$RITM_GRP	= $this->db->query($SITM_GRP)->result();
				foreach($RITM_GRP as $RWITM_GRP) :
					$ITM_GROUP	= $RWITM_GRP->ITM_GROUP;
				endforeach;

				// START : UPDATE L/R
					if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$BASE_DEBET 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' AND LR_STAT = 0";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'U')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$BASE_DEBET 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' AND LR_STAT = 0";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$BASE_DEBET 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' AND LR_STAT = 0";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$BASE_DEBET 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' AND LR_STAT = 0";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'I')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$BASE_DEBET 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' AND LR_STAT = 0";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'O')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$BASE_DEBET 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' AND LR_STAT = 0";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'GE')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$BASE_DEBET 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' AND LR_STAT = 0";
						$this->db->query($updLR);
					}
				// END : UPDATE L/R

				// START : SENDING PROCESS
					$percent 	= intval($PR_RW/$TOTPR * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PR_RW. ' / '.$TOTPR.') SPP, VC, VLK PPD processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PR_RW. ' / '.$TOTPR.') SPP, VC, VLK PPD processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT REQ

		// START 	: RE-COUNT PO
			$SPO_00C	= "tbl_po_detail WHERE PO_STAT IN (3,6) AND PRJCODE = '$PRJCODE'";
			$RPO_00C 	= $this->db->count_all($SPO_00C);

			$TOTPO 		= $RPO_00C;

			$PO_RW 		= 0;
			$SPO_00 	= "SELECT JOBCODEID, ITM_CODE, ITM_UNIT, PO_VOLM, PO_COST FROM tbl_po_detail
							WHERE PRJCODE = '$PRJCODE' AND PO_STAT IN (3,6)";
			$RPO_00	= $this->db->query($SPO_00)->result();
			foreach($RPO_00 as $RPOW_00) :
				$PO_RW 			= $PO_RW+1;
				$JOBCODEID		= $RPOW_00->JOBCODEID;
				$ITM_CODE		= $RPOW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RPOW_00->ITM_UNIT);
				$PO_VOLM		= $RPOW_00->PO_VOLM;
				$PO_COST		= $RPOW_00->PO_COST;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET PO_VOLM = PO_VOLM + $PO_VOLM,
									PO_AMOUNT = PO_AMOUNT + $PO_COST
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($PO_RW/$TOTPO * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PO_RW. ' / '.$TOTPO.') OP processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PO_RW. ' / '.$TOTPO.') OP processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT PO

		// START 	: RE-COUNT IR
			$SIR_00C	= "tbl_ir_detail A INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.IR_STAT IN (3,6)";
			$RIR_00C 	= $this->db->count_all($SIR_00C);

			$TOTIR 		= $RIR_00C;

			$IR_RW 		= 0;
			$SIR_00 	= "SELECT A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY, A.ITM_TOTAL FROM tbl_ir_detail A
			 					INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.IR_STAT IN (3,6)";
			$RIR_00	= $this->db->query($SIR_00)->result();
			foreach($RIR_00 as $RIRW_00) :
				$IR_RW 			= $IR_RW+1;
				$JOBCODEID		= $RIRW_00->JOBCODEID;
				$ITM_CODE		= $RIRW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RIRW_00->ITM_UNIT);
				$ITM_QTY		= $RIRW_00->ITM_QTY;
				$ITM_TOTAL		= $RIRW_00->ITM_TOTAL;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET IR_VOLM = IR_VOLM + $ITM_QTY,
									IR_AMOUNT = IR_AMOUNT + $ITM_TOTAL
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($IR_RW/$TOTIR * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$IR_RW. ' / '.$TOTIR.') LPM processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$IR_RW. ' / '.$TOTIR.') LPM processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT IR

		// START 	: RE-COUNT SPK
			$SWO_00C	= "tbl_wo_detail A INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.WO_STAT IN (3,6)";
			$RWO_00C 	= $this->db->count_all($SWO_00C);

			$TOTWO 		= $RWO_00C;

			$WO_RW 		= 0;
			$SWO_00 	= "SELECT A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.WO_VOLM, A.WO_TOTAL FROM tbl_wo_detail A
			 					INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.WO_STAT IN (3,6)";
			$RWO_00	= $this->db->query($SWO_00)->result();
			foreach($RWO_00 as $RWOW_00) :
				$WO_RW 			= $WO_RW+1;
				$JOBCODEID		= $RWOW_00->JOBCODEID;
				$ITM_CODE		= $RWOW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RWOW_00->ITM_UNIT);
				$ITM_QTY		= $RWOW_00->ITM_QTY;
				$ITM_TOTAL		= $RWOW_00->ITM_TOTAL;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET WO_QTY = WO_QTY + $ITM_QTY,
									WO_AMOUNT = WO_AMOUNT + $ITM_TOTAL
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($WO_RW/$TOTWO * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$WO_RW. ' / '.$TOTWO.') SPK processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$WO_RW. ' / '.$TOTWO.') SPK processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT SPK

		// START 	: RE-COUNT OPNAME
			$SOPN_00C	= "tbl_opn_detail A INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT IN (3,6)";
			$ROPN_00C 	= $this->db->count_all($SOPN_00C);

			$TOTOPN 		= $ROPN_00C;

			$OPN_RW 		= 0;
			$SOPN_00 	= "SELECT A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.OPND_VOLM, A.OPND_ITMTOTAL FROM tbl_opn_detail A 
								INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT IN (3,6)";
			$ROPN_00	= $this->db->query($SOPN_00)->result();
			foreach($ROPN_00 as $ROPNW_00) :
				$OPN_RW 			= $OPN_RW+1;
				$JOBCODEID		= $ROPNW_00->JOBCODEID;
				$ITM_CODE		= $ROPNW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($ROPNW_00->ITM_UNIT);
				$ITM_QTY		= $ROPNW_00->OPND_VOLM;
				$ITM_TOTAL		= $ROPNW_00->OPND_ITMTOTAL;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY + $ITM_QTY,
									OPN_AMOUNT = OPN_AMOUNT + $ITM_TOTAL
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($OPN_RW/$TOTOPN * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$OPN_RW. ' / '.$TOTOPN.') OPNAME processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$OPN_RW. ' / '.$TOTOPN.') OPNAME processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT OPNAME

		// START 	: RE-COUNT ADDENDUM
			$SAMD_00C	= "tbl_amd_detail A INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.AMD_STAT IN (3,6)";
			$RAMD_00C 	= $this->db->count_all($SAMD_00C);

			$TOTAMD 		= $RAMD_00C;

			$AMD_RW 		= 0;
			$SAMD_00 	= "SELECT A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.AMD_VOLM, A.AMD_TOTAL FROM tbl_amd_detail A 
								INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
							WHERE B.PRJCODE = '$PRJCODE' AND B.AMD_STAT IN (3,6)";
			$RAMD_00	= $this->db->query($SAMD_00)->result();
			foreach($RAMD_00 as $RAMDW_00) :
				$AMD_RW 		= $AMD_RW+1;
				$JOBCODEID		= $RAMDW_00->JOBCODEID;
				$ITM_CODE		= $RAMDW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RAMDW_00->ITM_UNIT);
				$ITM_QTY		= $RAMDW_00->AMD_VOLM;
				$ITM_TOTAL		= $RAMDW_00->AMD_TOTAL;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ITM_QTY,
									ADD_JOBCOST = ADD_JOBCOST + $ITM_TOTAL,
									ADD_PRICE = ADD_JOBCOST / ADD_VOLM
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($AMD_RW/$TOTAMD * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$AMD_RW. ' / '.$TOTAMD.') AMANDEMEN processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$AMD_RW. ' / '.$TOTAMD.') AMANDEMEN processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT ADDENDUM

		// 3.	COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'RECOUNTJRN')	// READY TO ADD ON
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];

		$PRJNAME 	= '';
		$sqlPRJNM	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPRJNM	= $this->db->query($sqlPRJNM)->result();
		foreach($resPRJNM as $rowPRJNM) :
			$PRJNAME= $rowPRJNM->PRJNAME;
		endforeach;

	    $percent = intval(0)."%";
		echo '<script>parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';
		echo '<script>parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

	    ob_flush();
	    flush();

		// UPDATE Journal Header
		$resGEJH 	= "UPDATE tbl_journaldetail A, tbl_journalheader B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date, A.JournalType = B.JournalType
						WHERE A.JournalH_Code = B.JournalH_Code";
		$this->db->query($resGEJH);

		// RESET SELURUH PENGGUNAAN JOBLISDETAIL
			/*$updJLD = "UPDATE tbl_joblist_detail SET OPN_QTY=0, OPN_AMOUNT=0, ITM_USED=0, ITM_USED_AM=0
						WHERE PRJCODE = '$PRJCODE'";*/
			$updJLD = "UPDATE tbl_joblist_detail SET ADD_VOLM=0, ADD_PRICE=0, ADD_JOBCOST=0, ADDM_VOLM=0, ADDM_JOBCOST=0,
							REQ_VOLM=0, REQ_AMOUNT=0, PO_VOLM=0, PO_AMOUNT=0, IR_VOLM=0, IR_AMOUNT=0,
							WO_QTY=0, WO_AMOUNT=0, OPN_QTY=0, OPN_AMOUNT=0, ITM_USED=0, ITM_USED_AM=0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updJLD);

			$updJLD = "UPDATE tbl_profitloss SET BPP_MTR_REAL = 0, BPP_UPH_REAL = 0, BPP_SUBK_REAL = 0,
							BPP_ALAT_REAL = 0, BPP_I_REAL = 0, BPP_I_REAL = 0
						WHERE PRJCODE = '$PRJCODE' AND LR_STAT = 0";
			$this->db->query($updJLD);

		// START 	: RESET STATUS
			$SUP_00		= "UPDATE tbl_journaldetail A, tbl_journalheader B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);

			$SUP_00		= "UPDATE tbl_journaldetail_pd A, tbl_journalheader_pd B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);

			$SUP_00		= "UPDATE tbl_journaldetail_cprj A, tbl_journalheader_cprj B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);

			$SUP_00		= "UPDATE tbl_journaldetail_pd A, tbl_journalheader_pd B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);
			
			$SUP_00		= "UPDATE tbl_journaldetail_vcash A, tbl_journalheader_vcash B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($SUP_00);
			
			$SUP_00		= "UPDATE tbl_pr_detail A, tbl_pr_header B
							SET A.PR_STAT = B.PR_STAT, A.PR_DATE = B.PR_DATE WHERE A.PR_NUM = B.PR_NUM";
			$this->db->query($SUP_00);
			
			$SUP_00		= "UPDATE tbl_po_detail A, tbl_po_header B
							SET A.PO_STAT = B.PO_STAT WHERE A.PO_NUM = B.PO_NUM";
			$this->db->query($SUP_00);
		// END 		: RESET STATUS

		// START 	: RE-COUNT REQ
			$SPR_00C	= "tbl_pr_detail WHERE PR_STAT IN (3,6) AND PRJCODE = '$PRJCODE'";
			$RPR_00C 	= $this->db->count_all($SPR_00C);

			$SPR_01C	= "tbl_journaldetail_vcash WHERE GEJ_STAT = 3 AND proj_Code = '$PRJCODE'";
			$RPR_01C 	= $this->db->count_all($SPR_01C);

			$SPR_02C	= "tbl_journaldetail_pd WHERE GEJ_STAT = 3 AND ISPERSL_REALIZ = '1' AND proj_Code = '$PRJCODE'";
			$RPR_02C 	= $this->db->count_all($SPR_02C);

			$SPR_03C	= "tbl_journaldetail_cprj WHERE GEJ_STAT = 3 AND proj_Code = '$PRJCODE'";
			$RPR_03C 	= $this->db->count_all($SPR_03C);

			$TOTPR 		= $RPR_00C+$RPR_01C+$RPR_02C+$RPR_03C;

			$PR_RW 		= 0;
			$SPR_00 	= "SELECT JOBCODEID, ITM_CODE, ITM_UNIT, PR_VOLM, PR_PRICE FROM tbl_pr_detail
							WHERE PRJCODE = '$PRJCODE' AND PR_STAT IN (3,6)";
			$RPR_00	= $this->db->query($SPR_00)->result();
			foreach($RPR_00 as $RPRW_00) :
				$PR_RW 			= $PR_RW+1;
				$JOBCODEID		= $RPRW_00->JOBCODEID;
				$ITM_CODE		= $RPRW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RPRW_00->ITM_UNIT);
				$PR_VOLM		= $RPRW_00->PR_VOLM;
				$PR_PRICE		= $RPRW_00->PR_PRICE;
				$PR_AMOUNT 		= $PR_VOLM * $PR_PRICE;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM + $PR_VOLM,
									REQ_AMOUNT = REQ_AMOUNT + $PR_AMOUNT
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LOT' || $ITM_UNIT == 'BLN')
				{
					$UPD_JLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = 1, REQ_AMOUNT = REQ_AMOUNT + $PR_AMOUNT
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($UPD_JLD);
				}
				else
				{
					$UPD_JLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$ITM_VOLM,
										REQ_AMOUNT = REQ_AMOUNT + $PR_AMOUNT
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($UPD_JLD);
				}

				// START : SENDING PROCESS
					$percent 	= intval($PR_RW/$TOTPR * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PR_RW. ' / '.$TOTPR.') SPP, VC, VLK PPD processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PR_RW. ' / '.$TOTPR.') SPP, VC, VLK PPD processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;

			/*$SPR_00 	= "SELECT JournalH_Code,JournalH_Date,Acc_Name,proj_Code,JOBCODEID,Base_Debet,
								ITM_CODE,ITM_VOLM,ITM_PRICE,ITM_UNIT FROM tbl_journaldetail
							WHERE JOBCODEID != '' AND ITM_CODE != '' AND Base_Debet > 0 AND proj_Code ='$PRJCODE'
								AND GEJ_STAT = 3 AND PPN_Amount = 0 AND PPH_Amount=0  AND JournalType NOT IN ('IR', 'PINV')";*/
			$SPR_00 	= "SELECT JournalH_Code,JournalH_Date,Acc_Name,proj_Code,JOBCODEID,Base_Debet,
								ITM_CODE,ITM_VOLM,ITM_PRICE,ITM_UNIT FROM tbl_journaldetail
							WHERE JOBCODEID != '' AND ITM_CODE != '' AND Base_Debet > 0 AND proj_Code ='$PRJCODE'
								AND GEJ_STAT = 3 AND JournalType NOT IN ('IR', 'PINV')";
			$RPR_00	= $this->db->query($SPR_00)->result();
			foreach($RPR_00 as $RPRW_00) :
				$PR_RW 			= $PR_RW+1;
				$PERIODM		= date('m', strtotime($RPRW_00->JournalH_Date));
				$PERIODY		= date('Y', strtotime($RPRW_00->JournalH_Date));
				$JOBCODEID		= $RPRW_00->JOBCODEID;
				$ITM_CODE		= $RPRW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RPRW_00->ITM_UNIT);
				$BASE_DEBET		= $RPRW_00->Base_Debet;
				$ITM_VOLM		= $RPRW_00->ITM_VOLM;

				if($ITM_UNIT == 'LS' || $ITM_UNIT == 'LOT' || $ITM_UNIT == 'BLN')
				{
					$UPD_JLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = 1, REQ_AMOUNT = REQ_AMOUNT + $BASE_DEBET,
										ITM_USED = 1, ITM_USED_AM = ITM_USED_AM+$BASE_DEBET
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($UPD_JLD);
				}
				else
				{
					$UPD_JLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$ITM_VOLM,
										REQ_AMOUNT = REQ_AMOUNT + $BASE_DEBET,
										ITM_USED = ITM_USED+$ITM_VOLM, ITM_USED_AM = ITM_USED_AM+$BASE_DEBET
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($UPD_JLD);
				}

				// START : SENDING PROCESS
					$percent 	= intval($PR_RW/$TOTPR * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PR_RW. ' / '.$TOTPR.') SPP, VC, VLK PPD processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PR_RW. ' / '.$TOTPR.') SPP, VC, VLK PPD processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT REQ

		// START 	: RE-COUNT PO
			$SPO_00C	= "tbl_po_detail WHERE PO_STAT IN (3,6) AND PRJCODE = '$PRJCODE'";
			$RPO_00C 	= $this->db->count_all($SPO_00C);

			$TOTPO 		= $RPO_00C;

			$PO_RW 		= 0;
			$SPO_00 	= "SELECT JOBCODEID, ITM_CODE, ITM_UNIT, PO_VOLM, PO_COST FROM tbl_po_detail
							WHERE PRJCODE = '$PRJCODE' AND PO_STAT IN (3,6)";
			$RPO_00	= $this->db->query($SPO_00)->result();
			foreach($RPO_00 as $RPOW_00) :
				$PO_RW 			= $PO_RW+1;
				$JOBCODEID		= $RPOW_00->JOBCODEID;
				$ITM_CODE		= $RPOW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RPOW_00->ITM_UNIT);
				$PO_VOLM		= $RPOW_00->PO_VOLM;
				$PO_COST		= $RPOW_00->PO_COST;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET PO_VOLM = PO_VOLM + $PO_VOLM,
									PO_AMOUNT = PO_AMOUNT + $PO_COST
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($PO_RW/$TOTPO * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PO_RW. ' / '.$TOTPO.') OP processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PO_RW. ' / '.$TOTPO.') OP processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT PO

		// START 	: RE-COUNT IR
			$SIR_00C	= "tbl_ir_detail A INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.IR_STAT IN (3,6)";
			$RIR_00C 	= $this->db->count_all($SIR_00C);

			$TOTIR 		= $RIR_00C;

			$IR_RW 		= 0;
			$SIR_00 	= "SELECT A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY, A.ITM_TOTAL FROM tbl_ir_detail A
			 					INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.IR_STAT IN (3,6)";
			$RIR_00	= $this->db->query($SIR_00)->result();
			foreach($RIR_00 as $RIRW_00) :
				$IR_RW 			= $IR_RW+1;
				$JOBCODEID		= $RIRW_00->JOBCODEID;
				$ITM_CODE		= $RIRW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RIRW_00->ITM_UNIT);
				$ITM_QTY		= $RIRW_00->ITM_QTY;
				$ITM_TOTAL		= $RIRW_00->ITM_TOTAL;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET IR_VOLM = IR_VOLM + $ITM_QTY,
									IR_AMOUNT = IR_AMOUNT + $ITM_TOTAL
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($IR_RW/$TOTIR * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$IR_RW. ' / '.$TOTIR.') LPM processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$IR_RW. ' / '.$TOTIR.') LPM processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT IR

		// START 	: RE-COUNT SPK
			$SWO_00C	= "tbl_wo_detail A INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.WO_STAT IN (3,6)";
			$RWO_00C 	= $this->db->count_all($SWO_00C);

			$TOTWO 		= $RWO_00C;

			$WO_RW 		= 0;
			$SWO_00 	= "SELECT A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.WO_VOLM, A.WO_TOTAL FROM tbl_wo_detail A
			 					INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.WO_STAT IN (3,6)";
			$RWO_00	= $this->db->query($SWO_00)->result();
			foreach($RWO_00 as $RWOW_00) :
				$WO_RW 			= $WO_RW+1;
				$JOBCODEID		= $RWOW_00->JOBCODEID;
				$ITM_CODE		= $RWOW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RWOW_00->ITM_UNIT);
				$ITM_QTY		= $RWOW_00->WO_VOLM;
				$ITM_TOTAL		= $RWOW_00->WO_TOTAL;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET WO_QTY = WO_QTY + $ITM_QTY,
									WO_AMOUNT = WO_AMOUNT + $ITM_TOTAL
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($WO_RW/$TOTWO * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$WO_RW. ' / '.$TOTWO.') SPK processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$WO_RW. ' / '.$TOTWO.') SPK processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT SPK

		// START 	: RE-COUNT OPNAME
			$SOPN_00C	= "tbl_opn_detail A INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT IN (3,6)";
			$ROPN_00C 	= $this->db->count_all($SOPN_00C);

			$TOTOPN 		= $ROPN_00C;

			$OPN_RW 		= 0;
			$SOPN_00 	= "SELECT A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.OPND_VOLM, A.OPND_ITMTOTAL FROM tbl_opn_detail A 
								INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT IN (3,6)";
			$ROPN_00	= $this->db->query($SOPN_00)->result();
			foreach($ROPN_00 as $ROPNW_00) :
				$OPN_RW 			= $OPN_RW+1;
				$JOBCODEID		= $ROPNW_00->JOBCODEID;
				$ITM_CODE		= $ROPNW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($ROPNW_00->ITM_UNIT);
				$ITM_QTY		= $ROPNW_00->OPND_VOLM;
				$ITM_TOTAL		= $ROPNW_00->OPND_ITMTOTAL;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY + $ITM_QTY,
									OPN_AMOUNT = OPN_AMOUNT + $ITM_TOTAL
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($OPN_RW/$TOTOPN * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$OPN_RW. ' / '.$TOTOPN.') OPNAME processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$OPN_RW. ' / '.$TOTOPN.') OPNAME processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT OPNAME

		// START 	: RE-COUNT ADDENDUM
			$SAMD_00C	= "tbl_amd_detail A INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
							WHERE A.PRJCODE = '$PRJCODE' AND B.AMD_STAT IN (3,6)";
			$RAMD_00C 	= $this->db->count_all($SAMD_00C);

			$TOTAMD 		= $RAMD_00C;

			$AMD_RW 		= 0;
			$SAMD_00 	= "SELECT A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.AMD_VOLM, A.AMD_TOTAL FROM tbl_amd_detail A 
								INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM
							WHERE B.PRJCODE = '$PRJCODE' AND B.AMD_STAT IN (3,6)";
			$RAMD_00	= $this->db->query($SAMD_00)->result();
			foreach($RAMD_00 as $RAMDW_00) :
				$AMD_RW 		= $AMD_RW+1;
				$JOBCODEID		= $RAMDW_00->JOBCODEID;
				$ITM_CODE		= $RAMDW_00->ITM_CODE;
				$ITM_UNIT		= strtoupper($RAMDW_00->ITM_UNIT);
				$ITM_QTY		= $RAMDW_00->AMD_VOLM;
				$ITM_TOTAL		= $RAMDW_00->AMD_TOTAL;

				$UPD_JLD 		= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ITM_QTY,
									ADD_JOBCOST = ADD_JOBCOST + $ITM_TOTAL,
									ADD_PRICE = ADD_JOBCOST / ADD_VOLM
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'
									AND PRJCODE = '$PRJCODE'";
				$this->db->query($UPD_JLD);

				// START : SENDING PROCESS
					$percent 	= intval($AMD_RW/$TOTAMD * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$AMD_RW. ' / '.$TOTAMD.') AMANDEMEN processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$AMD_RW. ' / '.$TOTAMD.') AMANDEMEN processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
		// END 		: RE-COUNT ADDENDUM

		// 3.	COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();parent.clsBarX();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'RENDERDATA')
	{
		$REN_GROUP	= $_POST['IMP_CODEX'];
		$DESCRIPT	= $_POST['DESCRIPT'];

		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');

		$SYNC_PRJ	= $PRJCODE;
		$PRJCODE 	= $PRJCODE;

		$COMPID		= 0;
		$sqlPHO		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
		$resPHO		= $this->db->query($sqlPHO)->result();
		foreach($resPHO as $rowpho) :
			$COMPID = $rowpho->PRJCODE;
		endforeach;
		$PRJCODE_HO = $COMPID;

		if($REN_GROUP == 'COA')
		{
			$TABLENM1 	= 'kper'.$DESCRIPT;
			$TABLENM2 	= "";

			$BaseYear 	= $DESCRIPT;

			/*$tags 		= explode('~',$DESCRIPT);
			$tTbl 		= count($tags);
			if($tTbl > 1)
			{
				$TABLENM1 	= $tags[0];
				$TABLENM2 	= $tags[1];
			}*/

			$sqlCOAC	= "$TABLENM1";
			$resCOAC	= $this->db->count_all($sqlCOAC);

			$s_TRUNSC	= "DELETE FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSC);

			$s_REND		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
			$r_REND		= $this->db->count_all($s_REND);
			$percent 	= intval($r_REND/$resCOAC * 100)."%";
			echo '<script>
			parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';
			echo '<script>
			parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';

		    ob_flush();
		    flush();

			// START : RENDER PROCEDUR - RENDER COA
				$accid 	= 100000;
				$ordid	= 0;
				$s_01	= "SELECT kodeacc, namaacc, d_k, awal, akhir, debet, kredit, induk, judul
							FROM $TABLENM1 WHERE level = 1 ORDER BY kodeacc";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$kodeacc1	= $rw_01->kodeacc;
					$namaacc1	= $rw_01->namaacc;
					$level1 	= 1;
					$d_k1		= $rw_01->d_k;
					$awal1		= $rw_01->awal;
					$akhir1		= $rw_01->akhir;
					$debet1		= $rw_01->debet;
					$kredit1	= $rw_01->kredit;
					$induk1		= $rw_01->induk;
					$judul1		= $rw_01->judul;
					$cat1 		= substr($kodeacc1, 0, 1);  // First Categ

					$isL1 		= 1;
					$cls1 		= 2;	// Child
					if($judul1 == 'Y')
					{
						$isL1 	= 0;
						$cls1 	= 1;
					}

					// INSERT INTO COA LEVEL 1
						$ordid 	= $ordid+1;
						$accid 	= $accid+1;
						$s_ins1	= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
										Account_Number, Acc_DirParent, Account_NameEn, Account_NameId, Account_Category,
										Account_Level, Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, 
										Base_OpeningBalance, Base_Debet, Base_Kredit,
										Base_OpeningBalance2, Base_Debet2, Base_Kredit2,
										BaseD_$BaseYear, BaseK_$BaseYear,
										Currency_id, Link_Report, isHO, syncPRJ, isLast)

									VALUES ('$ordid', '$PRJCODE', '$PRJCODE_HO', '$accid', '$cls1',
										'$kodeacc1', '$induk1','$namaacc1', '$namaacc1', '$cat1',
										'$level1', 1, 1, '$COMPID', '$d_k1',
										'$akhir1', '$debet1', '$kredit1',
										'$akhir1', '$debet1', '$kredit1',
										'$debet1', '$kredit1',
										'IDR', 'LR', '1', '$SYNC_PRJ', '$isL1')";
						$this->db->query($s_ins1);

					$s_REND		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
					$r_REND		= $this->db->count_all($s_REND);
					$percent 	= intval($r_REND/$resCOAC * 100)."%";
					echo '<script>
					parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';
					echo '<script>
					parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';

				    ob_flush();
				    flush();

					// BREAKDOWN CHILD : LEVEL 2
						if($isL1 == 0)
						{
							$s_02	= "SELECT kodeacc, namaacc, d_k, awal, akhir, debet, kredit, induk, judul
										FROM $TABLENM1 WHERE induk = '$kodeacc1' ORDER BY kodeacc";
							$r_02 	= $this->db->query($s_02)->result();
							foreach($r_02 as $rw_02) :
								$kodeacc2	= $rw_02->kodeacc;
								$namaacc2	= $rw_02->namaacc;
								$level2 	= 2;
								$d_k2		= $rw_02->d_k;
								$awal2		= $rw_02->awal;
								$akhir2		= $rw_02->akhir;
								$debet2		= $rw_02->debet;
								$kredit2	= $rw_02->kredit;
								$induk2		= $rw_02->induk;
								$judul2		= $rw_02->judul;
								$cat2 		= substr($kodeacc2, 0, 1);  // First Categ

								$isL2 		= 1;
								$cls2 		= 2;	// Child
								if($judul2 == 'Y')
								{
									$isL2 	= 0;
									$cls2 	= 1;
								}

								// INSERT INTO COA LEVEL 2
									$ordid 	= $ordid+1;
									$accid 	= $accid+1;
									$s_ins2	= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
													Account_Number, Acc_DirParent, Account_NameEn, Account_NameId, Account_Category,
													Account_Level, Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, 
													Base_OpeningBalance, Base_Debet, Base_Kredit,
													Base_OpeningBalance2, Base_Debet2, Base_Kredit2,
													BaseD_$BaseYear, BaseK_$BaseYear,
													Currency_id, Link_Report, isHO, syncPRJ, isLast)

												VALUES ('$ordid', '$PRJCODE', '$PRJCODE_HO', '$accid', '$cls2',
													'$kodeacc2', '$induk2','$namaacc2', '$namaacc2', '$cat2',
													'$level2', 1, 1, '$COMPID', '$d_k2',
													'$akhir2', '$debet2', '$kredit2',
													'$akhir2', '$debet2', '$kredit2',
													'$debet2', '$kredit2',
													'IDR', 'LR', '1', '$SYNC_PRJ', '$isL2')";
									$this->db->query($s_ins2);	

								$s_REND		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
								$r_REND		= $this->db->count_all($s_REND);
								$percent 	= intval($r_REND/$resCOAC * 100)."%";
								echo '<script>
								parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';
								echo '<script>
								parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';

							    ob_flush();
							    flush();

								// BREAKDOWN CHILD : LEVEL 3
									if($isL2 == 0)
									{
										$s_03	= "SELECT kodeacc, namaacc, d_k, awal, akhir, debet, kredit, induk, judul
													FROM $TABLENM1 WHERE induk = '$kodeacc2' ORDER BY kodeacc";
										$r_03 	= $this->db->query($s_03)->result();
										foreach($r_03 as $rw_03) :
											$kodeacc3	= $rw_03->kodeacc;
											$namaacc3	= $rw_03->namaacc;
											$level3 	= 3;
											$d_k3		= $rw_03->d_k;
											$awal3		= $rw_03->awal;
											$akhir3		= $rw_03->akhir;
											$debet3		= $rw_03->debet;
											$kredit3	= $rw_03->kredit;
											$induk3		= $rw_03->induk;
											$judul3		= $rw_03->judul;
											$cat3 		= substr($kodeacc3, 0, 1);  // First Categ

											$isL3 		= 1;
											$cls3 		= 2;	// Child
											if($judul3 == 'Y')
											{
												$isL3 	= 0;
												$cls3 	= 1;
											}

											// INSERT INTO COA LEVEL 3
												$ordid 	= $ordid+1;
												$accid 	= $accid+1;
												$s_ins3	= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
																Account_Number, Acc_DirParent, Account_NameEn, Account_NameId, Account_Category,
																Account_Level, Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, 
																Base_OpeningBalance, Base_Debet, Base_Kredit,
																Base_OpeningBalance2, Base_Debet2, Base_Kredit2,
																BaseD_$BaseYear, BaseK_$BaseYear,
																Currency_id, Link_Report, isHO, syncPRJ, isLast)

															VALUES ('$ordid', '$PRJCODE', '$PRJCODE_HO', '$accid', '$cls3',
																'$kodeacc3', '$induk3','$namaacc3', '$namaacc3', '$cat3',
																'$level3', 1, 1, '$COMPID', '$d_k3',
																'$akhir3', '$debet3', '$kredit3',
																'$akhir3', '$debet3', '$kredit3',
																'$debet3', '$kredit3',
																'IDR', 'LR', '1', '$SYNC_PRJ', '$isL3')";
												$this->db->query($s_ins3);

											$s_REND		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
											$r_REND		= $this->db->count_all($s_REND);
											$percent 	= intval($r_REND/$resCOAC * 100)."%";
											echo '<script>
											parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';
											echo '<script>
											parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';

										    ob_flush();
										    flush();

											// BREAKDOWN CHILD : LEVEL 4
												if($isL3 == 0)
												{
													$s_04	= "SELECT kodeacc, namaacc, d_k, awal, akhir, debet, kredit, induk, judul
																FROM $TABLENM1 WHERE induk = '$kodeacc3' ORDER BY kodeacc";
													$r_04 	= $this->db->query($s_04)->result();
													foreach($r_04 as $rw_04) :
														$kodeacc4	= $rw_04->kodeacc;
														$namaacc4	= $rw_04->namaacc;
														$level4 	= 4;
														$d_k4		= $rw_04->d_k;
														$awal4		= $rw_04->awal;
														$akhir4		= $rw_04->akhir;
														$debet4		= $rw_04->debet;
														$kredit4	= $rw_04->kredit;
														$induk4		= $rw_04->induk;
														$judul4		= $rw_04->judul;
														$cat4 		= substr($kodeacc4, 0, 1);  // First Categ

														$isL4 		= 1;
														$cls4 		= 2;	// Child
														if($judul4 == 'Y')
														{
															$isL4 	= 0;
															$cls4 	= 1;
														}

														// INSERT INTO COA LEVEL 4
															$ordid 	= $ordid+1;
															$accid 	= $accid+1;
															$s_ins4	= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
																			Account_Number, Acc_DirParent, Account_NameEn, Account_NameId, Account_Category,
																			Account_Level, Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, 
																			Base_OpeningBalance, Base_Debet, Base_Kredit,
																			Base_OpeningBalance2, Base_Debet2, Base_Kredit2,
																			BaseD_$BaseYear, BaseK_$BaseYear,
																			Currency_id, Link_Report, isHO, syncPRJ, isLast)

																		VALUES ('$ordid', '$PRJCODE', '$PRJCODE_HO', '$accid', '$cls4',
																			'$kodeacc4', '$induk4','$namaacc4', '$namaacc4', '$cat4',
																			'$level4', 1, 1, '$COMPID', '$d_k4',
																			'$akhir4', '$debet4', '$kredit4',
																			'$akhir4', '$debet4', '$kredit4',
																			'$debet4', '$kredit4',
																			'IDR', 'LR', '1', '$SYNC_PRJ', '$isL4')";
															$this->db->query($s_ins4);

														$s_REND		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
														$r_REND		= $this->db->count_all($s_REND);
														$percent 	= intval($r_REND/$resCOAC * 100)."%";
														echo '<script>
														parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';
														echo '<script>
														parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';

													    ob_flush();
													    flush();

														// BREAKDOWN CHILD : LEVEL 5
															if($isL4 == 0)
															{
																$s_05	= "SELECT kodeacc, namaacc, d_k, awal, akhir, debet, kredit, induk, judul
																			FROM $TABLENM1 WHERE induk = '$kodeacc4' ORDER BY kodeacc";
																$r_05 	= $this->db->query($s_05)->result();
																foreach($r_05 as $rw_05) :
																	$kodeacc5	= $rw_05->kodeacc;
																	$namaacc5	= $rw_05->namaacc;
																	$level5 	= 5;
																	$d_k5		= $rw_05->d_k;
																	$awal5		= $rw_05->awal;
																	$akhir5		= $rw_05->akhir;
																	$debet5		= $rw_05->debet;
																	$kredit5	= $rw_05->kredit;
																	$induk5		= $rw_05->induk;
																	$judul5		= $rw_05->judul;
																	$cat5 		= substr($kodeacc5, 0, 1);  // First Categ

																	$isL5 		= 1;
																	$cls5 		= 2;	// Child
																	if($judul5 == 'Y')
																	{
																		$isL5 	= 0;
																		$cls5 	= 1;
																	}

																	// INSERT INTO COA LEVEL 5
																		$ordid 	= $ordid+1;
																		$accid 	= $accid+1;
																		$s_ins5	= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
																						Account_Number, Acc_DirParent, Account_NameEn, Account_NameId, Account_Category,
																						Account_Level, Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, 
																						Base_OpeningBalance, Base_Debet, Base_Kredit,
																						Base_OpeningBalance2, Base_Debet2, Base_Kredit2,
																						BaseD_$BaseYear, BaseK_$BaseYear,
																						Currency_id, Link_Report, isHO, syncPRJ, isLast)

																					VALUES ('$ordid', '$PRJCODE', '$PRJCODE_HO', '$accid', '$cls5',
																						'$kodeacc5', '$induk5','$namaacc5', '$namaacc5', '$cat5',
																						'$level5', 1, 1, '$COMPID', '$d_k5',
																						'$akhir5', '$debet5', '$kredit5',
																						'$akhir5', '$debet5', '$kredit5',
																						'$debet5', '$kredit5',
																						'IDR', 'LR', '1', '$SYNC_PRJ', '$isL5')";
																		$this->db->query($s_ins5);

																	$s_REND		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
																	$r_REND		= $this->db->count_all($s_REND);
																	$percent 	= intval($r_REND/$resCOAC * 100)."%";
																	echo '<script>
																	parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';
																	echo '<script>
																	parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';

																    ob_flush();
																    flush();

																	// BREAKDOWN CHILD : LEVEL 6
																		if($isL5 == 0)
																		{
																			$s_06	= "SELECT kodeacc, namaacc, d_k, awal, akhir, debet, kredit, induk, judul
																						FROM $TABLENM1 WHERE induk = '$kodeacc5' ORDER BY kodeacc";
																			$r_06 	= $this->db->query($s_06)->result();
																			foreach($r_06 as $rw_06) :
																				$kodeacc6	= $rw_06->kodeacc;
																				$namaacc6	= $rw_06->namaacc;
																				$level6 	= 6;
																				$d_k6		= $rw_06->d_k;
																				$awal6		= $rw_06->awal;
																				$akhir6		= $rw_06->akhir;
																				$debet6		= $rw_06->debet;
																				$kredit6	= $rw_06->kredit;
																				$induk6		= $rw_06->induk;
																				$judul6		= $rw_06->judul;
																				$cat6 		= substr($kodeacc6, 0, 1);  // First Categ

																				$isL6 		= 1;
																				$cls6 		= 2;	// Child
																				if($judul6 == 'Y')
																				{
																					$isL6 	= 0;
																					$cls6 	= 1;
																				}

																				// INSERT INTO COA LEVEL 6
																					$ordid 	= $ordid+1;
																					$accid 	= $accid+1;
																					$s_ins6	= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
																									Account_Number, Acc_DirParent, Account_NameEn, Account_NameId, Account_Category,
																									Account_Level, Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, 
																									Base_OpeningBalance, Base_Debet, Base_Kredit,
																									Base_OpeningBalance2, Base_Debet2, Base_Kredit2,
																									BaseD_$BaseYear, BaseK_$BaseYear,
																									Currency_id, Link_Report, isHO, syncPRJ, isLast)

																								VALUES ('$ordid', '$PRJCODE', '$PRJCODE_HO', '$accid', '$cls6',
																									'$kodeacc6', '$induk6','$namaacc6', '$namaacc6', '$cat6',
																									'$level6', 1, 1, '$COMPID', '$d_k6',
																									'$akhir6', '$debet6', '$kredit6',
																									'$akhir6', '$debet6', '$kredit6',
																									'$debet6', '$kredit6',
																									'IDR', 'LR', '1', '$SYNC_PRJ', '$isL6')";
																					$this->db->query($s_ins6);

																				$s_REND		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
																				$r_REND		= $this->db->count_all($s_REND);
																				$percent 	= intval($r_REND/$resCOAC * 100)."%";
																				echo '<script>
																				parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';
																				echo '<script>
																				parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';

																			    ob_flush();
																			    flush();

																				// BREAKDOWN CHILD : LEVEL 8
																					if($isL6 == 0)
																					{
																						$s_08	= "SELECT kodeacc, namaacc, d_k, awal, akhir, debet, kredit, induk, judul
																									FROM $TABLENM1 WHERE induk = '$kodeacc6' ORDER BY kodeacc";
																						$r_08 	= $this->db->query($s_08)->result();
																						foreach($r_08 as $rw_08) :
																							$kodeacc8	= $rw_08->kodeacc;
																							$namaacc8	= $rw_08->namaacc;
																							$level8 	= 8;
																							$d_k8		= $rw_08->d_k;
																							$awal8		= $rw_08->awal;
																							$akhir8		= $rw_08->akhir;
																							$debet8		= $rw_08->debet;
																							$kredit8	= $rw_08->kredit;
																							$induk8		= $rw_08->induk;
																							$judul8		= $rw_08->judul;
																							$cat8 		= substr($kodeacc8, 0, 1);  // First Categ

																							$isL8 		= 1;
																							$cls8 		= 2;	// Child
																							if($judul8 == 'Y')
																							{
																								$isL8 	= 0;
																								$cls8 	= 1;
																							}

																							// INSERT INTO COA LEVEL 8
																								$ordid 	= $ordid+1;
																								$accid 	= $accid+1;
																								$s_ins8	= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
																												Account_Number, Acc_DirParent, Account_NameEn, Account_NameId, Account_Category,
																												Account_Level, Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, 
																												Base_OpeningBalance, Base_Debet, Base_Kredit,
																												Base_OpeningBalance2, Base_Debet2, Base_Kredit2,
																												BaseD_$BaseYear, BaseK_$BaseYear,
																												Currency_id, Link_Report, isHO, syncPRJ, isLast)

																											VALUES ('$ordid', '$PRJCODE', '$PRJCODE_HO', '$accid', '$cls8',
																												'$kodeacc8', '$induk8','$namaacc8', '$namaacc8', '$cat8',
																												'$level8', 1, 1, '$COMPID', '$d_k8',
																												'$akhir8', '$debet8', '$kredit8',
																												'$akhir8', '$debet8', '$kredit8',
																												'$debet8', '$kredit8',
																												'IDR', 'LR', '1', '$SYNC_PRJ', '$isL8')";
																								$this->db->query($s_ins8);

																							$s_REND		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
																							$r_REND		= $this->db->count_all($s_REND);
																							$percent 	= intval($r_REND/$resCOAC * 100)."%";
																							echo '<script>
																							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';
																							echo '<script>
																							parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_REND. ' / '.$resCOAC.') processed</span></div>";</script>';

																						    ob_flush();
																						    flush();
																						endforeach;
																					}
																			endforeach;
																		}
																endforeach;
															}
													endforeach;
												}
										endforeach;
									}
							endforeach;
						}
				endforeach;

				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
			// END : RENDER PROCEDUR - RENDER COA
		}
		else if($REN_GROUP == 'BOQ')
		{
			$TABLENM1 	= 'p_'.$PRJCODE;
			$TABLENM2 	= 'r_'.$PRJCODE;

			/*$tags 		= explode('~',$TABLENM);
			$tTbl 		= count($tags);
			if($tTbl > 1)
			{
				$TABLENM1 	= $tags[0];
				$TABLENM2 	= $tags[1];
			}*/

			$sPEK		= "$TABLENM1";
			$RPEK		= $this->db->count_all($sPEK);

			$sPEKD		= "$TABLENM2";
			$RPEKD		= $this->db->count_all($sPEKD);

			$s_TRUNSC	= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSC);
			$s_TRUNSC	= "DELETE FROM tbl_boqlist_temp WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSC);
			$s_TRUNSC	= "DELETE FROM tbl_joblist WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSC);
			$s_TRUNSC	= "DELETE FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSC);

			$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
			$r_IMP		= $this->db->count_all($s_IMP);
			$percent 	= intval($r_IMP/$RPEK * 100)."%";

			$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
			$r_IMPD		= $this->db->count_all($s_IMPD);
			$percent2 	= intval($r_IMPD/$RPEKD * 100)."%";

			echo '<script>
			parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$s_IMP.') processed</span></div>";</script>';
			echo '<script>
			parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$s_IMP.') processed</span></div>";</script>';

		    ob_flush();
		    flush();

			// START : RENDER PROCEDUR - RENDER BOQ
				$ordid	= 0;
				/*$s_01	= "SELECT judul, level, kodepek, namapek, satuan, volumer, hargar, htawar, volume_k, harga_k, nilai_k, pfc, bobot, bobotr, bobota,
							r_bahan, r_upah, r_alat, r_subkon, r_overhead, p_bahan, p_upah, p_alat, p_subkon, p_overhead, vol_opname, vol_a_kon, hrg_a_kon,
							jlm_a_kon, vol_a_rap, hrg_a_rap ,jlm_a_rap
							FROM $TABLENM1 WHERE level = 1 ORDER BY kodeacc";*/

				$s_01	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k, harga_k, nilai_k, pfc, bobot, bobotr, htawar
							FROM $TABLENM1 WHERE level = 1 ORDER BY kodepek";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$judul1		= $rw_01->judul;
					$level1		= $rw_01->level;
					$level1 	= 1;
					$kodepek1	= $rw_01->kodepek;
					$induk1		= $rw_01->induk;
					$namapek1	= addslashes($rw_01->namapek);
					$satuan1	= addslashes($rw_01->satuan);
					$volumer1	= $rw_01->volumer;
					$hargar1	= $rw_01->hargar;
					$volume_k1	= $rw_01->volume_k;
					$harga_k1	= $rw_01->harga_k;
					$nilai_k1	= $rw_01->nilai_k;
					$bobot1		= $rw_01->bobot;
					$bobotr1	= $rw_01->bobotr;		// Bobot Realisasi
					$htawar1	= $rw_01->htawar;
					$grpItm1 	= "S";

					$ISBBT1 	= 0;
					if($bobot1 > 0)
						$ISBBT1 = 1;

					$isL1 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
					$isHd1 		= 0;
					$s_chld1	= "$TABLENM1 WHERE induk = '$induk1'";
					$r_chld1	= $this->db->count_all($s_chld1);
					if($r_chld1 > 0)
					{
						$isHd1 	= 1;
						//$isL1 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
					}

					// START : INSERT INTO JOBLIST LEVEL 1
						$ordid 	= $ordid+1;

						// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
							$TJOB1 		= $htawar1;
							/*if($isL1 == 1)
							{
								$s_01a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek1' AND hr = 0";
								$r_01a 	= $this->db->query($s_01a)->result();
								foreach($r_01a as $rw_01a) :
									$TJOB1	= $rw_01a->judul;
								endforeach;
							}*/

						// INSERT DATA
							$s_ins1a	= "INSERT INTO tbl_boqlist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
												ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
												JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
												ISBOBOT, ISHEADER, ISLAST, Patt_Number)
											VALUES ($ordid, '$kodepek1', '$kodepek1', '$induk1', '$PRJCODE', '$PRJCODE_HO',
												'', '$namapek1','$grpItm1', '$satuan1', '$level1', '$volumer1', '$hargar1', 
												'$htawar1', '$volume_k1', '$harga_k1', '$nilai_k1', '$PRJCODE', '$PRJCODE_HO', '$bobot1', 
												'$ISBBT1', '$isHd1', '$isL1', '$ordid')";
							$this->db->query($s_ins1a);

							$s_ins1b	= "INSERT INTO tbl_joblist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
												PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
												PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
												BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
											VALUES ($ordid, '$kodepek1', '$kodepek1', '$induk1', '', '$PRJCODE',
												'$PRJCODE_HO', '', '$namapek1', '$grpItm1', '$satuan1', '$level1', '$volumer1', 
												'$hargar1', '$htawar1', '$volume_k1', '$harga_k1', '$PRJCODE', '$PRJCODE_HO',
												'$nilai_k1', '$bobot1', '$ISBBT1', '$isHd1', '$isL1', '$ordid')";
							$this->db->query($s_ins1b);

							$s_ins1c	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
												JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
												ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
												PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
												ISLAST, Patt_Number)
											VALUES ($ordid, '$kodepek1', '$kodepek1', '$induk1', '',
												'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek1',
												'$grpItm1','$grpItm1', '$satuan1', '$volumer1', '$hargar1', '$hargar1', 
												'$PRJCODE', '$PRJCODE_HO', '$htawar1', '$volume_k1', '$harga_k1', '$nilai_k1', '$level1', 
												'$isL1', '$ordid')";
							$this->db->query($s_ins1c);

						// START : BREAK DOWN ITEM
							$ordid_d1 	= 0;
							$s_01b		= "SELECT kelompok, koderes, volumer, hargar, nilair, kode_gab, koefisien, nama, satuan
											FROM $TABLENM2 WHERE kodepek = '$kodepek1' AND hr = 0";
							$r_01b 		= $this->db->query($s_01b)->result();
							foreach($r_01b as $rw_01b) :
								$ordid_d1 	= $ordid_d1+1;
								$kodepek_d1	= $kodepek1."-".$ordid_d1;
								$grp_d1		= addslashes($rw_01b->kelompok);
								$bahan_d1	= $rw_01b->koderes;			// Kode Item
								$volumer_d1	= $rw_01b->volumer;
								$hargar_d1	= $rw_01b->hargar;
								$nilair_d1	= $rw_01b->nilair;
								$koef_d1	= $rw_01b->koefisien;
								$nama_d1	= addslashes($rw_01b->nama);
								$satuan_d1	= addslashes($rw_01b->satuan);
								$level_d1 	= $level1+1;
								$isL_d1 	= 1;

								$volume_k_d1= 0;
								$harga_k_d1	= 0;
								$nilai_k_d1	= 0;

								$s_ins1d	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
													JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
													ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
													PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
													ISLAST, Patt_Number)
												VALUES ($ordid, '$kodepek_d1', '$kodepek_d1', '$kodepek1', '',
													'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d1', '$nama_d1',
													'$grp_d1','$grp_d1', '$satuan_d1', '$volumer_d1', '$hargar_d1', '$hargar_d1', 
													'$PRJCODE', '$PRJCODE_HO', '$nilair_d1', '$volume_k_d1', '$harga_k_d1', '$nilai_k_d1', '$level_d1', 
													'$isL_d1', '$ordid_d1')";
								$this->db->query($s_ins1d);
								
								$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
								$r_IMPD		= $this->db->count_all($s_IMPD);
								$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
								echo '<script>
								parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

							    ob_flush();
							    flush();
							endforeach;
							
							$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek1' AND hr = 0";
							$this->db->query($sdel_d);
						// END : BREAK DOWN ITEM
					// END : INSERT INTO JOBLIST LEVEL 1

					if($isHd1 == 1)
					{
						$s_02	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k, harga_k, nilai_k, pfc, bobot, bobotr, htawar
									FROM $TABLENM1 WHERE induk = '$kodepek1' ORDER BY kodepek";
						$r_02 	= $this->db->query($s_02)->result();
						foreach($r_02 as $rw_02) :
							$judul2		= $rw_02->judul;
							$level2		= $rw_02->level;
							$level2 	= 2;
							$kodepek2	= $rw_02->kodepek;
							$induk2		= $rw_02->induk;
							$namapek2	= addslashes($rw_02->namapek);
							$satuan2	= addslashes($rw_02->satuan);
							$volumer2	= $rw_02->volumer;
							$hargar2	= $rw_02->hargar;
							$volume_k2	= $rw_02->volume_k;
							$harga_k2	= $rw_02->harga_k;
							$nilai_k2	= $rw_02->nilai_k;
							$bobot2		= $rw_02->bobot;
							$bobotr2	= $rw_02->bobotr;		// Bobot Realisasi
							$htawar2	= $rw_02->htawar;
							$grpItm2 	= "S";

							$ISBBT2 	= 0;
							if($bobot2 > 0)
								$ISBBT2 = 1;

							$isL2 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
							$isHd2 		= 0;
							$s_chld2	= "$TABLENM1 WHERE induk = '$induk2'";
							$r_chld2	= $this->db->count_all($s_chld2);
							if($r_chld2 > 0)
							{
								$isHd2 	= 1;
								//$isL2 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
							}

							// START : INSERT INTO JOBLIST LEVEL 2
								$ordid 	= $ordid+1;

								// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
									$TJOB2 		= $htawar2;
									/*if($isL2 == 1)
									{
										$s_02a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek2' AND hr = 0";
										$r_02a 	= $this->db->query($s_02a)->result();
										foreach($r_02a as $rw_02a) :
											$TJOB2	= $rw_02a->judul;
										endforeach;
									}*/

								$s_ins2a	= "INSERT INTO tbl_boqlist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
													ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
													JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
													ISBOBOT, ISHEADER, ISLAST, Patt_Number)
												VALUES ($ordid, '$kodepek2', '$kodepek2', '$induk2', '$PRJCODE', '$PRJCODE_HO',
													'', '$namapek2','$grpItm2', '$satuan2', '$level2', '$volumer2', '$hargar2', 
													'$htawar2', '$volume_k2', '$harga_k2', '$nilai_k2', '$PRJCODE', '$PRJCODE_HO', '$bobot2', 
													'$ISBBT2', '$isHd2', '$isL2', '$ordid')";
								$this->db->query($s_ins2a);

								$s_ins2b	= "INSERT INTO tbl_joblist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
													PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
													PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
													BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
												VALUES ($ordid, '$kodepek2', '$kodepek2', '$induk2', '', '$PRJCODE',
													'$PRJCODE_HO', '', '$namapek2', '$grpItm2', '$satuan2', '$level2', '$volumer2', 
													'$hargar2', '$htawar2', '$volume_k2', '$harga_k2', '$PRJCODE', '$PRJCODE_HO',
													'$nilai_k2', '$bobot2', '$ISBBT2', '$isHd2', '$isL2', '$ordid')";
								$this->db->query($s_ins2b);

								$s_ins2c	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
													JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
													ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
													PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
													ISLAST, Patt_Number)
												VALUES ($ordid, '$kodepek2', '$kodepek2', '$induk2', '',
													'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek2',
													'$grpItm2','$grpItm2', '$satuan2', '$volumer2', '$hargar2', '$hargar2', 
													'$PRJCODE', '$PRJCODE_HO', '$htawar2', '$volume_k2', '$harga_k2', '$nilai_k2', '$level2', 
													'$isL2', '$ordid')";
								$this->db->query($s_ins2c);

								// START : BREAK DOWN ITEM
									$ordid_d2 	= 0;
									$s_02b		= "SELECT kelompok, koderes, volumer, hargar, nilair, kode_gab, koefisien, nama, satuan
													FROM $TABLENM2 WHERE kodepek = '$kodepek2' AND hr = 0";
									$r_02b 		= $this->db->query($s_02b)->result();
									foreach($r_02b as $rw_02b) :
										$ordid_d2 	= $ordid_d2+1;
										$kodepek_d2	= $kodepek2."-".$ordid_d2;
										$grp_d2		= addslashes($rw_02b->kelompok);
										$bahan_d2	= $rw_02b->koderes;			// Kode Item
										$volumer_d2	= $rw_02b->volumer;
										$hargar_d2	= $rw_02b->hargar;
										$nilair_d2	= $rw_02b->nilair;
										$koef_d2	= $rw_02b->koefisien;
										$nama_d2	= addslashes($rw_02b->nama);
										$satuan_d2	= addslashes($rw_02b->satuan);
										$level_d2 	= $level2+1;
										$isL_d2 	= 1;

										$volume_k_d2= 0;
										$harga_k_d2	= 0;
										$nilai_k_d2	= 0;

										$s_ins2d	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
															JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
															ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
															PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
															ISLAST, Patt_Number)
														VALUES ($ordid, '$kodepek_d2', '$kodepek_d2', '$kodepek2', '',
															'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d2', '$nama_d2',
															'$grp_d2','$grp_d2', '$satuan_d2', '$volumer_d2', '$hargar_d2', '$hargar_d2', 
															'$PRJCODE', '$PRJCODE_HO', '$nilair_d2', '$volume_k_d2', '$harga_k_d2', '$nilai_k_d2', '$level_d2', 
															'$isL_d2', '$ordid_d2')";
										$this->db->query($s_ins2d);
																				
										$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
										$r_IMPD		= $this->db->count_all($s_IMPD);
										$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
										echo '<script>
										parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

									    ob_flush();
									    flush();
									endforeach;
							
									/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek2' AND hr = 0";
									$this->db->query($sdel_d);*/
								// END : BREAK DOWN ITEM
							// END : INSERT INTO JOBLIST LEVEL 2

							if($isHd2 == 1)
							{
								$s_03	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k, harga_k, nilai_k,
												pfc, bobot, bobotr, htawar
											FROM $TABLENM1 WHERE induk = '$kodepek2' ORDER BY kodepek";
								$r_03 	= $this->db->query($s_03)->result();
								foreach($r_03 as $rw_03) :
									$judul3		= $rw_03->judul;
									$level3		= $rw_03->level;
									$level3 	= 3;
									$kodepek3	= $rw_03->kodepek;
									$induk3		= $rw_03->induk;
									$namapek3	= addslashes($rw_03->namapek);
									$satuan3	= addslashes($rw_03->satuan);
									$volumer3	= $rw_03->volumer;
									$hargar3	= $rw_03->hargar;
									$volume_k3	= $rw_03->volume_k;
									$harga_k3	= $rw_03->harga_k;
									$nilai_k3	= $rw_03->nilai_k;
									$bobot3		= $rw_03->bobot;
									$bobotr3	= $rw_03->bobotr;		// Bobot Realisasi
									$htawar3	= $rw_03->htawar;
									$grpItm3 	= "S";

									$ISBBT3 	= 0;
									if($bobot3 > 0)
										$ISBBT3 = 1;

									$isL3 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
									$isHd3 		= 0;
									$s_chld3	= "$TABLENM1 WHERE induk = '$induk3'";
									$r_chld3	= $this->db->count_all($s_chld3);
									if($r_chld3 > 0)
									{
										$isHd3 	= 1;
										//$isL3 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
									}

									// START : INSERT INTO JOBLIST LEVEL 3
										$ordid 	= $ordid+1;

										// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
											$TJOB3 		= $htawar3;
											/*if($isL3 == 1)
											{
												$s_03a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek3' AND hr = 0";
												$r_03a 	= $this->db->query($s_03a)->result();
												foreach($r_03a as $rw_03a) :
													$TJOB3	= $rw_03a->judul;
												endforeach;
											}*/

										$s_ins3a	= "INSERT INTO tbl_boqlist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
															ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
															JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
															ISBOBOT, ISHEADER, ISLAST, Patt_Number)
														VALUES ($ordid, '$kodepek3', '$kodepek3', '$induk3', '$PRJCODE', '$PRJCODE_HO',
															'', '$namapek3','$grpItm3', '$satuan3', '$level3', '$volumer3', '$hargar3', 
															'$htawar3', '$volume_k3', '$harga_k3', '$nilai_k3', '$PRJCODE', '$PRJCODE_HO', '$bobot3', 
															'$ISBBT3', '$isHd3', '$isL3', '$ordid')";
										$this->db->query($s_ins3a);

										$s_ins3b	= "INSERT INTO tbl_joblist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
															PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
															PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
															BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
														VALUES ($ordid, '$kodepek3', '$kodepek3', '$induk3', '', '$PRJCODE',
															'$PRJCODE_HO', '', '$namapek3', '$grpItm3', '$satuan3', '$level3', '$volumer3', 
															'$hargar3', '$htawar3', '$volume_k3', '$harga_k3', '$PRJCODE', '$PRJCODE_HO',
															'$nilai_k3', '$bobot3', '$ISBBT3', '$isHd3', '$isL3', '$ordid')";
										$this->db->query($s_ins3b);

										$s_ins3c	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
															JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
															ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
															PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
															ISLAST, Patt_Number)
														VALUES ($ordid, '$kodepek3', '$kodepek3', '$induk3', '',
															'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek3',
															'$grpItm3','$grpItm3', '$satuan3', '$volumer3', '$hargar3', '$hargar3', 
															'$PRJCODE', '$PRJCODE_HO', '$htawar3', '$volume_k3', '$harga_k3', '$nilai_k3', '$level3', 
															'$isL3', '$ordid')";
										$this->db->query($s_ins3c);

										// START : BREAK DOWN ITEM
											$ordid_d3 	= 0;
											$s_03b		= "SELECT kelompok, koderes, volumer, hargar, nilair, kode_gab, koefisien, nama, satuan
															FROM $TABLENM2 WHERE kodepek = '$kodepek3' AND hr = 0";
											$r_03b 		= $this->db->query($s_03b)->result();
											foreach($r_03b as $rw_03b) :
												$ordid_d3 	= $ordid_d3+1;
												$kodepek_d3	= $kodepek3."-".$ordid_d3;
												$grp_d3		= addslashes($rw_03b->kelompok);
												$bahan_d3	= $rw_03b->koderes;			// Kode Item
												$volumer_d3	= $rw_03b->volumer;
												$hargar_d3	= $rw_03b->hargar;
												$nilair_d3	= $rw_03b->nilair;
												$koef_d3	= $rw_03b->koefisien;
												$nama_d3	= addslashes($rw_03b->nama);
												$satuan_d3	= addslashes($rw_03b->satuan);
												$level_d3 	= $level3+1;
												$isL_d3 	= 1;

												$volume_k_d3= 0;
												$harga_k_d3	= 0;
												$nilai_k_d3	= 0;

												$s_ins3d	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																	JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																	ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																	PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																	ISLAST, Patt_Number)
																VALUES ($ordid, '$kodepek_d3', '$kodepek_d3', '$kodepek3', '',
																	'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d3', '$nama_d3',
																	'$grp_d3','$grp_d3', '$satuan_d3', '$volumer_d3', '$hargar_d3', '$hargar_d3', 
																	'$PRJCODE', '$PRJCODE_HO', '$nilair_d3', '$volume_k_d3', '$harga_k_d3', '$nilai_k_d3', '$level_d3', 
																	'$isL_d3', '$ordid_d3')";
												$this->db->query($s_ins3d);
																				
												$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
												$r_IMPD		= $this->db->count_all($s_IMPD);
												$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
												echo '<script>
												parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

											    ob_flush();
											    flush();
											endforeach;
							
											/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek3' AND hr = 0";
											$this->db->query($sdel_d);*/
										// END : BREAK DOWN ITEM
									// END : INSERT INTO JOBLIST LEVEL 3

									if($isHd3 == 1)
									{
										$s_04	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k, harga_k, nilai_k,
														pfc, bobot, bobotr, htawar
													FROM $TABLENM1 WHERE induk = '$kodepek3' ORDER BY kodepek";
										$r_04 	= $this->db->query($s_04)->result();
										foreach($r_04 as $rw_04) :
											$judul4		= $rw_04->judul;
											$level4		= $rw_04->level;
											$level4 	= 4;
											$kodepek4	= $rw_04->kodepek;
											$induk4		= $rw_04->induk;
											$namapek4	= addslashes($rw_04->namapek);
											$satuan4	= addslashes($rw_04->satuan);
											$volumer4	= $rw_04->volumer;
											$hargar4	= $rw_04->hargar;
											$volume_k4	= $rw_04->volume_k;
											$harga_k4	= $rw_04->harga_k;
											$nilai_k4	= $rw_04->nilai_k;
											$bobot4		= $rw_04->bobot;
											$bobotr4	= $rw_04->bobotr;		// Bobot Realisasi
											$htawar4	= $rw_04->htawar;
											$grpItm4 	= "S";

											$ISBBT4 	= 0;
											if($bobot4 > 0)
												$ISBBT4 = 1;

											$isL4 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
											$isHd4 		= 0;
											$s_chld4	= "$TABLENM1 WHERE induk = '$induk4'";
											$r_chld4	= $this->db->count_all($s_chld4);
											if($r_chld4 > 0)
											{
												$isHd4 	= 1;
												//$isL4 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
											}

											// START : INSERT INTO JOBLIST LEVEL 4
												$ordid 	= $ordid+1;

												// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
													$TJOB4 		= $htawar4;
													/*if($isL4 == 1)
													{
														$s_04a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek4' AND hr = 0";
														$r_04a 	= $this->db->query($s_04a)->result();
														foreach($r_04a as $rw_04a) :
															$TJOB4	= $rw_04a->judul;
														endforeach;
													}*/

												$s_ins4a	= "INSERT INTO tbl_boqlist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
																	ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
																	JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
																	ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																VALUES ($ordid, '$kodepek4', '$kodepek4', '$induk4', '$PRJCODE', '$PRJCODE_HO',
																	'', '$namapek4','$grpItm4', '$satuan4', '$level4', '$volumer4', '$hargar4', 
																	'$htawar4', '$volume_k4', '$harga_k4', '$nilai_k4', '$PRJCODE', '$PRJCODE_HO', '$bobot4', 
																	'$ISBBT4', '$isHd4', '$isL4', '$ordid')";
												$this->db->query($s_ins4a);

												$s_ins4b	= "INSERT INTO tbl_joblist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
																	PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
																	PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
																	BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																VALUES ($ordid, '$kodepek4', '$kodepek4', '$induk4', '', '$PRJCODE',
																	'$PRJCODE_HO', '', '$namapek4', '$grpItm4', '$satuan4', '$level4', '$volumer4', 
																	'$hargar4', '$htawar4', '$volume_k4', '$harga_k4', '$PRJCODE', '$PRJCODE_HO',
																	'$nilai_k4', '$bobot4', '$ISBBT4', '$isHd4', '$isL4', '$ordid')";
												$this->db->query($s_ins4b);

												$s_ins4c	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																	JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																	ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																	PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																	ISLAST, Patt_Number)
																VALUES ($ordid, '$kodepek4', '$kodepek4', '$induk4', '',
																	'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek4',
																	'$grpItm4','$grpItm4', '$satuan4', '$volumer4', '$hargar4', '$hargar4', 
																	'$PRJCODE', '$PRJCODE_HO', '$htawar4', '$volume_k4', '$harga_k4', '$nilai_k4', '$level4', 
																	'$isL4', '$ordid')";
												$this->db->query($s_ins4c);

												// START : BREAK DOWN ITEM
													$ordid_d4 	= 0;
													$s_04b		= "SELECT kelompok, koderes, volumer, hargar, nilair, kode_gab, koefisien, nama, satuan
																	FROM $TABLENM2 WHERE kodepek = '$kodepek4' AND hr = 0";
													$r_04b 		= $this->db->query($s_04b)->result();
													foreach($r_04b as $rw_04b) :
														$ordid_d4 	= $ordid_d4+1;
														$kodepek_d4	= $kodepek4."-".$ordid_d4;
														$grp_d4		= addslashes($rw_04b->kelompok);
														$bahan_d4	= $rw_04b->koderes;			// Kode Item
														$volumer_d4	= $rw_04b->volumer;
														$hargar_d4	= $rw_04b->hargar;
														$nilair_d4	= $rw_04b->nilair;
														$koef_d4	= $rw_04b->koefisien;
														$nama_d4	= addslashes($rw_04b->nama);
														$satuan_d4	= addslashes($rw_04b->satuan);
														$level_d4 	= $level4+1;
														$isL_d4 	= 1;

														$volume_k_d4= 0;
														$harga_k_d4	= 0;
														$nilai_k_d4	= 0;

														$s_ins4d	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																			JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																			ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																			PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																			ISLAST, Patt_Number)
																		VALUES ($ordid, '$kodepek_d4', '$kodepek_d4', '$kodepek4', '',
																			'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d4', '$nama_d4',
																			'$grp_d4','$grp_d4', '$satuan_d4', '$volumer_d4', '$hargar_d4', '$hargar_d4', 
																			'$PRJCODE', '$PRJCODE_HO', '$nilair_d4', '$volume_k_d4', '$harga_k_d4', '$nilai_k_d4', '$level_d4', 
																			'$isL_d4', '$ordid_d4')";
														$this->db->query($s_ins4d);
																				
														$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
														$r_IMPD		= $this->db->count_all($s_IMPD);
														$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
														echo '<script>
														parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

													    ob_flush();
													    flush();
													endforeach;
							
													/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek4' AND hr = 0";
													$this->db->query($sdel_d);*/
												// END : BREAK DOWN ITEM
											// END : INSERT INTO JOBLIST LEVEL 4

											if($isHd4 == 1)
											{
												$s_05	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k, harga_k, nilai_k,
																pfc, bobot, bobotr, htawar
															FROM $TABLENM1 WHERE induk = '$kodepek4' ORDER BY kodepek";
												$r_05 	= $this->db->query($s_05)->result();
												foreach($r_05 as $rw_05) :
													$judul5		= $rw_05->judul;
													$level5		= $rw_05->level;
													$level5 	= 5;
													$kodepek5	= $rw_05->kodepek;
													$induk5		= $rw_05->induk;
													$namapek5	= addslashes($rw_05->namapek);
													$satuan5	= addslashes($rw_05->satuan);
													$volumer5	= $rw_05->volumer;
													$hargar5	= $rw_05->hargar;
													$volume_k5	= $rw_05->volume_k;
													$harga_k5	= $rw_05->harga_k;
													$nilai_k5	= $rw_05->nilai_k;
													$bobot5		= $rw_05->bobot;
													$bobotr5	= $rw_05->bobotr;		// Bobot Realisasi
													$htawar5	= $rw_05->htawar;
													$grpItm5 	= "S";

													$ISBBT5 	= 0;
													if($bobot5 > 0)
														$ISBBT5 = 1;

													$isL5 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
													$isHd5 		= 0;
													$s_chld5	= "$TABLENM1 WHERE induk = '$induk5'";
													$r_chld5	= $this->db->count_all($s_chld5);
													if($r_chld5 > 0)
													{
														$isHd5 	= 1;
														//$isL5 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
													}

													// START : INSERT INTO JOBLIST LEVEL 5
														$ordid 	= $ordid+1;

														// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
															$TJOB5 		= $htawar5;
															/*if($isL5 == 1)
															{
																$s_05a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek5' AND hr = 0";
																$r_05a 	= $this->db->query($s_05a)->result();
																foreach($r_05a as $rw_05a) :
																	$TJOB5	= $rw_05a->judul;
																endforeach;
															}*/

														$s_ins5a	= "INSERT INTO tbl_boqlist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
																			ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
																			JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
																			ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																		VALUES ($ordid, '$kodepek5', '$kodepek5', '$induk5', '$PRJCODE', '$PRJCODE_HO',
																			'', '$namapek5','$grpItm5', '$satuan5', '$level5', '$volumer5', '$hargar5', 
																			'$htawar5', '$volume_k5', '$harga_k5', '$nilai_k5', '$PRJCODE', '$PRJCODE_HO', '$bobot5', 
																			'$ISBBT5', '$isHd5', '$isL5', '$ordid')";
														$this->db->query($s_ins5a);

														$s_ins5b	= "INSERT INTO tbl_joblist
																			(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
																			PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
																			PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
																			BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																		VALUES ($ordid, '$kodepek5', '$kodepek5', '$induk5', '', '$PRJCODE',
																			'$PRJCODE_HO', '', '$namapek5', '$grpItm5', '$satuan5',
																			'$level5', '$volumer5', 
																			'$hargar5', '$htawar5', '$volume_k5', '$harga_k5', '$PRJCODE', '$PRJCODE_HO',
																			'$nilai_k5', '$bobot5', '$ISBBT5', '$isHd5', '$isL5', '$ordid')";
														$this->db->query($s_ins5b);

														$s_ins5c	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																			JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																			ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																			PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																			ISLAST, Patt_Number)
																		VALUES ($ordid, '$kodepek5', '$kodepek5', '$induk5', '',
																			'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek5',
																			'$grpItm5','$grpItm5', '$satuan5', '$volumer5', '$hargar5', '$hargar5', 
																			'$PRJCODE', '$PRJCODE_HO', '$htawar5', '$volume_k5', '$harga_k5', '$nilai_k5', '$level5', 
																			'$isL5', '$ordid')";
														$this->db->query($s_ins5c);

														// START : BREAK DOWN ITEM
															$ordid_d5 	= 0;
															$s_05b		= "SELECT kelompok, koderes, volumer, hargar, nilair, kode_gab, koefisien, nama, satuan
																			FROM $TABLENM2 WHERE kodepek = '$kodepek5' AND hr = 0";
															$r_05b 		= $this->db->query($s_05b)->result();
															foreach($r_05b as $rw_05b) :
																$ordid_d5 	= $ordid_d5+1;
																$kodepek_d5	= $kodepek5."-".$ordid_d5;
																$grp_d5		= addslashes($rw_05b->kelompok);
																$bahan_d5	= $rw_05b->koderes;			// Kode Item
																$volumer_d5	= $rw_05b->volumer;
																$hargar_d5	= $rw_05b->hargar;
																$nilair_d5	= $rw_05b->nilair;
																$koef_d5	= $rw_05b->koefisien;
																$nama_d5	= addslashes($rw_05b->nama);
																$satuan_d5	= addslashes($rw_05b->satuan);
																$level_d5 	= $level5+1;
																$isL_d5 	= 1;

																$volume_k_d5= 0;
																$harga_k_d5	= 0;
																$nilai_k_d5	= 0;

																$s_ins5d	= "INSERT INTO tbl_joblist_detail
																					(ORD_ID, JOBCODEDET, JOBCODEID,JOBPARENT, JOBCODEID_P,
																					JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																					ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																					PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																					ISLAST, Patt_Number)
																				VALUES ($ordid, '$kodepek_d5', '$kodepek_d5', '$kodepek5', '',
																					'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d5', '$nama_d5',
																					'$grp_d5','$grp_d5', '$satuan_d5', '$volumer_d5', '$hargar_d5', '$hargar_d5', 
																					'$PRJCODE', '$PRJCODE_HO', '$nilair_d5', '$volume_k_d5', '$harga_k_d5', '$nilai_k_d5', '$level_d5', 
																					'$isL_d5', '$ordid_d5')";
																$this->db->query($s_ins5d);
																				
																$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
																$r_IMPD		= $this->db->count_all($s_IMPD);
																$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
																echo '<script>
																parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

															    ob_flush();
															    flush();
															endforeach;
							
															/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek5' AND hr = 0";
															$this->db->query($sdel_d);*/
														// END : BREAK DOWN ITEM
													// END : INSERT INTO JOBLIST LEVEL 5

													if($isHd5 == 1)
													{
														$s_06	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k, harga_k, nilai_k,
																		pfc, bobot, bobotr, htawar
																	FROM $TABLENM1 WHERE induk = '$kodepek5' ORDER BY kodepek";
														$r_06 	= $this->db->query($s_06)->result();
														foreach($r_06 as $rw_06) :
															$judul6		= $rw_06->judul;
															$level6		= $rw_06->level;
															$level6 	= 6;
															$kodepek6	= $rw_06->kodepek;
															$induk6		= $rw_06->induk;
															$namapek6	= addslashes($rw_06->namapek);
															$satuan6	= addslashes($rw_06->satuan);
															$volumer6	= $rw_06->volumer;
															$hargar6	= $rw_06->hargar;
															$volume_k6	= $rw_06->volume_k;
															$harga_k6	= $rw_06->harga_k;
															$nilai_k6	= $rw_06->nilai_k;
															$bobot6		= $rw_06->bobot;
															$bobotr6	= $rw_06->bobotr;		// Bobot Realisasi
															$htawar6	= $rw_06->htawar;
															$grpItm6 	= "S";

															$ISBBT6 	= 0;
															if($bobot6 > 0)
																$ISBBT6 = 1;

															$isL6 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
															$isHd6 		= 0;
															$s_chld6	= "$TABLENM1 WHERE induk = '$induk6'";
															$r_chld6	= $this->db->count_all($s_chld6);
															if($r_chld6 > 0)
															{
																$isHd6 	= 1;
																//$isL6 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
															}

															// START : INSERT INTO JOBLIST LEVEL 6
																$ordid 	= $ordid+1;

																// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
																	$TJOB6 		= $htawar6;
																	/*if($isL6 == 1)
																	{
																		$s_06a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek6' AND hr = 0";
																		$r_06a 	= $this->db->query($s_06a)->result();
																		foreach($r_06a as $rw_06a) :
																			$TJOB6	= $rw_06a->judul;
																		endforeach;
																	}*/

																$s_ins6a	= "INSERT INTO tbl_boqlist
																					(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
																					ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
																					JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
																					ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																				VALUES ($ordid, '$kodepek6', '$kodepek6', '$induk6', '$PRJCODE', '$PRJCODE_HO',
																					'', '$namapek6','$grpItm6', '$satuan6', '$level6', '$volumer6', '$hargar6', 
																					'$htawar6', '$volume_k6', '$harga_k6', '$nilai_k6', '$PRJCODE', '$PRJCODE_HO', '$bobot6', 
																					'$ISBBT6', '$isHd6', '$isL6', '$ordid')";
																$this->db->query($s_ins6a);

																$s_ins6b	= "INSERT INTO tbl_joblist
																					(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
																					PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
																					PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
																					BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																				VALUES ($ordid, '$kodepek6', '$kodepek6', '$induk6', '', '$PRJCODE',
																					'$PRJCODE_HO', '', '$namapek6', '$grpItm6', '$satuan6',
																					'$level6', '$volumer6', 
																					'$hargar6', '$htawar6', '$volume_k6', '$harga_k6', '$PRJCODE', '$PRJCODE_HO',
																					'$nilai_k6', '$bobot6', '$ISBBT6', '$isHd6', '$isL6', '$ordid')";
																$this->db->query($s_ins6b);

																$s_ins6c	= "INSERT INTO tbl_joblist_detail
																					(ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																					JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																					ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																					PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																					ISLAST, Patt_Number)
																				VALUES ($ordid, '$kodepek6', '$kodepek6', '$induk6', '',
																					'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek6',
																					'$grpItm6','$grpItm6', '$satuan6', '$volumer6', '$hargar6', '$hargar6', 
																					'$PRJCODE', '$PRJCODE_HO', '$htawar6', '$volume_k6', '$harga_k6', '$nilai_k6', '$level6', 
																					'$isL6', '$ordid')";
																$this->db->query($s_ins6c);

																// START : BREAK DOWN ITEM
																	$ordid_d6 	= 0;
																	$s_06b		= "SELECT kelompok, koderes, volumer, hargar, nilair, kode_gab, koefisien,
																						nama, satuan
																					FROM $TABLENM2 WHERE kodepek = '$kodepek6' AND hr = 0";
																	$r_06b 		= $this->db->query($s_06b)->result();
																	foreach($r_06b as $rw_06b) :
																		$ordid_d6 	= $ordid_d6+1;
																		$kodepek_d6	= $kodepek6."-".$ordid_d6;
																		$grp_d6		= addslashes($rw_06b->kelompok);
																		$bahan_d6	= $rw_06b->koderes;			// Kode Item
																		$volumer_d6	= $rw_06b->volumer;
																		$hargar_d6	= $rw_06b->hargar;
																		$nilair_d6	= $rw_06b->nilair;
																		$koef_d6	= $rw_06b->koefisien;
																		$nama_d6	= addslashes($rw_06b->nama);
																		$satuan_d6	= addslashes($rw_06b->satuan);
																		$level_d6 	= $level6+1;
																		$isL_d6 	= 1;

																		$volume_k_d6= 0;
																		$harga_k_d6	= 0;
																		$nilai_k_d6	= 0;

																		$s_ins6d	= "INSERT INTO tbl_joblist_detail
																							(ORD_ID, JOBCODEDET, JOBCODEID,JOBPARENT, JOBCODEID_P,
																							JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																							ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																							PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																							ISLAST, Patt_Number)
																						VALUES ($ordid, '$kodepek_d6', '$kodepek_d6', '$kodepek6', '',
																							'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d6', '$nama_d6',
																							'$grp_d6','$grp_d6', '$satuan_d6', '$volumer_d6', '$hargar_d6', '$hargar_d6', 
																							'$PRJCODE', '$PRJCODE_HO', '$nilair_d6', '$volume_k_d6', '$harga_k_d6', '$nilai_k_d6', '$level_d6', 
																							'$isL_d6', '$ordid_d6')";
																		$this->db->query($s_ins6d);

																		$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
																		$r_IMPD		= $this->db->count_all($s_IMPD);
																		$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
																		echo '<script>
																		parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

																	    ob_flush();
																	    flush();
																	endforeach;
							
																	/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek6' AND hr = 0";
																	$this->db->query($sdel_d);*/
																// END : BREAK DOWN ITEM
															// END : INSERT INTO JOBLIST LEVEL 6

															if($isHd6 == 1)
															{
																$s_07	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k,
																				harga_k, nilai_k, pfc, bobot, bobotr, htawar
																			FROM $TABLENM1 WHERE induk = '$kodepek6' ORDER BY kodepek";
																$r_07 	= $this->db->query($s_07)->result();
																foreach($r_07 as $rw_07) :
																	$judul7		= $rw_07->judul;
																	$level7		= $rw_07->level;
																	$level7 	= 7;
																	$kodepek7	= $rw_07->kodepek;
																	$induk7		= $rw_07->induk;
																	$namapek7	= addslashes($rw_07->namapek);
																	$satuan7	= addslashes($rw_07->satuan);
																	$volumer7	= $rw_07->volumer;
																	$hargar7	= $rw_07->hargar;
																	$volume_k7	= $rw_07->volume_k;
																	$harga_k7	= $rw_07->harga_k;
																	$nilai_k7	= $rw_07->nilai_k;
																	$bobot7		= $rw_07->bobot;
																	$bobotr7	= $rw_07->bobotr;		// Bobot Realisasi
																	$htawar7	= $rw_07->htawar;
																	$grpItm7 	= "S";

																	$ISBBT7 	= 0;
																	if($bobot7 > 0)
																		$ISBBT7 = 1;

																	$isL7 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
																	$isHd7 		= 0;
																	$s_chld7	= "$TABLENM1 WHERE induk = '$induk7'";
																	$r_chld7	= $this->db->count_all($s_chld7);
																	if($r_chld7 > 0)
																	{
																		$isHd7 	= 1;
																		//$isL7 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
																	}

																	// START : INSERT INTO JOBLIST LEVEL 7
																		$ordid 	= $ordid+1;

																		// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
																			$TJOB7 		= $htawar7;
																			/*if($isL7 == 1)
																			{
																				$s_07a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek7' AND hr = 0";
																				$r_07a 	= $this->db->query($s_07a)->result();
																				foreach($r_07a as $rw_07a) :
																					$TJOB7	= $rw_07a->judul;
																				endforeach;
																			}*/

																		$s_ins7a	= "INSERT INTO tbl_boqlist
																							(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
																							ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
																							JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
																							ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																						VALUES ($ordid, '$kodepek7', '$kodepek7', '$induk7', '$PRJCODE', '$PRJCODE_HO',
																							'', '$namapek7','$grpItm7', '$satuan7', '$level7', '$volumer7', '$hargar7', 
																							'$htawar7', '$volume_k7', '$harga_k7', '$nilai_k7', '$PRJCODE', '$PRJCODE_HO', '$bobot7', 
																							'$ISBBT7', '$isHd7', '$isL7', '$ordid')";
																		$this->db->query($s_ins7a);

																		$s_ins7b	= "INSERT INTO tbl_joblist
																							(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
																							PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
																							PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
																							BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																						VALUES ($ordid, '$kodepek7', '$kodepek7', '$induk7', '', '$PRJCODE',
																							'$PRJCODE_HO', '', '$namapek7', '$grpItm7', '$satuan7',
																							'$level7', '$volumer7', 
																							'$hargar7', '$htawar7', '$volume_k7', '$harga_k7', '$PRJCODE', '$PRJCODE_HO',
																							'$nilai_k7', '$bobot7', '$ISBBT7', '$isHd7', '$isL7', '$ordid')";
																		$this->db->query($s_ins7b);

																		$s_ins7c	= "INSERT INTO tbl_joblist_detail
																							(ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																							JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																							ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																							PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																							ISLAST, Patt_Number)
																						VALUES ($ordid, '$kodepek7', '$kodepek7', '$induk7', '',
																							'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek7',
																							'$grpItm7','$grpItm7', '$satuan7', '$volumer7', '$hargar7', '$hargar7', 
																							'$PRJCODE', '$PRJCODE_HO', '$htawar7', '$volume_k7', '$harga_k7', '$nilai_k7', '$level7', 
																							'$isL7', '$ordid')";
																		$this->db->query($s_ins7c);

																		// START : BREAK DOWN ITEM
																			$ordid_d7 	= 0;
																			$s_07b		= "SELECT kelompok, koderes, volumer, hargar, nilair, kode_gab, koefisien,
																								nama, satuan
																							FROM $TABLENM2 WHERE kodepek = '$kodepek7' AND hr = 0";
																			$r_07b 		= $this->db->query($s_07b)->result();
																			foreach($r_07b as $rw_07b) :
																				$ordid_d7 	= $ordid_d7+1;
																				$kodepek_d7	= $kodepek7."-".$ordid_d7;
																				$grp_d7		= addslashes($rw_07b->kelompok);
																				$bahan_d7	= $rw_07b->koderes;			// Kode Item
																				$volumer_d7	= $rw_07b->volumer;
																				$hargar_d7	= $rw_07b->hargar;
																				$nilair_d7	= $rw_07b->nilair;
																				$koef_d7	= $rw_07b->koefisien;
																				$nama_d7	= addslashes($rw_07b->nama);
																				$satuan_d7	= addslashes($rw_07b->satuan);
																				$level_d7 	= $level7+1;
																				$isL_d7 	= 1;

																				$volume_k_d7= 0;
																				$harga_k_d7	= 0;
																				$nilai_k_d7	= 0;

																				$s_ins7d	= "INSERT INTO tbl_joblist_detail
																									(ORD_ID, JOBCODEDET, JOBCODEID,JOBPARENT, JOBCODEID_P,
																									JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																									ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																									PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																									ISLAST, Patt_Number)
																								VALUES ($ordid, '$kodepek_d7', '$kodepek_d7', '$kodepek7', '',
																									'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d7', '$nama_d7',
																									'$grp_d7','$grp_d7', '$satuan_d7', '$volumer_d7', '$hargar_d7', '$hargar_d7', 
																									'$PRJCODE', '$PRJCODE_HO', '$nilair_d7', '$volume_k_d7', '$harga_k_d7', '$nilai_k_d7', '$level_d7', 
																									'$isL_d7', '$ordid_d7')";
																				$this->db->query($s_ins7d);
																				
																				$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
																				$r_IMPD		= $this->db->count_all($s_IMPD);
																				$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
																				echo '<script>
																				parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

																			    ob_flush();
																			    flush();
																			endforeach;
							
																			/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek8' AND hr = 0";
																			$this->db->query($sdel_d);*/
																		// END : BREAK DOWN ITEM
																	// END : INSERT INTO JOBLIST LEVEL 8

																	if($isHd7 == 1)
																	{
																		$s_08	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k,
																						harga_k, nilai_k, pfc, bobot, bobotr, htawar
																					FROM $TABLENM1 WHERE induk = '$kodepek7' ORDER BY kodepek";
																		$r_08 	= $this->db->query($s_08)->result();
																		foreach($r_08 as $rw_08) :
																			$judul8		= $rw_08->judul;
																			$level8		= $rw_08->level;
																			$level8 	= 8;
																			$kodepek8	= $rw_08->kodepek;
																			$induk8		= $rw_08->induk;
																			$namapek8	= addslashes($rw_08->namapek);
																			$satuan8	= addslashes($rw_08->satuan);
																			$volumer8	= $rw_08->volumer;
																			$hargar8	= $rw_08->hargar;
																			$volume_k8	= $rw_08->volume_k;
																			$harga_k8	= $rw_08->harga_k;
																			$nilai_k8	= $rw_08->nilai_k;
																			$bobot8		= $rw_08->bobot;
																			$bobotr8	= $rw_08->bobotr;		// Bobot Realisasi
																			$htawar8	= $rw_08->htawar;
																			$grpItm8 	= "S";

																			$ISBBT8 	= 0;
																			if($bobot8 > 0)
																				$ISBBT8 = 1;

																			$isL8 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
																			$isHd8 		= 0;
																			$s_chld8	= "$TABLENM1 WHERE induk = '$induk8'";
																			$r_chld8	= $this->db->count_all($s_chld8);
																			if($r_chld8 > 0)
																			{
																				$isHd8 	= 1;
																				//$isL8 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
																			}

																			// START : INSERT INTO JOBLIST LEVEL 8
																				$ordid 	= $ordid+1;

																				// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
																					$TJOB8 		= $htawar8;
																					/*if($isL8 == 1)
																					{
																						$s_08a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek8' AND hr = 0";
																						$r_08a 	= $this->db->query($s_08a)->result();
																						foreach($r_08a as $rw_08a) :
																							$TJOB8	= $rw_08a->judul;
																						endforeach;
																					}*/

																				$s_ins8a	= "INSERT INTO tbl_boqlist
																									(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
																									ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
																									JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
																									ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																								VALUES ($ordid, '$kodepek8', '$kodepek8', '$induk8', '$PRJCODE', '$PRJCODE_HO',
																									'', '$namapek8','$grpItm8', '$satuan8', '$level8', '$volumer8', '$hargar8', 
																									'$htawar8', '$volume_k8', '$harga_k8', '$nilai_k8', '$PRJCODE', '$PRJCODE_HO', '$bobot8', 
																									'$ISBBT8', '$isHd8', '$isL8', '$ordid')";
																				$this->db->query($s_ins8a);

																				$s_ins8b	= "INSERT INTO tbl_joblist
																									(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
																									PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
																									PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
																									BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																								VALUES ($ordid, '$kodepek8', '$kodepek8', '$induk8', '', '$PRJCODE',
																									'$PRJCODE_HO', '', '$namapek8', '$grpItm8', '$satuan8',
																									'$level8', '$volumer8', 
																									'$hargar8', '$htawar8', '$volume_k8', '$harga_k8', '$PRJCODE', '$PRJCODE_HO',
																									'$nilai_k8', '$bobot8', '$ISBBT8', '$isHd8', '$isL8', '$ordid')";
																				$this->db->query($s_ins8b);

																				$s_ins8c	= "INSERT INTO tbl_joblist_detail
																									(ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																									JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																									ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																									PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																									ISLAST, Patt_Number)
																								VALUES ($ordid, '$kodepek8', '$kodepek8', '$induk8', '',
																									'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek8',
																									'$grpItm8','$grpItm8', '$satuan8', '$volumer8', '$hargar8', '$hargar8', 
																									'$PRJCODE', '$PRJCODE_HO', '$htawar8', '$volume_k8', '$harga_k8', '$nilai_k8', '$level8', 
																									'$isL8', '$ordid')";
																				$this->db->query($s_ins8c);

																				// START : BREAK DOWN ITEM
																					$ordid_d8 	= 0;
																					$s_08b		= "SELECT kelompok, koderes, volumer, hargar, nilair, kode_gab, 
																										koefisien, nama, satuan
																									FROM $TABLENM2 WHERE kodepek = '$kodepek8' AND hr = 0";
																					$r_08b 		= $this->db->query($s_08b)->result();
																					foreach($r_08b as $rw_08b) :
																						$ordid_d8 	= $ordid_d8+1;
																						$kodepek_d8	= $kodepek8."-".$ordid_d8;
																						$grp_d8		= addslashes($rw_08b->kelompok);
																						$bahan_d8	= $rw_08b->koderes;			// Kode Item
																						$volumer_d8	= $rw_08b->volumer;
																						$hargar_d8	= $rw_08b->hargar;
																						$nilair_d8	= $rw_08b->nilair;
																						$koef_d8	= $rw_08b->koefisien;
																						$nama_d8	= addslashes($rw_08b->nama);
																						$satuan_d8	= addslashes($rw_08b->satuan);
																						$level_d8 	= $level8+1;
																						$isL_d8 	= 1;

																						$volume_k_d8= 0;
																						$harga_k_d8	= 0;
																						$nilai_k_d8	= 0;

																						$s_ins8d	= "INSERT INTO tbl_joblist_detail
																											(ORD_ID, JOBCODEDET, JOBCODEID,JOBPARENT, JOBCODEID_P,
																											JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																											ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																											PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																											ISLAST, Patt_Number)
																										VALUES ($ordid, '$kodepek_d8', '$kodepek_d8', '$kodepek8', '',
																											'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d8', '$nama_d8',
																											'$grp_d8','$grp_d8', '$satuan_d8', '$volumer_d8', '$hargar_d8', '$hargar_d8', 
																											'$PRJCODE', '$PRJCODE_HO', '$nilair_d8', '$volume_k_d8', '$harga_k_d8', '$nilai_k_d8', '$level_d8', 
																											'$isL_d8', '$ordid_d8')";
																						$this->db->query($s_ins8d);
																					
																						$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
																						$r_IMPD		= $this->db->count_all($s_IMPD);
																						$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
																						echo '<script>
																						parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

																					    ob_flush();
																					    flush();
																					endforeach;
							
																					/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek8' AND hr = 0";
																					$this->db->query($sdel_d);*/
																				// END : BREAK DOWN ITEM
																			// END : INSERT INTO JOBLIST LEVEL 8

																			if($isHd8 == 1)
																			{
																				$s_09	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, volume_k,
																								harga_k, nilai_k, pfc, bobot, bobotr, htawar
																							FROM $TABLENM1 WHERE induk = '$kodepek8' ORDER BY kodepek";
																				$r_09 	= $this->db->query($s_09)->result();
																				foreach($r_09 as $rw_09) :
																					$judul9	= $rw_09->judul;
																					$level9	= $rw_09->level;
																					$level9 	= 9;
																					$kodepek9	= $rw_09->kodepek;
																					$induk9	= $rw_09->induk;
																					$namapek9	= addslashes($rw_09->namapek);
																					$satuan9	= addslashes($rw_09->satuan);
																					$volumer9	= $rw_09->volumer;
																					$hargar9	= $rw_09->hargar;
																					$volume_k9	= $rw_09->volume_k;
																					$harga_k9	= $rw_09->harga_k;
																					$nilai_k9	= $rw_09->nilai_k;
																					$bobot9	= $rw_09->bobot;
																					$bobotr9	= $rw_09->bobotr;		// Bobot Realisasi
																					$htawar9	= $rw_09->htawar;
																					$grpItm9 	= "S";

																					$ISBBT9 	= 0;
																					if($bobot9 > 0)
																						$ISBBT9 = 1;

																					$isL9 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
																					$isHd9 		= 0;
																					$s_chld9	= "$TABLENM1 WHERE induk = '$induk9'";
																					$r_chld9	= $this->db->count_all($s_chld9);
																					if($r_chld9 > 0)
																					{
																						$isHd9 	= 1;
																						//$isL9 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
																					}

																					// START : INSERT INTO JOBLIST LEVEL 9
																						$ordid 	= $ordid+1;

																						// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
																							$TJOB9 		= $htawar9;
																							/*if($isL9 == 1)
																							{
																								$s_09a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek9' AND hr = 0";
																								$r_09a 	= $this->db->query($s_09a)->result();
																								foreach($r_09a as $rw_09a) :
																									$TJOB9	= $rw_09a->judul;
																								endforeach;
																							}*/

																						$s_ins9a	= "INSERT INTO tbl_boqlist
																											(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
																											ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
																											JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
																											ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																										VALUES ($ordid, '$kodepek9', '$kodepek9', '$induk9', '$PRJCODE', '$PRJCODE_HO',
																											'', '$namapek9','$grpItm9', '$satuan9', '$level9', '$volumer9', '$hargar9', 
																											'$htawar9', '$volume_k9', '$harga_k9', '$nilai_k9', '$PRJCODE', '$PRJCODE_HO', '$bobot9', 
																											'$ISBBT9', '$isHd9', '$isL9', '$ordid')";
																						$this->db->query($s_ins9a);

																						$s_ins9b	= "INSERT INTO tbl_joblist
																											(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
																											PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
																											PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
																											BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																										VALUES ($ordid, '$kodepek9', '$kodepek9', '$induk9', '', '$PRJCODE',
																											'$PRJCODE_HO', '', '$namapek9', '$grpItm9', '$satuan9',
																											'$level9', '$volumer9', 
																											'$hargar9', '$htawar9', '$volume_k9', '$harga_k9', '$PRJCODE', '$PRJCODE_HO',
																											'$nilai_k9', '$bobot9', '$ISBBT9', '$isHd9', '$isL9', '$ordid')";
																						$this->db->query($s_ins9b);

																						$s_ins9c	= "INSERT INTO tbl_joblist_detail
																											(ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																											JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																											ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																											PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																											ISLAST, Patt_Number)
																										VALUES ($ordid, '$kodepek9', '$kodepek9', '$induk9', '',
																											'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek9',
																											'$grpItm9','$grpItm9', '$satuan9', '$volumer9', '$hargar9', '$hargar9', 
																											'$PRJCODE', '$PRJCODE_HO', '$htawar9', '$volume_k9', '$harga_k9', '$nilai_k9', '$level9', 
																											'$isL9', '$ordid')";
																						$this->db->query($s_ins9c);

																						// START : BREAK DOWN ITEM
																							$ordid_d9 	= 0;
																							$s_09b		= "SELECT kelompok, koderes, volumer, hargar, nilair, 
																												kode_gab, koefisien, nama, satuan
																											FROM $TABLENM2 WHERE kodepek = '$kodepek9' AND hr = 0";
																							$r_09b 		= $this->db->query($s_09b)->result();
																							foreach($r_09b as $rw_09b) :
																								$ordid_d9 	= $ordid_d9+1;
																								$kodepek_d9	= $kodepek9."-".$ordid_d9;
																								$grp_d9		= addslashes($rw_09b->kelompok);
																								$bahan_d9	= $rw_09b->koderes;			// Kode Item
																								$volumer_d9	= $rw_09b->volumer;
																								$hargar_d9	= $rw_09b->hargar;
																								$nilair_d9	= $rw_09b->nilair;
																								$koef_d9	= $rw_09b->koefisien;
																								$nama_d9	= addslashes($rw_09b->nama);
																								$satuan_d9	= addslashes($rw_09b->satuan);
																								$level_d9 	= $level9+1;
																								$isL_d9 	= 1;

																								$volume_k_d9= 0;
																								$harga_k_d9	= 0;
																								$nilai_k_d9	= 0;

																								$s_ins9d	= "INSERT INTO tbl_joblist_detail
																													(ORD_ID, JOBCODEDET, JOBCODEID,JOBPARENT, JOBCODEID_P,
																													JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																													ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																													PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																													ISLAST, Patt_Number)
																												VALUES ($ordid, '$kodepek_d9', '$kodepek_d9', '$kodepek9', '',
																													'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d9', '$nama_d9',
																													'$grp_d9','$grp_d9', '$satuan_d9', '$volumer_d9', '$hargar_d9', '$hargar_d9', 
																													'$PRJCODE', '$PRJCODE_HO', '$nilair_d9', '$volume_k_d9', '$harga_k_d9', '$nilai_k_d9', '$level_d9', 
																													'$isL_d9', '$ordid_d9')";
																								$this->db->query($s_ins9d);
																				
																								$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
																								$r_IMPD		= $this->db->count_all($s_IMPD);
																								$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
																								echo '<script>
																								parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

																							    ob_flush();
																							    flush();
																							endforeach;
							
																							/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek9' AND hr = 0";
																							$this->db->query($sdel_d);*/
																						// END : BREAK DOWN ITEM
																					// END : INSERT INTO JOBLIST LEVEL 9

																					if($isHd9 == 1)
																					{
																						$s_010	= "SELECT judul, level, kodepek, induk, namapek, satuan, volumer, 
																										volume_k, harga_k, nilai_k, pfc, bobot, bobotr, htawar
																									FROM $TABLENM1 WHERE induk = '$kodepek9' ORDER BY kodepek";
																						$r_010 	= $this->db->query($s_010)->result();
																						foreach($r_010 as $rw_010) :
																							$judul10	= $rw_010->judul;
																							$level10	= $rw_010->level;
																							$level10 	= 10;
																							$kodepek10	= $rw_010->kodepek;
																							$induk10	= $rw_010->induk;
																							$namapek10	= $rw_010->namapek;
																							$satuan10	= $rw_010->satuan;
																							$volumer10	= $rw_010->volumer;
																							$hargar10	= $rw_010->hargar;
																							$volume_k10	= $rw_010->volume_k;
																							$harga_k10	= $rw_010->harga_k;
																							$nilai_k10	= $rw_010->nilai_k;
																							$bobot10	= $rw_010->bobot;
																							$bobotr10	= $rw_010->bobotr;		// Bobot Realisasi
																							$htawar10	= $rw_010->htawar;
																							$grpItm10 	= "S";

																							$ISBBT10 	= 0;
																							if($bobot10 > 0)
																								$ISBBT10 = 1;

																							$isL10 		= 0;	// SEMUA DATA DI TABEL p_XXX ADALAH HEADER. DETIL DI TABEL r_
																							$isHd10 		= 0;
																							$s_chld10	= "$TABLENM1 WHERE induk = '$induk10'";
																							$r_chld10	= $this->db->count_all($s_chld10);
																							if($r_chld10 > 0)
																							{
																								$isHd10 	= 1;
																								//$isL10 = 0;	// SEMUA DATA DI TABEL P_XXX ADALAH HEADER. DETIL DI TABEL r_
																							}

																							// START : INSERT INTO JOBLIST LEVEL 10
																								$ordid 	= $ordid+1;

																								// TENTUKAN KODE KELOMPOK ITEM BESERTA HITUNG TOTAL NILAI PEKERJAAN TIAP PEKERJAAN
																									$TJOB10 		= $htawar10;
																									/*if($isL10 == 1)
																									{
																										$s_010a	= "SELECT SUM(nilair) AS TJOB FROM $TABLENM2 WHERE kodepek = '$kodepek10' AND hr = 0";
																										$r_010a 	= $this->db->query($s_010a)->result();
																										foreach($r_010a as $rw_010a) :
																											$TJOB10	= $rw_010a->judul;
																										endforeach;
																									}*/

																								$s_ins10a	= "INSERT INTO tbl_boqlist
																													(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO,
																													ITM_CODE,  JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE,
																													JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT,
																													ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																												VALUES ($ordid, '$kodepek10', '$kodepek10', '$induk10', '$PRJCODE', '$PRJCODE_HO',
																													'', '$namapek10','$grpItm10', '$satuan10', '$level10', '$volumer10', '$hargar10', 
																													'$htawar10', '$volume_k10', '$harga_k10', '$nilai_k10', '$PRJCODE', '$PRJCODE_HO', '$bobot10', 
																													'$ISBBT10', '$isHd10', '$isL10', '$ordid')";
																								$this->db->query($s_ins10a);

																								$s_ins10b	= "INSERT INTO tbl_joblist
																													(ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
																													PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
																													PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
																													BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
																												VALUES ($ordid, '$kodepek10', '$kodepek10', '$induk10', '', '$PRJCODE',
																													'$PRJCODE_HO', '', '$namapek10', '$grpItm10', '$satuan10',
																													'$level10', '$volumer10', 
																													'$hargar10', '$htawar10', '$volume_k10', '$harga_k10', '$PRJCODE', '$PRJCODE_HO',
																													'$nilai_k10', '$bobot10', '$ISBBT10', '$isHd10', '$isL10', '$ordid')";
																								$this->db->query($s_ins10b);

																								$s_ins10c	= "INSERT INTO tbl_joblist_detail
																													(ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
																													JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																													ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																													PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																													ISLAST, Patt_Number)
																												VALUES ($ordid, '$kodepek10', '$kodepek10', '$induk10', '',
																													'', '$PRJCODE', '$PRJCODE_HO', '', '$namapek10',
																													'$grpItm10','$grpItm10', '$satuan10', '$volumer10', '$hargar10', '$hargar10', 
																													'$PRJCODE', '$PRJCODE_HO', '$htawar10', '$volume_k10', '$harga_k10', '$nilai_k10', '$level10', 
																													'$isL10', '$ordid')";
																								$this->db->query($s_ins10c);


																								// START : BREAK DOWN ITEM
																									$ordid_d10 	= 0;
																									$s_010b		= "SELECT kelompok, koderes, volumer, hargar, 
																														nilair, kode_gab, koefisien, nama, satuan
																													FROM $TABLENM2 WHERE kodepek = '$kodepek10' AND hr = 0";
																									$r_010b 		= $this->db->query($s_010b)->result();
																									foreach($r_010b as $rw_010b) :
																										$ordid_d10 	= $ordid_d10+1;
																										$kodepek_d10= $kodepek10."-".$ordid_d10;
																										$grp_d10	= addslashes($rw_010b->kelompok);
																										$bahan_d10	= $rw_010b->koderes;			// Kode Item
																										$volumer_d10= $rw_010b->volumer;
																										$hargar_d10	= $rw_010b->hargar;
																										$nilair_d10	= $rw_010b->nilair;
																										$koef_d10	= $rw_010b->koefisien;
																										$nama_d10	= addslashes($rw_010b->nama);
																										$satuan_d10	= addslashes($rw_010b->satuan);
																										$level_d10 	= $level10+1;
																										$isL_d10 	= 1;

																										$volume_k_d10= 0;
																										$harga_k_d10	= 0;
																										$nilai_k_d10	= 0;

																										$s_ins10d	= "INSERT INTO tbl_joblist_detail
																															(ORD_ID, JOBCODEDET, JOBCODEID,JOBPARENT, JOBCODEID_P,
																															JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
																															ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
																															PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
																															ISLAST, Patt_Number)
																														VALUES ($ordid, '$kodepek_d10', '$kodepek_d10', '$kodepek10', '',
																															'', '$PRJCODE', '$PRJCODE_HO', '$bahan_d10', '$nama_d10',
																															'$grp_d10','$grp_d10', '$satuan_d10', '$volumer_d10', '$hargar_d10', '$hargar_d10', 
																															'$PRJCODE', '$PRJCODE_HO', '$nilair_d10', '$volume_k_d10', '$harga_k_d10', '$nilai_k_d10', '$level_d10', 
																															'$isL_d10', '$ordid_d10')";
																										$this->db->query($s_ins10d);
																				
																										$s_IMPD		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
																										$r_IMPD		= $this->db->count_all($s_IMPD);
																										$percent 	= intval($r_IMPD/$RPEKD * 100)."%";
																										echo '<script>
																										parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$RPEKD.') processed</span></div>";</script>';

																									    ob_flush();
																									    flush();
																									endforeach;
							
																									/*$sdel_d 	= "DELETE FROM $TABLENM2 WHERE kodepek = '$kodepek10' AND hr = 0";
																									$this->db->query($sdel_d);*/
																								// END : BREAK DOWN ITEM
																							// END : INSERT INTO JOBLIST LEVEL 10
																						endforeach;
																					}
																				endforeach;

																				$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
																				$r_IMP		= $this->db->count_all($s_IMP);
																				$percent 	= intval($r_IMP/$RPEK * 100)."%";
																				echo '<script>
																				parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$RPEK.') processed</span></div>";</script>';

																			    ob_flush();
																			    flush();
																			}
																		endforeach;
																				
																		$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
																		$r_IMP		= $this->db->count_all($s_IMP);
																		$percent 	= intval($r_IMP/$RPEK * 100)."%";
																		echo '<script>
																		parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$RPEK.') processed</span></div>";</script>';

																	    ob_flush();
																	    flush();
																	}
																endforeach;
																				
																$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
																$r_IMP		= $this->db->count_all($s_IMP);
																$percent 	= intval($r_IMP/$RPEK * 100)."%";
																echo '<script>
																parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$RPEK.') processed</span></div>";</script>';

															    ob_flush();
															    flush();
															}
														endforeach;
																				
														$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
														$r_IMP		= $this->db->count_all($s_IMP);
														$percent 	= intval($r_IMP/$RPEK * 100)."%";
														echo '<script>
														parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$RPEK.') processed</span></div>";</script>';

													    ob_flush();
													    flush();
													}
												endforeach;
																				
												$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
												$r_IMP		= $this->db->count_all($s_IMP);
												$percent 	= intval($r_IMP/$RPEK * 100)."%";
												echo '<script>
												parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$RPEK.') processed</span></div>";</script>';

											    ob_flush();
											    flush();
											}
										endforeach;
																				
										$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
										$r_IMP		= $this->db->count_all($s_IMP);
										$percent 	= intval($r_IMP/$RPEK * 100)."%";
										echo '<script>
										parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$RPEK.') processed</span></div>";</script>';

									    ob_flush();
									    flush();
									}
								endforeach;
																				
								$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
								$r_IMP		= $this->db->count_all($s_IMP);
								$percent 	= intval($r_IMP/$RPEK * 100)."%";
								echo '<script>
								parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$RPEK.') processed</span></div>";</script>';

							    ob_flush();
							    flush();
							}
						endforeach;
																				
						$s_IMP		= "tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
						$r_IMP		= $this->db->count_all($s_IMP);
						$percent 	= intval($r_IMP/$RPEK * 100)."%";
						echo '<script>
						parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$RPEK.') processed</span></div>";</script>';

					    ob_flush();
					    flush();
					}
				endforeach;

				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.document.getElementById("loading_2").style.display ="none";
				    parent.updStat();</script>';
			// END : RENDER PROCEDUR - RENDER BOQ
		}
		else if($REN_GROUP == 'ITM')
		{
			$TABLENM1 	= 'pm'.$PRJCODE;	// Tabel Amandemen yg tidak ada di RAP

			$s_TRUNSC	= "DELETE FROM tbl_item WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSC);

			$r_TIMP 	= 0;
			$r_TIMP1 	= 0;
			$s_TIMP		= "SELECT COUNT(DISTINCT ITM_CODE) AS TROW_ITM FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE != '';";
			$rTIMP		= $this->db->query($s_TIMP)->result();
			foreach($rTIMP as $rTIMP1) :
				$r_TIMP1= $rTIMP1->TROW_ITM;
			endforeach;

			$s_IMP2		= "TABLENM1";
			$r_TIMP2	= $this->db->count_all($s_IMP2);

			$r_TIMP 	= $r_TIMP1 + $r_TIMP2;

			$s_IMP		= "tbl_item WHERE PRJCODE = '$PRJCODE'";
			$r_IMP		= $this->db->count_all($s_IMP);
			$percent 	= intval($r_IMP/$r_TIMP * 100)."%";

			echo '<script>
			parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';
			echo '<script>
			parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$r_TIMP.') processed</span></div>";</script>';

		    ob_flush();
		    flush();

			// START : RENDER PROCEDUR - RENDER ITEM
				$ordid	= 0;

				$s_00	= "SELECT DISTINCT ITM_CODE, JOBDESC, ITM_GROUP, GROUP_CATEG, ITM_UNIT
							FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE != ''";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$ordid 			= $ordid+1;
					$ITM_CODE_00	= $rw_00->ITM_CODE;
					$JOBDESC_00		= addslashes($rw_00->JOBDESC);
					$ITM_GROUP_00	= $rw_00->ITM_GROUP;
					$GROUP_CATEG_00	= $rw_00->GROUP_CATEG;
					$ITM_UNIT_00	= addslashes($rw_00->ITM_UNIT);

					$s_01		= "SELECT SUM(ITM_VOLM) AS T_VOLM, SUM(ITM_BUDG) AS T_BUDG,
										SUM(BOQ_VOLM) AS T_BOQVOL, SUM(BOQ_JOBCOST) AS T_BOQCOST
									FROM tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE_00' AND PRJCODE = '$PRJCODE'";
					$r_01 		= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01) :
						$ITM_VOLM_01	= $rw_01->T_VOLM;
						$ITM_VOLM_01P 	= $ITM_VOLM_01;
						if($ITM_VOLM_01 == 0 || $ITM_VOLM_01 == '')
							$ITM_VOLM_01P = 1;
						$ITM_BUDG_01	= $rw_01->T_BUDG;
						$ITM_PRICE_01	= $ITM_BUDG_01 / $ITM_VOLM_01P;

						$BOQ_VOLM_01	= $rw_01->T_BOQVOL;
						$BOQ_VOLM_01P 	= $ITM_VOLM_01;
						if($BOQ_VOLM_01 == 0 || $BOQ_VOLM_01 == '')
							$BOQ_VOLM_01P = 1;
						$BOQ_JOBCOST_01	= $rw_01->T_BOQCOST;
						$BOQ_PRICE_01	= $BOQ_JOBCOST_01 / $BOQ_VOLM_01P;

						$s_01a			= "SELECT sum(B.ITM_VOLM) AS TOTBUDG FROM tbl_joblist_detail B
											WHERE B.ITM_CODE = '$ITM_CODE_00' AND B.PRJCODE = '$PRJCODE'";
						$r_01a 			= $this->db->query($s_01a)->result();
						foreach($r_01a as $rw_01a) :
							$T_BUD_01	= $rw_01a->TOTBUDG;
						endforeach;
						$ITM_VOLMBG_01 	= $T_BUD_01 ?: 0;

						$ISMTRL 		= 0;
						if($ITM_GROUP_00 == 'B' || $ITM_GROUP_00 == 'A')
							$ISMTRL 	= 1;

						// START : INSERT INTO ITEM
							$s_ins1	= "INSERT INTO tbl_item (PRJCODE, PRJCODE_HO, PRJPERIOD, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, 
												ITM_DESC, ITM_TYPE, ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLMBG, ITM_VOLMBGR, 
												ITM_VOLM,  ITM_PRICE, ITM_REMQTY, ITM_TOTALP, ITM_LASTP, ITM_AVGP,
												BOQ_ITM_VOLM, BOQ_ITM_PRICE, BOQ_ITM_TOTALP, ACC_ID, ACC_ID_UM, ACC_ID_SAL, STATUS, 
												ISMTRL, ISRM, ISWIP, ISFG, ISRIB, NEEDQRC, LASTNO, ITM_LR)
											VALUES ('$PRJCODE', '$PRJCODE_HO', '$PRJCODE', '$ITM_CODE_00', '$ITM_GROUP_00', '$ITM_GROUP_00', '$JOBDESC_00', 
												'', 'PRM', '$ITM_UNIT_00', '$ITM_UNIT_00', 'IDR', '$ITM_VOLMBG_01', '$ITM_VOLMBG_01', 
												'$ITM_VOLM_01', '$ITM_PRICE_01', '$ITM_PRICE_01', '$ITM_BUDG_01', '$ITM_PRICE_01', '$ITM_PRICE_01',
												'$BOQ_VOLM_01', '$BOQ_PRICE_01', '$BOQ_JOBCOST_01', '', '', '', '1', 
												'$ISMTRL', '0', '0', '0', '0', '0', '$ordid', '')";
							$this->db->query($s_ins1);

							$s_IMP		= "tbl_item WHERE PRJCODE = '$PRJCODE'";
							$r_IMP		= $this->db->count_all($s_IMP);
							$percent 	= intval($r_IMP/$r_TIMP * 100)."%";

							echo '<script>
							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';
							echo '<script>
							parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$r_TIMP.') processed</span></div>";</script>';

						    ob_flush();
						    flush();
						// END : INSERT INTO ITEM
					endforeach;
				endforeach;

				// FROM AMANDEMEN
					// WAITING
			// END : RENDER PROCEDUR - RENDER ITEM

			// START : CONCLUSION
				$sqlUNIT	= "SELECT DISTINCT A.ITM_UNIT 
								FROM tbl_item A
								WHERE NOT EXISTS (SELECT 1 FROM tbl_unittype B WHERE B.Unit_Type_Code = A.ITM_UNIT)
								AND A.PRJCODE = '$PRJCODE'";
				$resUNIT	= $this->db->query($sqlUNIT)->result();
				foreach($resUNIT as $rowUNIT) :
					$ITM_UNIT	= addslashes($rowUNIT->ITM_UNIT);
					if($ITM_UNIT != '')
					{
						$sqlInsUNIT	= "INSERT INTO tbl_unittype (Unit_Type_Code, UMCODE, Unit_Type_Name)
											VALUES ('$ITM_UNIT', '$ITM_UNIT', '$ITM_UNIT')";
						$this->db->query($sqlInsUNIT);
					}
				endforeach;
			// END : CONCLUSION

			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		}
		else if($REN_GROUP == 'JRN')
		{
			$TABLENM1 	= 'buku'.$DESCRIPT;
			$accYr 		= $DESCRIPT;

			$s_TRUNSH	= "DELETE FROM tbl_journalheader WHERE proj_Code = '$PRJCODE'";
			$this->db->query($s_TRUNSH);

			$s_TRUNSD	= "DELETE FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE'";
			$this->db->query($s_TRUNSD);

			$s_IMP		= "tbl_journaldetail WHERE proj_Code = '$PRJCODE'";
			$r_IMP		= $this->db->count_all($s_IMP);

			$s_TIMP		= "$TABLENM1 WHERE proyek = '$PRJCODE' AND hr = 0";
			$r_TIMP		= $this->db->count_all($s_TIMP);
			$percent 	= intval($r_IMP/$r_TIMP * 100)."%";

			echo '<script>
			parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';
			echo '<script>
			parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';

		    ob_flush();
		    flush();

			// START : RENDER PROCEDUR - RENDER JOURNAL
				$ordid	= 0;

				$s_01	= "SELECT nomor, kodeacc, tanggal, debet, kredit, uraian, kelompok, keterangan, proyek, nomorpo, no_tgh, buaso, kd_bahan, user_i
							FROM $TABLENM1 WHERE proyek = '$PRJCODE' AND hr = 0 GROUP BY nomor ORDER BY id";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$ordid 			= $ordid+1;
					$nomor_01		= $rw_01->nomor;
					$kodeacc_01		= $rw_01->kodeacc;
					$tanggal_01		= $rw_01->tanggal;
					$debet_01		= $rw_01->debet;
					$kredit_01		= $rw_01->kredit;
					$uraian_01		= addslashes($rw_01->uraian);
					$kelompok_01	= $rw_01->kelompok;
					$keterangan_01	= addslashes($rw_01->keterangan);
					$proyek_01		= $rw_01->proyek;
					$nomorpo_01		= $rw_01->nomorpo;
					$no_tgh_01		= $rw_01->no_tgh;
					$buaso_01		= $rw_00->buaso;
					$kd_bahan_01	= $rw_00->kd_bahan;
					$user_i_01		= $rw_00->user_i;
					$LastUpd_01		= date("Y-m-d H:i:s");

					// START : INSERT HEADER
						$s_ins1 	= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Source,
											Emp_ID, Created, LastUpdate, KursAmount_tobase, 
											Wh_id, Reference_Number, Reference_Type, proj_Code, GEJ_STAT)
										VALUES ('$nomor_01', '$kelompok_01', '$uraian_01', '$tanggal_01', '$PRJCODE_HO', '$no_tgh_01', 
											'$user_i_01', '$LastUpd_01', '$LastUpd_01', 1, 
											'$PRJCODE', 'PO: $nomorpo_01', '$kelompok_01', '$PrjCode', '3')";
						$this->db->query($s_ins1);
					// END : INSERT HEADER

					// START : INSERT DETAIL
						$s_02	= "SELECT kodeacc, namaacc, tanggal, debet, kredit, uraian, kelompok, keterangan, proyek, nomorpo,
										no_tgh, buaso, kd_bahan, user_i
									FROM $TABLENM1 WHERE proyek = '$PRJCODE' AND hr = 0 AND nomor = '$nomor_01' ORDER BY id";
						$r_02 	= $this->db->query($s_02)->result();
						foreach($r_02 as $rw_02) :
							$ordid 			= $ordid+1;
							$kodeacc_02		= $rw_02->kodeacc;
							$namaacc_02		= addslashes($rw_02->namaacc);
							$tanggal_02		= $rw_02->tanggal;
							$debet_02		= $rw_02->debet;
							$kredit_02		= $rw_02->kredit;
							$uraian_02		= addslashes($rw_02->uraian);
							$kelompok_02	= $rw_02->kelompok;
							$keterangan_02	= addslashes($rw_02->keterangan);
							$proyek_02		= $rw_02->proyek;
							$nomorpo_02		= $rw_02->nomorpo;
							$no_tgh_02		= $rw_02->no_tgh;
							$buaso_02		= $rw_00->buaso;
							$kd_bahan_02	= $rw_00->kd_bahan;
							$user_i_02		= $rw_00->user_i;
							$LastUpd_02		= date("Y-m-d H:i:s");

							$s_03 			= "SELECT syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$kodeacc_02' LIMIT 1";
							$r_03			= $this->db->query($s_03)->result();
							foreach($r_03 as $rw_03):
								$syncPRJ_03	= $rw_03->syncPRJ;
							endforeach;
							$dtcol_03 	= explode("~",$syncPRJ_03);
							$jmD_03 		= count($dtcol_03);

							if($debet_02 > 0)
							{
								$s_ins2 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT,
													JOBCODEID, PattNum, oth_reason, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
												VALUES ('$nomor_01', '$kodeacc_02', '$PRJCODE', 'IDR', 
													$debet_02, $debet_02, $debet_02, 'Default', 1, 0, 
													'D', '$kd_bahan_02','$buaso_02','$buaso_02', 1, $debet_02,'IMPORT',
													'', $ordid, '$keterangan_02', '', '', '$namaacc_02', '$PRJCODE_HO')";
								$this->db->query($s_ins2);

								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD_03; $i++)
								{
									$SYNC_PRJ	= $dtcol_03[$i];
									$s_upd1		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$debet_02,
														Base_Debet2 = Base_Debet2+$debet_02,  BaseD_$accYr = BaseD_$accYr+$debet_02
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$kodeacc_02'";
									$this->db->query($s_upd1);
								}
							}
							else
							{
								$s_ins2 	= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, Acc_Id, proj_Code, Currency_id, 
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK,
													ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, PattNum,
													oth_reason, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
												VALUES ('$nomor_01', '$tanggal_01', '$kodeacc_02', '$PRJCODE', 'IDR',
													$kredit_02, $kredit_02, $kredit_02, 'Default', 1, 0, 'K',
													'$kd_bahan_02','$buaso_02','$buaso_02',1, $kredit_02,'IMPORT', $ordid,
													'$keterangan_02', '', '', '$namaacc_02', '$PRJCODE_HO')";
								$this->db->query($s_ins2);

								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD_03; $i++)
								{
									$SYNC_PRJ	= $dtcol_03[$i];
									$s_upd1		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$kredit_02, 
														Base_Kredit2 = Base_Kredit2+$kredit_02, BaseK_$accYr = BaseK_$accYr+$kredit_02
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$kodeacc_02'";
									$this->db->query($s_upd1);
								}
							}
								
							$s_IMP		= "tbl_journaldetail WHERE proj_Code = '$PRJCODE'";
							$r_IMP		= $this->db->count_all($s_IMP);
							$percent 	= intval($r_IMP/$r_TIMP * 100)."%";
							echo '<script>
							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';

						    ob_flush();
						    flush();
						endforeach;
					// END : INSERT DETAIL
				endforeach;

				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
					echo '<script>
					    parent.document.getElementById("loading_1").style.display ="none";
					    parent.updStat();</script>';
			// END : RENDER PROCEDUR - RENDER JOURNAL
		}
		else if($REN_GROUP == 'PR')
		{
			$s_TRUNSH	= "DELETE FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSH);

			$s_TRUNSD	= "DELETE FROM tbl_pr_detail WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSD);

			// START : UPDATE ZERO FRO PR AND ITEM DETAIL
				$s_upd0		= "UPDATE tbl_joblist_detail SET REQ_VOLM = 0, REQ_AMOUNT = 0 WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_upd0);

				$s_upd01	= "UPDATE tbl_item SET PR_VOLM = 0, PR_AMOUNT = 0 WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_upd01);
			// END : UPDATE ZERO FRO PR AND ITEM DETAIL

			// START : COLLECT DATA
				$add_00	= 0;

				$s_IMP		= "rpb WHERE proyek = '$PRJCODE' AND hr = 0";
				$r_TIMP		= $this->db->count_all($s_IMP);
				for($i=0; $i<=$r_TIMP; $i++)
				{
					$percent 	= intval($i / $r_TIMP * 100)."%";
					$collected 	= intval($i);
					echo '<script>
						  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

				    ob_flush(); 
				    flush();
				}
			// END : COLLECT DATA

			// START : RENDER PROCEDUR - RENDER RPB
				$ordid	= 0;

				$s_01	= "SELECT nomor, tanggal, user_i, proyek, status_trma, namapek, CONCAT(namapek, ' ', uraian) AS rpb_desc,
								tgl_pakai, tgl_i, no_minta
							FROM rpb WHERE proyek = '$PRJCODE' AND hr = 0 GROUP BY nomor ORDER BY id";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$ordid 			= $ordid+1;
					$nomor_01		= $rw_01->nomor;
					$tanggal_01		= $rw_01->tanggal;
					$user_i_01		= $rw_00->user_i;
					$proyek_01		= $rw_01->proyek;
					$status_trma_01	= $rw_01->status_trma;
					$namapek_01		= addslashes($rw_01->namapek);
					$uraian_01		= addslashes($rw_01->uraian);
					$rpb_desc_01	= addslashes($rw_01->rpb_desc);
					$tgl_pakai_01 	= $rw_01->tgl_pakai;
					$tgl_i_01 		= $rw_01->tgl_i;
					$no_minta_01 	= $rw_01->no_minta;

					// START : INSERT HEADER
						$s_ins1 	= "INSERT INTO tbl_pr_header (PR_NUM, PR_CODE, PR_DATE, PR_RECEIPTD, PRJCODE, DEPCODE, PR_REQUESTER, PR_NOTE,
											PR_PLAN_IR, PR_STAT, PR_VALUE, PR_CREATER, PR_CREATED, PR_REFNO, PR_ISCLOSE, STATDESC, STATCOL)
										VALUES ('$nomor_01', '$nomor_01', '$tanggal_01', '$tgl_pakai_01', '$PRJCODE', 'D.IMP', '$user_i_01', '$rpb_desc_01',
											'$tgl_pakai_01', 3, 0, '$user_i_01', '$tgl_i_01', '$no_minta', 0, 'Approved', 'success')";
						$this->db->query($s_ins1);
					// END : INSERT HEADER

					// START : INSERT DETAIL
						$ord_02	= 0;
						$s_02	= "SELECT nomor, tanggal, user_i, proyek, status_trma, kode, kodepek, namapek, CONCAT(namapek, ' ', uraian) AS rpb_desc,
										no_minta, vol_minta, vol_po
									FROM rpb WHERE proyek = '$PRJCODE' AND hr = 0 AND nomor = '$nomor_01' ORDER BY id";
						$r_02 	= $this->db->query($s_02)->result();
						foreach($r_02 as $rw_02) :
							$ord_02 		= $ord_02+1;
							$nomor_02		= $rw_02->nomor;
							$tanggal_02		= $rw_02->tanggal;
							$user_i_02		= $rw_02->user_i;
							$no_minta_02 	= $rw_02->no_minta;
							$proyek_02		= $rw_02->proyek;
							$status_trma_02	= $rw_02->status_trma;
							$kode_02		= $rw_02->kode;
							$kodepek_02		= addslashes($rw_02->kodepek);
							$namapek_02		= addslashes($rw_02->namapek);
							$uraian_02		= addslashes($rw_02->uraian);
							$rpb_desc_02	= addslashes($rw_02->rpb_desc);
							$vol_minta_02 	= $rw_02->vol_minta;
							if($vol_minta_02 == '')
								$vol_minta_02= 0;

							$vol_po_02 		= $rw_02->vol_po;

							/*$kode_02 		= 'B004005001008003';
							$kodepek_02 	= 'E.1.01.04.02';*/

							// START : INSERT DETAIL
								$s_03	= "SELECT A.JOBCODEID, A.JOBDESC FROM tbl_joblist_detail A
											WHERE A.JOBCODEID IN (SELECT B.JOBPARENT FROM tbl_joblist_detail_temp B WHERE B.JOBCODEID = '$kodepek_02' AND PRJCODE = '$PRJCODE')
												 AND PRJCODE = '$PRJCODE'";
								$r_03 	= $this->db->query($s_03)->result();
								foreach($r_03 as $rw_03) :
									$jobpar_03	= $rw_03->JOBCODEID;
									$jobdesc_03	= addslashes($rw_03->JOBDESC);
								endforeach;

								$s_04 		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$kode_02'";
								$r_04 		= $this->db->count_all($s_04);
								if($r_04 == 0)
								{
									$TBL_04		= "pm".$PRJCODE;
									$buaso_04	= '';
									$satuan_04 	= '';
									$s_04		= "SELECT buaso, satuan FROM $TBL_04  WHERE koderes = '$kode_02' AND kodepek = '$kodepek_02' AND hr = 0";
									$r_04 		= $this->db->query($s_04)->result();
									foreach($r_04 as $rw_04) :
										$buaso_04	= $rw_04->buaso;
										$satuan_04	= addslashes($rw_04->satuan);
									endforeach;

									if($buaso_04 == 'B')
										$TBL_05	= "b_".$PRJCODE;
									elseif($buaso_04 == 'U')
										$TBL_05	= "u_".$PRJCODE;
									elseif($buaso_04 == 'A')
										$TBL_05	= "a_".$PRJCODE;
									elseif($buaso_04 == 'S')
										$TBL_05	= "s_".$PRJCODE;
									elseif($buaso_04 == 'O')
										$TBL_05	= "o_".$PRJCODE;

									$hargar 	= 0;
									$s_05		= "SELECT hargar, harpospk FROM $TBL_05 WHERE koderes = '$kode_02' AND hr = 0";
									$r_05 		= $this->db->query($s_05)->result();
									foreach($r_05 as $rw_05) :
										$hargar		= $rw_05->hargar;
										$harpospk 	= $rw_05->harpospk;
										if($hargar == 0)
											$hargar = $harpospk;
									endforeach;
								}
								else
								{
									$hargar		= 0;
									$s_04		= "SELECT ITM_PRICE, ITM_UNIT FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$kode_02'";
									$r_04 		= $this->db->query($s_04)->result();
									foreach($r_04 as $rw_04) :
										$hargar	= $rw_04->ITM_PRICE;
										$satuan	= $rw_04->ITM_UNIT;
									endforeach;
									$satuan_04 	= $satuan;
								}

								$amn_minta_02 	= $vol_minta_02 * $hargar;
								$amn_po_02 		= $vol_po_02 * $hargar;
								$s_ins2 	= "INSERT INTO tbl_pr_detail (PR_ID, PR_NUM, PR_CODE, PR_DATE, PRJCODE, DEPCODE, PR_REFNO,
													JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC, ITM_CODE, SNCODE, ITM_UNIT, 
													PR_VOLM, PR_VOLM2, PR_PRICE, PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT, PR_TOTAL, PR_DESC)
												VALUES ('$ord_02', '$nomor_02', '$nomor_02', '$tanggal_02', '$PRJCODE', 'D.IMP', '$no_minta_02',
													'$kodepek_02', '$kodepek_02', '$jobpar_03', '$jobdesc_03', '$kode_02', '', '$satuan_04',
													'$vol_minta_02', '$vol_minta_02', $hargar, 0, 0, 0, 0, $amn_minta_02, '$rpb_desc_02')";
								$this->db->query($s_ins2);
							// END : INSERT DETAIL

							// START : UPDATE JOBLIST DETAIL
								$s_upd1		= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM + $vol_minta_02, REQ_AMOUNT = REQ_AMOUNT + $amn_minta_02
												WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$kodepek_02' AND ITM_CODE = '$kode_02'";
								$this->db->query($s_upd1);
							// END : UPDATE JOBLIST DETAIL

							// START : UPDATE ITEM
								$s_upd2		= "UPDATE tbl_item SET PR_VOLM = PR_VOLM + $vol_minta_02, PR_AMOUNT = PR_AMOUNT + $amn_minta_02
												WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$kode_02'";
								$this->db->query($s_upd2);
							// END : UPDATE ITEM

							$s_IMP		= "tbl_pr_detail WHERE PRJCODE = '$PRJCODE'";
							$r_IMP		= $this->db->count_all($s_IMP);
							$percent 	= intval($r_IMP/$r_TIMP * 100)."%";

							echo '<script>
							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';
							echo '<script>
							parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$r_TIMP.') processed</span></div>";</script>';

						    ob_flush();
						    flush();
						endforeach;
					// END : INSERT DETAIL
				endforeach;
			// END : RENDER PROCEDUR - RENDER RPB

			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		}
		else if($REN_GROUP == 'PO')
		{
			$s_TRUNSH	= "DELETE FROM tbl_po_header WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSH);

			$s_TRUNSD	= "DELETE FROM tbl_po_detail WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSD);

			// START : UPDATE ZERO FRO PR AND ITEM DETAIL
				$s_upd0		= "UPDATE tbl_joblist_detail SET PO_VOLM = 0, PO_AMOUNT = 0 WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_upd0);

				$s_upd01	= "UPDATE tbl_item SET PO_VOLM = 0, PO_AMOUNT = 0 WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_upd01);
			// END : UPDATE ZERO FRO PR AND ITEM DETAIL

			// START : COLLECT DATA
				$add_00	= 0;

				$s_TIMP		= "purorder WHERE proyek = '$PRJCODE' AND hr = 0";
				$r_TIMP		= $this->db->count_all($s_TIMP);
				for($i=0; $i<=$r_TIMP; $i++)
				{
					$percent 	= intval($i / $r_TIMP * 100)."%";
					$collected 	= intval($i);
					echo '<script>
						  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

				    ob_flush(); 
				    flush();
				}
			// END : COLLECT DATA

			// START : RENDER PROCEDUR - RENDER PURORDER
				$ordid	= 0;

				$s_01	= "SELECT nomor, tanggal, kode, nama, satuan, volume, harga, tgl_i, pemasok, tempo, spb, uraianm, kodepek, no_rpb
							FROM purorder WHERE proyek = '$PRJCODE' AND hr = 0 GROUP BY nomor ORDER BY id";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$ordid 			= $ordid+1;
					$nomor_01		= $rw_01->nomor;
					$tanggal_01		= $rw_01->tanggal;
					$kode_01		= $rw_01->kode;
					$nama_01		= addslashes($rw_01->nama);
					$satuan_01		= addslashes($rw_01->satuan);
					$volume_01		= $rw_01->volume;
					$harga_01		= $rw_01->harga;
					$totpo_01 		= $volume_01 * $harga_01;
					$user_i_01		= $rw_00->user_i;
					$tgl_i_01		= $rw_00->tgl_i;
					$pemasok_01		= $rw_01->pemasok;
					$tempo_01		= $rw_01->tempo;
					$uraianm_01		= addslashes($rw_01->uraianm);

					// START : INSERT HEADER
						$s_ins1 	= "INSERT INTO tbl_po_header (PO_NUM, PO_CODE, PO_TYPE, PO_CAT, PO_DATE, PO_DUED, PRJCODE, DEPCODE, SPLCODE,
											PR_NUM, PR_CODE, PO_CURR, PO_CURRATE, PO_TOTCOST, PO_TENOR, PO_NOTES, PO_CREATED, PO_STAT,
											CREATERNM, STATDESC, STATCOL)
										VALUES ('$nomor_01', '$nomor_01', 1, 0, '$tanggal_01', '$tanggal_01', '$PRJCODE', 'D.IMP', '$pemasok_01',
											'$no_rpb_01', '$no_rpb_01', 'IDR', 1, '$totpo_01', '$tempo_01', '$uraianm_01', '$tgl_i_01', 3,
											'$user_i_01', 'Approved', 'success')";
						$this->db->query($s_ins1);
					// END : INSERT HEADER

					// START : INSERT DETAIL
						$ord_02	= 0;
						$s_02	= "SELECT tanggal, kode, nama, satuan, volume, harga, tgl_i, pemasok, tempo, spb, uraianm, kodepek, no_rpb, vol_rpb
									FROM purorder WHERE proyek = '$PRJCODE' AND hr = 0 AND nomor = '$nomor_01'";
						$r_02 	= $this->db->query($s_02)->result();
						foreach($r_02 as $rw_02) :
							$ord_02 		= $ord_02+1;
							$tanggal_02		= $rw_02->tanggal;
							$kode_02		= $rw_02->kode;
							$nama_02		= addslashes($rw_02->nama);
							$satuan_02		= addslashes($rw_02->satuan);
							$volume_02		= $rw_02->volume;
							$harga_02		= $rw_02->harga;
							$totpo_02 		= $volume_02 * $harga_02;
							$user_i_02		= $rw_00->user_i;
							$tgl_i_02		= $rw_00->tgl_i;
							$pemasok_02		= $rw_02->pemasok;
							$tempo_02		= $rw_02->tempo;
							$spb_02			= $rw_02->spb;
							$uraianm_02		= addslashes($rw_02->uraianm);
							$kodepek_02		= $rw_02->kodepek;
							$no_rpb_02		= $rw_02->no_rpb;
							$vol_rpb_02		= $rw_02->vol_rpb;
							$totrpb_02 		= $vol_rpb_02 * $harga_02;

							$PR_ID 			= 0;
							$s_03			= "SELECT PR_ID FROM tbl_pr_detail WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$no_rpb_02'
												AND JOBCODEID = '$kodepek_02' AND ITM_CODE = '$kode_02'";
							$r_03 			= $this->db->query($s_03)->result();
							foreach($r_03 as $rw_03) :
								$PR_ID		= $rw_03->PR_ID;
							endforeach;

							$s_ins2 		= "INSERT INTO tbl_po_detail (PO_ID, PO_NUM, PO_CODE, PO_DATE, PRJCODE, 
													PR_NUM, JOBCODEDET, JOBCODEID, PRD_ID, ITM_CODE, ITM_UNIT, PR_VOLM, PR_AMOUNT,
													PO_VOLM, PO_PRICE, PO_COST, PO_DESC, TAXCODE1, TAXPRICE1)
												VALUES ('$ord_02', '$nomor_01', '$nomor_01', '$tanggal_01', '$PRJCODE',
													'$no_rpb_02', '$kodepek_02', '$kodepek_02', $PR_ID, '$kode_02', '$satuan_02', '$vol_rpb_02', '$totrpb_02',
													'$volume_02', '$harga_02', '$totpo_02', '$uraianm_02', '', 0)";
							$this->db->query($s_ins2);

							// START : UPDATE JOBLIST DETAIL
								$s_upd1		= "UPDATE tbl_joblist_detail SET PO_VOLM = PO_VOLM + $volume_02, PO_AMOUNT = PO_AMOUNT + $totpo_02
												WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$kodepek_02' AND ITM_CODE = '$kode_02'";
								$this->db->query($s_upd1);
							// END : UPDATE JOBLIST DETAIL

							// START : UPDATE ITEM
								$s_upd2		= "UPDATE tbl_item SET PO_VOLM = PO_VOLM + $volume_02, PO_AMOUNT = PO_AMOUNT + $totpo_02
												WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$kode_02'";
								$this->db->query($s_upd2);
							// END : UPDATE ITEM

							$s_IMP		= "tbl_po_detail WHERE PRJCODE = '$PRJCODE'";
							$r_IMP		= $this->db->count_all($s_IMP);
							$percent 	= intval($r_IMP/$r_TIMP * 100)."%";

							echo '<script>
							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';
							echo '<script>
							parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$r_TIMP.') processed</span></div>";</script>';

						    ob_flush();
						    flush();
						endforeach;
					// END : INSERT DETAIL
				endforeach;
			// END : RENDER PROCEDUR - RENDER PURORDER

			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		}
		else if($REN_GROUP == 'AMD')
		{
			$TABLENM1 	= 'addendum';
			$TABLENM2 	= 'r_'.$DESCRIPT;
			$accYr 		= $DESCRIPT;

			$s_TRUNSH	= "DELETE FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSH);

			$s_TRUNSD	= "DELETE FROM tbl_amd_detail WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSD);

			// START : COLLECT DATA
				$add_00	= 0;

				$s_TIMP		= "$TABLENM1 WHERE kode = '$PRJCODE' AND hr = 0";
				$r_TIMP		= $this->db->count_all($s_TIMP);
				for($i=0; $i<=$r_TIMP; $i++)
				{
					$percent 	= intval($i / $r_TIMP * 100)."%";
					$collected 	= intval($i);
					echo '<script>
						  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

				    ob_flush(); 
				    flush();
				}
			// END : COLLECT DATA

			// START : RENDER PROCEDUR - RENDER AMANDEMEN
				$ordid	= 0;
				$ADDTGL = date('ymdHis');
				$s_01	= "SELECT nomor_urut, add_ke, tgl_konadd, vol_konadd, hrg_konadd, jlm_konadd, kode_pek, nokon_add, user_i, tgl_i
							FROM $TABLENM1 WHERE kode = '$PRJCODE' AND hr = 0 GROUP BY kode_pek ORDER BY nomor_urut";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$ordid 			= $ordid+1;
					$nomor_urut_01	= $rw_01->nomor_urut;
					$nomor_01 		= $ADDTGL.".".$ordid."-".$nomor_urut_01;
					$add_ke_01		= $rw_01->add_ke;
					$tgl_konadd_01	= $rw_01->tgl_konadd;
					$vol_konadd_01	= $rw_01->vol_konadd;
					$hrg_konadd_01	= $rw_01->hrg_konadd;
					$jlm_konadd_01	= $rw_01->jlm_konadd;
					$kode_pek_01	= $rw_01->kode_pek;
					$nokon_add_01	= $rw_01->nokon_add;
					$user_i_01		= $rw_00->user_i;
					$tgl_i_01		= $rw_00->tgl_i;

					$s_02			= "SELECT JOBCODEID, JOBDESC, JOBPARENT, ITM_GROUP FROM tbl_joblist_detail
										WHERE JOBCODEID = '$kode_pek_01' AND PRJCODE = '$PRJCODE'";
					$r_02 			= $this->db->query($s_02)->result();
					foreach($r_02 as $rw_02) :
						$jobkode_02	= $rw_02->JOBCODEID;
						$jobpar_02	= $rw_02->JOBPARENT;
						$jobdesc_02	= addslashes($rw_02->JOBDESC);
						$jobgrp_02	= $rw_02->ITM_GROUP;
					endforeach;

					// START : INSERT HEADER
						$s_ins1 	= "INSERT INTO tbl_amd_header (AMD_NUM, AMD_CODE, AMD_DATE, PRJCODE, AMD_TYPE, AMD_CATEG, AMD_REFNO, AMD_NOTES,
											AMD_JOBPAR, AMD_JOBID, AMD_JOBDESC, AMD_DESC, AMD_STAT, STATDESC, STATCOL, CREATERNM)
										VALUES ('$nomor_01', '$nomor_01', '$tgl_konadd_01', '$PRJCODE', 9, 'OB', '-', '$jobdesc_02',
											'$jobpar_02', '$jobkode_02', '$jobdesc_02', 'Import from SIAP', 3, 'Approved', 'success', '$user_i_01')";
						$this->db->query($s_ins1);
					// END : INSERT HEADER

					// DI NKESYS DIKELOMPOKKAN BERDASARKAN KODE PEKERJAAN INDUK, NAMUN
					// SEMENTARA DI SIAP, BERDASARKAN NOMOR URUT
					// SEHINGGA AKAN TETAP BERDASARKAN KODE PEKERJAAN

					// START : INSERT DETAIL
						$ord_03	= 0;
						$s_03	= "SELECT nomor_urut, add_ke, tgl_konadd, vol_konadd, hrg_konadd, jlm_konadd, kode_pek, nokon_add, user_i, tgl_i
										FROM addendum WHERE kode = '$PRJCODE' AND hr = 0 AND kode_pek = '$kode_pek_01' ORDER BY nomor_urut";
						$r_03 	= $this->db->query($s_03)->result();
						foreach($r_03 as $rw_03) :
							$ord_03 		= $ord_03+1;
							$nomor_urut_03	= $rw_03->nomor_urut;
							$nomor_03 		= $ADDTGL."-".$nomor_urut_03;
							$add_ke_03		= $rw_03->add_ke;
							$tgl_konadd_03	= $rw_03->tgl_konadd;
							$vol_konadd_03	= $rw_03->vol_konadd;
							$hrg_konadd_03	= $rw_03->hrg_konadd;
							$jlm_konadd_03	= $rw_03->jlm_konadd;
							$kode_pek_03	= $rw_03->kode_pek;
							$nokon_add_03	= $rw_03->nokon_add;
							$user_i_03		= $rw_00->user_i;
							$tgl_i_03		= $rw_00->tgl_i;

							$s_04			= "SELECT koderes, SUM(volumer) AS totADD, hargar, nama, satuan, kelompok FROM $TABLENM2
												WHERE kodepek = '$kode_pek_03' AND hr = 0 GROUP BY koderes";
							$r_04 			= $this->db->query($s_04)->result();
							foreach($r_04 as $rw_04) :
								$koderes_04	= $rw_04->koderes;
								$totVol_04	= $rw_04->totADD;
								$hargar_04	= $rw_04->hargar;
								$totAmn_04	= $rw_04->hargar;
								$nama_04	= addslashes($rw_04->nama);
								$satuan_04	= addslashes($rw_04->satuan);
								$jobgrp_04	= $rw_04->kelompok;
							endforeach;

							$s_ins2 	= "INSERT INTO tbl_amd_detail (AMD_NUM, JOBCODEID, JOBPARENT, PRJCODE, ITM_GROUP, ITM_CODE, ITM_UNIT,
												JOBDESC, AMD_CLASS, AMD_VOLM, ITM_PRICE, AMD_PRICE, AMD_TOTAL, AMD_DESC)
											VALUES ('$nomor_01', '$jobkode_02', '$jobpar_02', '$PRJCODE', '$jobgrp_04', '$koderes_04', '$satuan_04',
												'$jobdesc_02', 1, '$totVol_04', '$hargar_04', '$hargar_04', '$totAmn_04', '$nama_04')";
							$this->db->query($s_ins2);
		
							// START : UPDATE JOBLIST DETAIL
								$s_upd1		= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $vol_minta_02, ADD_PRICE = $hargar_04,
													ADD_JOBCOST = ADD_JOBCOST + $totAmn_04
												WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$kode_pek_03' AND ITM_CODE = '$koderes_04'";
								$this->db->query($s_upd1);
							// END : UPDATE JOBLIST DETAIL

							$s_IMP		= "tbl_amd_detail WHERE PRJCODE = '$PRJCODE'";
							$r_IMP		= $this->db->count_all($s_IMP);
							$percent 	= intval($r_IMP/$r_TIMP * 100)."%";

							echo '<script>
							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';
							echo '<script>
							parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$r_TIMP.') processed</span></div>";</script>';

						    ob_flush();
						    flush();
						endforeach;
					// END : INSERT DETAIL
				endforeach;
			// END : RENDER PROCEDUR - RENDER AMANDEMEN

			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		}
		else if($REN_GROUP == 'SI')
		{
			$TABLENM1 	= 'addmproy';

			$s_TRUNSH	= "DELETE FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSH);

			// START : COLLECT DATA
				$add_00	= 0;

				$s_TIMP		= "$TABLENM1 WHERE kode = '$PRJCODE'";
				$r_TIMP		= $this->db->count_all($s_TIMP);
				for($i=0; $i<=$r_TIMP; $i++)
				{
					$percent 	= intval($i / $r_TIMP * 100)."%";
					$collected 	= intval($i);
					echo '<script>
						  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

				    ob_flush(); 
				    flush();
				}
			// END : COLLECT DATA

			// START : RENDER PROCEDUR - RENDER SI
				$ordid	= 0;
				$ADDTGL = date('ymdHis');
				$s_01	= "SELECT no, no_kon_add, tgl_kon_add, tg_kad_mul, tgkaddsel, tg_sel_pml, nilaiadd, tgl_i, user_i
							FROM $TABLENM1 WHERE kode = '$PRJCODE' ORDER BY tgl_kon_add";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$ordid 			= $ordid+1;
					$nomor_01 		= $ADDTGL.".".$ordid;
					$no_01			= $rw_01->no;
					$no_kon_add_01	= $rw_01->no_kon_add;
					$tgl_kon_add_01	= $rw_01->tgl_kon_add;
					$tg_kad_mul_01	= $rw_01->tg_kad_mul;
					$tgkaddsel_01	= $rw_01->tgkaddsel;
					$nilaiadd_01	= $rw_01->nilaiadd;
					$jlm_konadd_01	= $rw_01->jlm_konadd;
					$tgl_i_01		= $rw_00->tgl_i;
					$user_i_01		= $rw_00->user_i;

					// START : INSERT HEADER
						$s_ins1 	= "INSERT INTO tbl_siheader (SI_CODE, SI_MANNO, SI_INCCON, SI_STEP, PRJCODE, SI_DATE, SI_ENDDATE, SI_CREATED,
											SI_DESC, SI_VALUE, SI_APPVAL, SI_STAT)
										VALUES ('$nomor_01', '$no_kon_add_01', 0, '$no_01', '$PRJCODE', '$tgl_kon_add_01', '$tgkaddsel_01', '$tgl_i_01',
											'-', '$nilaiadd_01', '$nilaiadd_01', 3)";
						$this->db->query($s_ins1);
					// END : INSERT HEADER

					$s_IMP		= "tbl_siheader WHERE PRJCODE = '$PRJCODE'";
					$r_IMP		= $this->db->count_all($s_IMP);
					$percent 	= intval($r_IMP/$r_TIMP * 100)."%";

					echo '<script>
					parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';
					echo '<script>
					parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$r_TIMP.') processed</span></div>";</script>';

				    ob_flush();
				    flush();
				endforeach;
			// END : RENDER PROCEDUR - RENDER SI

			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		}
		else if($REN_GROUP == 'FPRJ')
		{
			$TABLENM1 	= 'invoice';

			$s_TRUNSH	= "DELETE FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($s_TRUNSH);

			// START : COLLECT DATA
				$add_00	= 0;

				$s_TIMP		= "$TABLENM1 WHERE proyek = '$PRJCODE'";
				$r_TIMP		= $this->db->count_all($s_TIMP);
				for($i=0; $i<=$r_TIMP; $i++)
				{
					$percent 	= intval($i / $r_TIMP * 100)."%";
					$collected 	= intval($i);
					echo '<script>
						  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

				    ob_flush(); 
				    flush();
				}
			// END : COLLECT DATA

			// START : RENDER PROCEDUR - RENDER PROJ INV
				$ordid	= 0;
				$ADDTGL = date('ymdHis');
				$s_01	= "SELECT NOMOR, t_ke, t_tg, t_prg, t_nil, t_nilt, t_ppn, tg_bayar, bayar, tgl_i, user_i, uraian, nomor_fp
							FROM $TABLENM1 WHERE proyek = '$PRJCODE' AND hr = 0 ORDER BY NOMOR";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$ordid 			= $ordid+1;
					$nomor_01		= $rw_01->NOMOR;
					$t_ke_01		= $rw_01->t_ke;
					$t_tg_01		= $rw_01->t_tg;
					$t_prg_01		= $rw_01->t_prg;
					$t_nil_01		= $rw_01->t_nil;
					$t_nilt_01		= $rw_01->t_nilt;
					$t_ppn_01		= $rw_01->t_ppn;
					$tg_bayar_01	= $rw_01->tg_bayar;
					$bayar_01		= $rw_01->bayar;
					$nomor_fp_01	= $rw_01->nomor_fp;
					$tgl_i_01		= $rw_01->tgl_i;
					$user_i_01		= $rw_01->user_i;
					$uraian_01		= addslashes($rw_01->uraian);

					// START : INSERT HEADER
						$s_ins1 	= "INSERT INTO tbl_projinv_header (PINV_CODE, PINV_MANNO, PINV_STEP, PINV_CAT, PINV_SOURCE, PRJCODE, PINV_OWNER,
											PINV_DATE, PINV_ENDDATE, PINV_DPPER, PINV_PROG, PINV_DPVAL, PINV_DPVALPPn, PINV_PROGVAL, PINV_PROGVALPPn, PINV_PROGAPP, PINV_PROGAPPVAL, PINV_PROGCUR, PINV_PROGCURVAL, PINV_TOTVAL, PINV_TOTVALPPn, GPINV_TOTVAL, PINV_NOTES, PINV_STAT, PINV_PAIDDATE, PINV_PAIDAM, PINV_TAXDATE, PINV_TAXNO, PINV_CREATED)
										VALUES ('$nomor_01', '$nomor_01', '$t_ke_01', 2, '', '$PRJCODE', '',
											'$t_tg_01', '$t_tg_01', 0, '$t_prg_01', 0, 0, '$t_nil_01', '$t_ppn_01',
											'$t_prg_01', '$t_nil_01', '$t_prg_01', '$t_nil_01', '$t_prg_01', '$t_ppn_01', '$t_prg_01',
											'$uraian_01', 3, '$tg_bayar_01', '$bayar_01', '$tg_bayar_01', '$nomor_fp_01', '$tgl_i_01')";
						$this->db->query($s_ins1);
					// END : INSERT HEADER

					$s_IMP		= "tbl_projinv_header WHERE PRJCODE = '$PRJCODE'";
					$r_IMP		= $this->db->count_all($s_IMP);
					$percent 	= intval($r_IMP/$r_TIMP * 100)."%";

					echo '<script>
					parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMP. ' / '.$r_TIMP.') processed</span></div>";</script>';
					echo '<script>
					parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_IMPD. ' / '.$r_TIMP.') processed</span></div>";</script>';

				    ob_flush();
				    flush();
				endforeach;
			// END : RENDER PROCEDUR - RENDER PROJ INV

			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		}
	}
	else if($IMP_TYPE == 'COPY_BUDGET')
	{
		$this->db->trans_begin();
			$PRJCODE_D		= $COAH_CODEY;
			$PRJCODE_DHO 	= "";
			$PRJPERIOD_D 	= $PRJCODE;
			$PRJPERIOD_DP	= $PRJPERIOD;
			$sql 			= "SELECT PRJCODE_HO, PRJPERIOD, PRJPERIOD_P  FROM tbl_project WHERE PRJCODE = '$PRJCODE_D'";
			$result 		= $this->db->query($sql)->result();
			foreach($result as $row) :
				$PRJCODE_DHO 	= $row ->PRJCODE_HO;
				$PRJPERIOD_D 	= $row ->PRJPERIOD;
				$PRJPERIOD_DP 	= $row ->PRJCODE_HO;
			endforeach;

	        // CLEAR ALL DATA
	        	$sd_01 	= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE_D'";
	        	$this->db->query($sd_01);

	        	$sd_02 	= "DELETE FROM tbl_joblist WHERE PRJCODE = '$PRJCODE_D'";
	        	$this->db->query($sd_02);

	        	$sd_03 	= "DELETE FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE_D'";
	        	$this->db->query($sd_03);

	        	$sd_04 	= "DELETE FROM tbl_item WHERE PRJCODE = '$PRJCODE_D'";
	        	$this->db->query($sd_04);
			
			// 01. COPY DATA IN tbl_boqlist
				$s_01 	= "INSERT INTO tbl_boqlist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO, PRJPERIOD, ITM_CODE, JOBDESC, JOBGRP,
								JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLASTH, ISLAST,
								BOQ_STAT, Patt_Number)
							SELECT ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, '$PRJCODE_D', '$PRJCODE_DHO', '$PRJPERIOD_D', ITM_CODE, JOBDESC, JOBGRP,
								JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLASTH, ISLAST,
								BOQ_STAT, Patt_Number FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_01);

			// 02. COPY DATA IN tbl_joblist
				$s_02 	= "INSERT INTO tbl_joblist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, PRJCODE, PRJCODE_HO, PRJPERIOD, PRJPERIOD_P, JOBDESC, JOBGRP,
								JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLASTH, ISLAST,
								WBS_STAT, Patt_Number)
							SELECT ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, '$PRJCODE_D', '$PRJCODE_DHO', '$PRJPERIOD_D', PRJPERIOD_P, JOBDESC, JOBGRP,
								JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLASTH, ISLAST,
								WBS_STAT, Patt_Number FROM tbl_joblist WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_02);
			
			// 03. COPY DATA IN tbl_joblist_detail
				$s_03	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
				$r_03	= $this->db->count_all($s_03);

				$s_04	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE != ''";
				$r_04	= $this->db->count_all($s_04);

				$s_05 	= "SELECT ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, PRJCODE_HO, PRJPERIOD, PRJPERIOD_P, JOBDESC, ITM_GROUP, GROUP_CATEG, 
								ITM_CODE, ITM_UNIT, IS_LEVEL, ISLASTH, ISLAST, ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, BOQ_VOLM
							FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
				$r_05 	= $this->db->query($s_05)->result();

				$rw5 	= 0;
				foreach($r_05 as $rw_05):
					$ORD_ID_A 		= $rw_05->ORD_ID;
					$JOBCODEDET_A 	= $rw_05->JOBCODEDET;
					$JOBCODEID_A 	= $rw_05->JOBCODEID;
					$JOBPARENT_A 	= $rw_05->JOBPARENT;
					$PRJCODE_HO_A	= $PRJCODE_DHO;
					$PRJPERIOD_A 	= $PRJPERIOD_D;
					$PRJPERIOD_P_A 	= $PRJPERIOD_DP;
					$JOBDESC_A 		= addslashes($rw_05->JOBDESC);
					$ITM_GROUP_A 	= $rw_05->ITM_GROUP;
					$GROUP_CATEG_A 	= $rw_05->GROUP_CATEG;
					$ITM_CODE_A 	= $rw_05->ITM_CODE;
					$ITM_UNIT_A 	= addslashes($rw_05->ITM_UNIT);
					$IS_LEVEL_A 	= $rw_05->IS_LEVEL;
					$ISLASTH_A 		= $rw_05->ISLASTH;
					$ISLAST_A 		= $rw_05->ISLAST;
					$ITM_VOLM 		= $rw_05->ITM_VOLM;
					$ITM_PRICE 		= $rw_05->ITM_PRICE;
					$ITM_LASTP 		= $rw_05->ITM_LASTP;
					$ITM_AVGP 		= $rw_05->ITM_AVGP;
					$ITM_BUDG 		= $rw_05->ITM_BUDG;
					$BOQ_VOLM 		= $rw_05->BOQ_VOLM;
					$WBSD_STAT_A 	= 1;
					$PattNumber_A 	= $rw5+1;	

					// START : INSERT JOBLIST
						$s_05Ins		= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, PRJCODE, PRJCODE_HO, 
												PRJPERIOD, PRJPERIOD_P, JOBDESC, ITM_GROUP, GROUP_CATEG,
												ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, BOQ_VOLM,
												ITM_CODE, ITM_UNIT, IS_LEVEL, ISLASTH, ISLAST, Patt_Number)
											VALUES ($ORD_ID_A, '$JOBCODEDET_A', '$JOBCODEID_A', '$JOBPARENT_A', '$PRJCODE_D', '$PRJCODE_HO_A',
												'$PRJPERIOD_A', '$PRJPERIOD_P_A', '$JOBDESC_A', '$ITM_GROUP_A','$GROUP_CATEG_A', 
												'$ITM_VOLM', '$ITM_PRICE', '$ITM_LASTP', '$ITM_AVGP', '$ITM_BUDG', '$BOQ_VOLM',
												'$ITM_CODE_A', '$ITM_UNIT_A', '$IS_LEVEL_A', '$ISLASTH_A', '$ISLAST_A', '$PattNumber_A')";
						$this->db->query($s_05Ins);

						// TOTAL ROW IMPORTED
					        $sIMP	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE_D'";
							$rIMP	= $this->db->count_all($sIMP);

						// START : SENDING PROCESS
							$percent = intval($rIMP/$r_03 * 100)."%";
							echo '<script>
						    parent.document.getElementById("progressbarXXMDL").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$r_03.') processed</span></div>";</script>';

						    ob_flush(); 
						    flush();
						// END : SENDING PROCESS
					// END : INSERT JOBLIST
				endforeach;
			
			// 02. COPY DATA IN tbl_item
				$s_06 	= "INSERT INTO tbl_item (PRJCODE, PRJCODE_HO, PRJPERIOD, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_CLASS, ITM_NAME, ITM_TYPE,
								ITM_UNIT, UMCODE, ACC_ID, ACC_ID_UM, STATUS, ISMTRL, ITM_LR, ISMAJOR, ISCOUNT, LASTNO)
							SELECT '$PRJCODE_D', '$PRJCODE_DHO', '$PRJPERIOD_D', ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_CLASS, ITM_NAME, ITM_TYPE,
									ITM_UNIT, UMCODE, ACC_ID, ACC_ID_UM, STATUS, ISMTRL, ITM_LR, ISMAJOR, ISCOUNT, LASTNO
								FROM tbl_item WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_06);

			// 03. COPY JOBCREATE HEADER /DETAIL
				$dtTime = date('Y-m-d H:i:s');
				$s_07 	= "INSERT INTO tbl_jobcreate_header (PRJCODE, JOB_NUM, JOB_DATE, JOB_PARCODE, JOB_PARDESC, JOB_UNIT, JOB_BOQV, JOB_BOQP, JOB_BOQT,
								JOB_RAPV, JOB_RAPP, JOB_RAPT, JOB_NOTE, JOB_CREATER, JOB_CREATED, JOB_STAT, STATDESC, STATCOL, CREATERNM)
							SELECT '$PRJCODE_D', CONCAT(JOB_NUM,'C'), '$dtTime', JOB_PARCODE, JOB_PARDESC, JOB_UNIT, JOB_BOQV, JOB_BOQP, JOB_BOQT,
								JOB_RAPV, JOB_RAPP, JOB_RAPT, JOB_NOTE, JOB_CREATER, '$dtTime', JOB_STAT, STATDESC, STATCOL, CREATERNM
							FROM tbl_jobcreate_header WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_07);

				$s_08 	= "INSERT INTO tbl_jobcreate_detail (PRJCODE, JOB_NUM, JOBCODEID, JOBPARENT, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_GROUP,
								ITM_BOQV, ITM_BOQP, ITM_KOEF, ITM_RAPV, ITM_RAPP, ITM_TOTAL, ITM_NOTES, ISLOCK, ISLOCK2, LOCKER_ID, LOCKER_NM)
							SELECT '$PRJCODE_D', JOB_NUM, JOBCODEID, JOBPARENT, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_GROUP,
								ITM_BOQV, ITM_BOQP, ITM_KOEF, ITM_RAPV, ITM_RAPP, ITM_TOTAL, ITM_NOTES, ISLOCK, ISLOCK2, LOCKER_ID, LOCKER_NM
							FROM tbl_jobcreate_detail WHERE PRJCODE = '$PRJCODE'";
				$this->db->query($s_08);

			// IMPORT COMPLETE INFO
				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXXMDL").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>parent.updStatMDL();</script>';

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
	}
	else if($IMP_TYPE == 'RECOUNTRAP')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');

		$SYNC_PRJ	= $PRJCODE;

		// PREPARING
			$percent = intval(0)."%";
			echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';
			echo '<script>
		    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

		    ob_flush();
		    flush();

		    $s_DUPLITM 	= "SELECT ITM_CODE, JOBPARENT, COUNT(ITM_CODE) FROM tbl_joblist_detail
							WHERE PRJCODE = '$SYNC_PRJ'
							GROUP BY 
							    ITM_CODE, JOBPARENT
							HAVING 
							    COUNT(ITM_CODE) > 1 AND ITM_CODE != ''";
			$r_DUPLITM	= $this->db->query($s_DUPLITM)->result();
			foreach($r_DUPLITM as $rw_DUPLITM):
				$JPAR 	= $rw_DUPLITM->JOBPARENT;
				$ITMC 	= $rw_DUPLITM->ITM_CODE;

				$dDUPL1	= "DELETE FROM tbl_jobcreate_detail
							WHERE JOBPARENT = '$JPAR' AND ITM_CODE = '$ITMC' AND PRJCODE = '$SYNC_PRJ' AND ISNULL(JOBCODEID)";
				$this->db->query($dDUPL1);


				$dDUPL2	= "DELETE t1 FROM tbl_joblist_detail t1
							INNER JOIN tbl_joblist_detail t2 
							WHERE  
							    t1.ID < t2.ID AND 
							    t1.ITM_CODE = t2.ITM_CODE
									AND t1.JOBPARENT = t2.JOBPARENT
									AND t1.JOBPARENT = '$JPAR' AND t1.PRJCODE = '$SYNC_PRJ'";
				$this->db->query($dDUPL2);
			endforeach;

	    // RESET ORDER
			$sql_0		= "UPDATE tbl_joblist_detail SET ISUPD = 0 WHERE PRJCODE = '$SYNC_PRJ'";
			$this->db->query($sql_0);

	    // TOTAL BARIS HEADER
			$s_TROW		= "SELECT COUNT(*) AS TROW FROM (SELECT COUNT(*) FROM tbl_joblist_detail WHERE ISLAST = 1 AND ISUPD = 0
							AND PRJCODE = '$SYNC_PRJ' GROUP BY JOBPARENT) AS TOTROW";
			$r_TROW		= $this->db->query($s_TROW)->result();
			foreach($r_TROW as $rw_TROW):
				$TOTROW = $rw_TROW->TROW;
			endforeach;
			if($TOTROW == 0)
				$TOTROW = 1;

		// START : PROCEDUR - RESET ORDER
			// 1.	MELAKUKAN PENGULANGAN BERDASARKAN ITEM DETIL
				$s_01	= "SELECT DISTINCT JOBPARENT FROM tbl_joblist_detail WHERE ISLAST = 1 AND PRJCODE = '$SYNC_PRJ' ORDER BY ORD_ID";
				$r_01 = $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$JOBP01		= $rw_01->JOBPARENT;
					
					$s_SUM01	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBP01' AND PRJCODE = '$SYNC_PRJ'";
					$r_SUM01 	= $this->db->query($s_SUM01)->result();
					foreach($r_SUM01 as $rw_SUM01) :
						$TBUD01 	= $rw_SUM01->TOTBUDG;

						$s_UP01A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD01, ITM_PRICE = $TBUD01 / ITM_VOLM,
											ITM_LASTP = $TBUD01 / ITM_VOLM, ITM_AVGP = $TBUD01 / ITM_VOLM, ISUPD = 1
										WHERE JOBCODEID = '$JOBP01' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($s_UP01A);

						$s_UP01B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD01, PRICE = $TBUD01 / JOBVOLM
										WHERE JOBCODEID = '$JOBP01' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($s_UP01B);

						$s_UP01C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD01, PRICE = $TBUD01 / JOBVOLM
										WHERE JOBCODEID = '$JOBP01' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($s_UP01C);

						$s_UPDC		= "tbl_joblist_detail WHERE ISUPD = 1 AND PRJCODE = '$SYNC_PRJ'";
						$r_UPDC		= $this->db->count_all($s_UPDC);
						echo "r_UPDC = $r_UPDC/$TOTROW * 100<br>";
						$percent 	= intval($r_UPDC/$TOTROW * 100)."%";
						echo '<script>
						parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_UPDC. ' / '.$TOTROW.') processed</span></div>";</script>';
						echo '<script>
						parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$r_UPDC. ' / '.$TOTROW.') processed</span></div>";</script>';

					    ob_flush();
					    flush();

						$s_02		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP01' AND PRJCODE = '$SYNC_PRJ'";
						$r_02 		= $this->db->query($s_02)->result();
						foreach($r_02 as $r_02) :
							$JOBP02 	= $rw_02->JOBPARENT;
							$s_SUM02	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBP02' AND PRJCODE = '$SYNC_PRJ'";
							$r_SUM02 	= $this->db->query($s_SUM02)->result();
							foreach($r_SUM02 as $rw_SUM02) :
								$TBUD02 = $rw_SUM02->TOTBUDG;

								$s_UP02A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD02, ITM_PRICE = $TBUD02 / ITM_VOLM,
													ITM_LASTP = $TBUD02 / ITM_VOLM, ITM_AVGP = $TBUD02 / ITM_VOLM
												WHERE JOBCODEID = '$JOBP02' AND PRJCODE = '$SYNC_PRJ'";
								$this->db->query($s_UP02A);

								$s_UP02B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD02, PRICE = $TBUD02 / JOBVOLM
												WHERE JOBCODEID = '$JOBP02' AND PRJCODE = '$SYNC_PRJ'";
								$this->db->query($s_UP02B);

								$s_UP02C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD02, PRICE = $TBUD02 / JOBVOLM
												WHERE JOBCODEID = '$JOBP02' AND PRJCODE = '$SYNC_PRJ'";
								$this->db->query($s_UP02C);

								$s_03		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP02' AND PRJCODE = '$SYNC_PRJ'";
								$r_03 		= $this->db->query($s_03)->result();
								foreach($r_03 as $r_03) :
									$JOBP03 	= $rw_03->JOBPARENT;
									$s_SUM03	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBP03' AND PRJCODE = '$SYNC_PRJ'";
									$r_SUM03 	= $this->db->query($s_SUM03)->result();
									foreach($r_SUM03 as $rw_SUM03) :
										$TBUD03 = $rw_SUM03->TOTBUDG;

										$s_UP03A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD03, ITM_PRICE = $TBUD03 / ITM_VOLM,
															ITM_LASTP = $TBUD03 / ITM_VOLM, ITM_AVGP = $TBUD03 / ITM_VOLM
														WHERE JOBCODEID = '$JOBP03' AND PRJCODE = '$SYNC_PRJ'";
										$this->db->query($s_UP03A);

										$s_UP03B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD03, PRICE = $TBUD03 / JOBVOLM
														WHERE JOBCODEID = '$JOBP03' AND PRJCODE = '$SYNC_PRJ'";
										$this->db->query($s_UP03B);

										$s_UP03C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD03, PRICE = $TBUD03 / JOBVOLM
														WHERE JOBCODEID = '$JOBP03' AND PRJCODE = '$SYNC_PRJ'";
										$this->db->query($s_UP03C);

										$s_04		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP03' AND PRJCODE = '$SYNC_PRJ'";
										$r_04 		= $this->db->query($s_04)->result();
										foreach($r_04 as $r_04) :
											$JOBP04 	= $rw_04->JOBPARENT;
											$s_SUM04	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBP04' AND PRJCODE = '$SYNC_PRJ'";
											$r_SUM04 	= $this->db->query($s_SUM04)->result();
											foreach($r_SUM04 as $rw_SUM04) :
												$TBUD04 = $rw_SUM04->TOTBUDG;

												$s_UP04A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD04, ITM_PRICE = $TBUD04 / ITM_VOLM,
																	ITM_LASTP = $TBUD04 / ITM_VOLM, ITM_AVGP = $TBUD04 / ITM_VOLM
																WHERE JOBCODEID = '$JOBP04' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($s_UP04A);

												$s_UP04B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD04, PRICE = $TBUD04 / JOBVOLM
																WHERE JOBCODEID = '$JOBP04' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($s_UP04B);

												$s_UP04C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD04, PRICE = $TBUD04 / JOBVOLM
																WHERE JOBCODEID = '$JOBP04' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($s_UP04C);

												$s_05		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP04' AND PRJCODE = '$SYNC_PRJ'";
												$r_05 		= $this->db->query($s_05)->result();
												foreach($r_05 as $r_05) :
													$JOBP05 = $rw_05->JOBPARENT;
													$s_SUM05	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBP05' AND PRJCODE = '$SYNC_PRJ'";
													$r_SUM05 	= $this->db->query($s_SUM05)->result();
													foreach($r_SUM05 as $rw_SUM05) :
														$TBUD05 	= $rw_SUM05->TOTBUDG;

														$s_UP05A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD05, ITM_PRICE = $TBUD05 / ITM_VOLM,
																			ITM_LASTP = $TBUD05 / ITM_VOLM, ITM_AVGP = $TBUD05 / ITM_VOLM
																		WHERE JOBCODEID = '$JOBP05' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($s_UP05A);

														$s_UP05B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD05, PRICE = $TBUD05 / JOBVOLM
																		WHERE JOBCODEID = '$JOBP05' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($s_UP05B);

														$s_UP05C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD05, PRICE = $TBUD05 / JOBVOLM
																		WHERE JOBCODEID = '$JOBP05' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($s_UP05C);

														$s_06		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP05' AND PRJCODE = '$SYNC_PRJ'";
														$r_06 		= $this->db->query($s_06)->result();
														foreach($r_06 as $r_06) :
															$JOBP06 	= $rw_06->JOBPARENT;
															$s_SUM06	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBP06' AND PRJCODE = '$SYNC_PRJ'";
															$r_SUM06 	= $this->db->query($s_SUM06)->result();
															foreach($r_SUM06 as $rw_SUM06) :
																$TBUD06 	= $rw_SUM06->TOTBUDG;

																$s_UP06A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD06, ITM_PRICE = $TBUD06 / ITM_VOLM,
																					ITM_LASTP = $TBUD06 / ITM_VOLM, ITM_AVGP = $TBUD06 / ITM_VOLM
																				WHERE JOBCODEID = '$JOBP06' AND PRJCODE = '$SYNC_PRJ'";
																$this->db->query($s_UP06A);

																$s_UP06B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD06, PRICE = $TBUD06 / JOBVOLM
																				WHERE JOBCODEID = '$JOBP06' AND PRJCODE = '$SYNC_PRJ'";
																$this->db->query($s_UP06B);

																$s_UP06C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD06, PRICE = $TBUD06 / JOBVOLM
																				WHERE JOBCODEID = '$JOBP06' AND PRJCODE = '$SYNC_PRJ'";
																$this->db->query($s_UP06C);

																$s_07		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP06' AND PRJCODE = '$SYNC_PRJ'";
																$r_07 		= $this->db->query($s_07)->result();
																foreach($r_07 as $r_07) :
																	$JOBP07 	= $rw_07->JOBPARENT;
																	$s_SUM07	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBP07' AND PRJCODE = '$SYNC_PRJ'";
																	$r_SUM07 	= $this->db->query($s_SUM07)->result();
																	foreach($r_SUM07 as $rw_SUM07) :
																		$TBUD07 	= $rw_SUM07->TOTBUDG;

																		$s_UP07A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD07, ITM_PRICE = $TBUD07 / ITM_VOLM,
																							ITM_LASTP = $TBUD07 / ITM_VOLM, ITM_AVGP = $TBUD07 / ITM_VOLM
																						WHERE JOBCODEID = '$JOBP07' AND PRJCODE = '$SYNC_PRJ'";
																		$this->db->query($s_UP07A);

																		$s_UP07B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD07, PRICE = $TBUD07 / JOBVOLM
																						WHERE JOBCODEID = '$JOBP07' AND PRJCODE = '$SYNC_PRJ'";
																		$this->db->query($s_UP07B);

																		$s_UP07C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD07, PRICE = $TBUD07 / JOBVOLM
																						WHERE JOBCODEID = '$JOBP07' AND PRJCODE = '$SYNC_PRJ'";
																		$this->db->query($s_UP07C);

																		$s_08		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP07' AND PRJCODE = '$SYNC_PRJ'";
																		$r_08 		= $this->db->query($s_08)->result();
																		foreach($r_08 as $r_08) :
																			$JOBP08 	= $rw_08->JOBPARENT;
																			$s_SUM08	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail
																							WHERE JOBPARENT = '$JOBP08' AND PRJCODE = '$SYNC_PRJ'";
																			$r_SUM08 	= $this->db->query($s_SUM08)->result();
																			foreach($r_SUM08 as $rw_SUM08) :
																				$TBUD08 	= $rw_SUM08->TOTBUDG;

																				$s_UP08A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD08,
																									ITM_PRICE = $TBUD08 / ITM_VOLM,
																									ITM_LASTP = $TBUD08 / ITM_VOLM, ITM_AVGP = $TBUD08 / ITM_VOLM
																								WHERE JOBCODEID = '$JOBP08' AND PRJCODE = '$SYNC_PRJ'";
																				$this->db->query($s_UP08A);

																				$s_UP08B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD08, PRICE = $TBUD08 / JOBVOLM
																								WHERE JOBCODEID = '$JOBP08' AND PRJCODE = '$SYNC_PRJ'";
																				$this->db->query($s_UP08B);

																				$s_UP08C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD08, PRICE = $TBUD08 / JOBVOLM
																								WHERE JOBCODEID = '$JOBP08' AND PRJCODE = '$SYNC_PRJ'";
																				$this->db->query($s_UP08C);

																				$s_09		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP08' AND PRJCODE = '$SYNC_PRJ'";
																				$r_09 		= $this->db->query($s_09)->result();
																				foreach($r_09 as $r_09) :
																					$JOBP09 	= $rw_09->JOBPARENT;
																					$s_SUM09	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail
																									WHERE JOBPARENT = '$JOBP09' AND PRJCODE = '$SYNC_PRJ'";
																					$r_SUM09 	= $this->db->query($s_SUM09)->result();
																					foreach($r_SUM09 as $rw_SUM09) :
																						$TBUD09 	= $rw_SUM09->TOTBUDG;

																						$s_UP09A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD09,
																											ITM_PRICE = $TBUD09 / ITM_VOLM,
																											ITM_LASTP = $TBUD09 / ITM_VOLM,
																											ITM_AVGP = $TBUD09 / ITM_VOLM
																										WHERE JOBCODEID = '$JOBP09' AND PRJCODE = '$SYNC_PRJ'";
																						$this->db->query($s_UP09A);

																						$s_UP09B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD09,
																											PRICE = $TBUD09 / JOBVOLM
																										WHERE JOBCODEID = '$JOBP09' AND PRJCODE = '$SYNC_PRJ'";
																						$this->db->query($s_UP09B);

																						$s_UP09C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD09,
																											PRICE = $TBUD09 / JOBVOLM
																										WHERE JOBCODEID = '$JOBP09' AND PRJCODE = '$SYNC_PRJ'";
																						$this->db->query($s_UP09C);

																						$s_10		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP10' AND PRJCODE = '$SYNC_PRJ'";
																						$r_10 		= $this->db->query($s_10)->result();
																						foreach($r_10 as $r_10) :
																							$JOBP10 	= $rw_10->JOBPARENT;
																							$s_SUM10	= "SELECT SUM(ITM_BUDG) AS TOTBUDG FROM tbl_joblist_detail
																											WHERE JOBPARENT = '$JOBP10' AND PRJCODE = '$SYNC_PRJ'";
																							$r_SUM10 	= $this->db->query($s_SUM10)->result();
																							foreach($r_SUM10 as $rw_SUM10) :
																								$TBUD10 	= $rw_SUM10->TOTBUDG;

																								$s_UP10A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TBUD10,
																													ITM_PRICE = $TBUD10 / ITM_VOLM,
																													ITM_LASTP = $TBUD10 / ITM_VOLM,
																													ITM_AVGP = $TBUD10 / ITM_VOLM
																												WHERE JOBCODEID = '$JOBP10' AND PRJCODE = '$SYNC_PRJ'";
																								$this->db->query($s_UP10A);

																								$s_UP10B	= "UPDATE tbl_joblist SET JOBCOST = $TBUD10,
																													PRICE = $TBUD10 / JOBVOLM
																												WHERE JOBCODEID = '$JOBP10' AND PRJCODE = '$SYNC_PRJ'";
																								$this->db->query($s_UP10B);

																								$s_UP10C	= "UPDATE tbl_joblist SET JOBCOST = $TBUD10,
																													PRICE = $TBUD10 / JOBVOLM
																												WHERE JOBCODEID = '$JOBP10' AND PRJCODE = '$SYNC_PRJ'";
																								$this->db->query($s_UP10C);

																								/*$s_10		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBP10' AND PRJCODE = '$SYNC_PRJ'";
																								$r_10 		= $this->db->query($s_10)->result();
																								foreach($r_10 as $r_10) :
																									$JOBP10 	= $rw_10->JOBPARENT;
																								endforeach;*/
																							endforeach;
																						endforeach;
																					endforeach;
																				endforeach;
																			endforeach;
																		endforeach;
																	endforeach;
																endforeach;
															endforeach;
														endforeach;
													endforeach;
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// 2.	COMPLETE INFO
				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
					    parent.updStat();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'SYNJRNCOA_220526')
	{
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];
			
        // START : TOTAL DEBET KREDIT
            $TOTD		= 0;
            $TOTK		= 0;
			$SumRowB	= 0;
			$totCount	= 0;

			// RESET DATA
				$sqlResLSyn	= "TRUNCATE tbl_lastsync";
				$this->db->query($sqlResLSyn);

				$sqlResSyn	= "UPDATE tbl_journaldetail SET isSyncY = 0";
				$this->db->query($sqlResSyn);

				$sqlResSyn	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.GEJ_STAT = B.GEJ_STAT, A.Manual_No = B.Manual_No,
									A.JournalH_Date = B.JournalH_Date
								WHERE A.JournalH_Code = B.JournalH_Code";
				$this->db->query($sqlResSyn);

				$sqlResCOA	= "UPDATE tbl_chartaccount SET 
									Base_Debet 		= 0,
									Base_Kredit 	= 0,
									Base_Debet2 	= 0,
									Base_Kredit2 	= 0,
									BaseD_2021 		= 0,
									BaseK_2021 		= 0,
									BaseD_2022 		= 0,
									BaseK_2022 		= 0";
				$this->db->query($sqlResCOA);

			// TOTAL DATA
				$s_00 		= "tbl_journaldetail WHERE GEJ_STAT = '3' AND isSyncY = '0'";
				$TOTJRN		= $this->db->count_all($s_00);
			
			$sqlJOURNT1	= "SELECT
								A.JournalD_Id,
								A.JournalH_Code,
								A.JournalH_Date,
								A.proj_Code,
								A.Acc_Id,
								A.Base_Debet,
								A.Base_Kredit,
								A.LastUpdate
							FROM
								tbl_journaldetail A
							WHERE
								A.GEJ_STAT = 3 AND A.isSyncY = 0
							ORDER BY A.JournalH_Date ASC";
			$resJOURNT1	= $this->db->query($sqlJOURNT1)->result();
			foreach($resJOURNT1 as $rowJ1) :
				$totCount 	= $totCount + 1;
				$journID	= $rowJ1->JournalD_Id;
				$journCode	= $rowJ1->JournalH_Code;
				$journDate	= $rowJ1->JournalH_Date;
				$LastUpdate	= $rowJ1->LastUpdate;
				$AccId		= $rowJ1->Acc_Id;
				$proj_Code1	= $rowJ1->proj_Code;
				$BaseDebet	= $rowJ1->Base_Debet;
				$BaseKredit	= $rowJ1->Base_Kredit;

				$accYr		= date('Y', strtotime($journDate));

				$syncPRJ	= '';
				$sqlSyns	= "SELECT syncPRJ FROM tbl_chartaccount 
								WHERE PRJCODE = '$proj_Code1' AND Account_Number = '$AccId'";
				$resSyns	= $this->db->query($sqlSyns)->result();
				foreach($resSyns as $rowSYNC) :
					$syncPRJ= $rowSYNC->syncPRJ;
				endforeach;

				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
			
				if($jmD > 0)
				{
					$SYNC_PRJ	= '';
					for($i=0; $i < $jmD; $i++)
					{
						$SYNC_PRJ	= $dataPecah[$i];
						$sqlUpdCOA	= "UPDATE tbl_chartaccount SET 
											Base_Debet 		= Base_Debet+$BaseDebet,
											Base_Kredit 	= Base_Kredit+$BaseKredit,
											Base_Debet2 	= Base_Debet2+$BaseDebet,
											Base_Kredit2 	= Base_Kredit2+$BaseKredit,
											BaseD_$accYr 	= BaseD_$accYr+$BaseDebet,
											BaseK_$accYr 	= BaseK_$accYr+$BaseKredit
										WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$AccId'";
						$this->db->query($sqlUpdCOA);

						$s_00 		= "tbl_lastsync WHERE LS_YEAR = $accYr AND PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$AccId'";
						$r_00 		= $this->db->count_all($s_00);
						if($r_00 == 0)
						{
							$s_01 	= "INSERT INTO tbl_lastsync (LS_YEAR, PRJCODE, LS_ACC_ID, LS_DEBET, LS_KREDIT)
										VALUES
										('$accYr', '$SYNC_PRJ', '$AccId', '$BaseDebet', '$BaseKredit')";
							$this->db->query($s_01);
						}
						else
						{
							$s_02	= "UPDATE tbl_lastsync SET
												LS_DEBET 	= LS_DEBET+$BaseDebet,
												LS_KREDIT 	= LS_KREDIT+$BaseKredit
											WHERE LS_YEAR = $accYr AND PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$AccId'";
							$this->db->query($s_02);
						}
					}
				}

				$sqlUpdJRD	= "UPDATE tbl_journaldetail SET isSyncY = 1 WHERE JournalD_Id = '$journID'";
				$this->db->query($sqlUpdJRD);

				$s_03 		= "tbl_journaldetail WHERE GEJ_STAT = '3' AND isSyncY = '1'";
				$TOTPROC	= $this->db->count_all($s_03);
				$percent 	= intval($TOTPROC/$TOTJRN * 100)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$TOTPROC. ' / '.$TOTJRN.') processed</span></div>";</script>';
				echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$TOTPROC. ' / '.$TOTJRN.') processed</span></div>";</script>';

			    ob_flush();
			    flush();
			endforeach;
        // END : TOTAL DEBET KREDIT

		// COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
	}
	else if($IMP_TYPE == 'SYNJRNCOA')
	{
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];
			
        // START : TOTAL DEBET KREDIT
            $TOTD		= 0;
            $TOTK		= 0;
			$SumRowB	= 0;
			$totCount	= 0;

			// RESET DATA
				$sqlResSyn	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.GEJ_STAT = B.GEJ_STAT, A.Manual_No = B.Manual_No,
									A.JournalH_Date = B.JournalH_Date
								WHERE A.JournalH_Code = B.JournalH_Code";
				$this->db->query($sqlResSyn);

			// TOTAL DATA
				$s_00 		= "tbl_journaldetail WHERE GEJ_STAT = '3' AND isSyncY = '0'";
				$TOTJRN		= $this->db->count_all($s_00);
			
			$sqlJOURNT1	= "SELECT
								A.JournalD_Id,
								A.JournalH_Code,
								A.JournalH_Date,
								A.proj_Code,
								A.Acc_Id,
								A.Base_Debet,
								A.Base_Kredit,
								A.LastUpdate
							FROM
								tbl_journaldetail A
							WHERE
								A.GEJ_STAT = 3 AND A.isSyncY = 0
							ORDER BY A.JournalH_Date ASC";
			$resJOURNT1	= $this->db->query($sqlJOURNT1)->result();
			foreach($resJOURNT1 as $rowJ1) :
				$totCount 	= $totCount + 1;
				$journID	= $rowJ1->JournalD_Id;
				$journCode	= $rowJ1->JournalH_Code;
				$journDate	= $rowJ1->JournalH_Date;
				$LastUpdate	= $rowJ1->LastUpdate;
				$AccId		= $rowJ1->Acc_Id;
				$proj_Code1	= $rowJ1->proj_Code;
				$BaseDebet	= $rowJ1->Base_Debet;
				$BaseKredit	= $rowJ1->Base_Kredit;

				$accYr		= date('Y', strtotime($journDate));

				$syncPRJ	= '';
				$sqlSyns	= "SELECT syncPRJ FROM tbl_chartaccount 
								WHERE PRJCODE = '$proj_Code1' AND Account_Number = '$AccId'";
				$resSyns	= $this->db->query($sqlSyns)->result();
				foreach($resSyns as $rowSYNC) :
					$syncPRJ= $rowSYNC->syncPRJ;
				endforeach;

				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
			
				if($jmD > 0)
				{
					$SYNC_PRJ	= '';
					for($i=0; $i < $jmD; $i++)
					{
						$SYNC_PRJ	= $dataPecah[$i];

						$LS_D		= 0;
						$LS_K 		= 0;
						$sLSyns		= "SELECT IFNULL(LS_DEBET,0) AS LS_DEBET, IFNULL(LS_KREDIT,0) AS LS_KREDIT FROM tbl_lastsync
										WHERE LS_YEAR = $accYr AND PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$AccId'";
						$rLSyns	= $this->db->query($sLSyns)->result();
						foreach($rLSyns as $rwSyns) :
							$LS_D 	= $rwSyns->LS_DEBET;
							$LS_K 	= $rwSyns->LS_KREDIT;
						endforeach;

						$sqlUpdCOA	= "UPDATE tbl_chartaccount SET 
											Base_Debet 		= $LS_D+$BaseDebet,
											Base_Kredit 	= $LS_K+$BaseKredit,
											Base_Debet2 	= $LS_D+$BaseDebet,
											Base_Kredit2 	= $LS_K+$BaseKredit,
											BaseD_$accYr 	= $LS_D+$BaseDebet,
											BaseK_$accYr 	= $LS_K+$BaseKredit
										WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$AccId'";
						$this->db->query($sqlUpdCOA);

						$s_00 		= "tbl_lastsync WHERE LS_YEAR = $accYr AND PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$AccId'";
						$r_00 		= $this->db->count_all($s_00);
						if($r_00 == 0)
						{
							$s_01 	= "INSERT INTO tbl_lastsync (LS_YEAR, PRJCODE, LS_ACC_ID, LS_DEBET, LS_KREDIT)
										VALUES
										('$accYr', '$SYNC_PRJ', '$AccId', '$BaseDebet', '$BaseKredit')";
							$this->db->query($s_01);
						}
						else
						{
							$s_02	= "UPDATE tbl_lastsync SET
												LS_DEBET 	= LS_DEBET+$BaseDebet,
												LS_KREDIT 	= LS_KREDIT+$BaseKredit
											WHERE LS_YEAR = $accYr AND PRJCODE = '$SYNC_PRJ' AND LS_ACC_ID = '$AccId'";
							$this->db->query($s_02);
						}
					}
				}

				$sqlUpdJRD	= "UPDATE tbl_journaldetail SET isSyncY = 1 WHERE JournalD_Id = '$journID'";
				$this->db->query($sqlUpdJRD);

				$s_03 		= "tbl_journaldetail WHERE GEJ_STAT = '3' AND isSyncY = '1'";
				$TOTPROC	= $this->db->count_all($s_03);
				$percent 	= intval($TOTPROC/$TOTJRN * 100)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$TOTPROC. ' / '.$TOTJRN.') processed</span></div>";</script>';
				echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$TOTPROC. ' / '.$TOTJRN.') processed</span></div>";</script>';

			    ob_flush();
			    flush();
			endforeach;
        // END : TOTAL DEBET KREDIT

		// COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
	}
	else if($IMP_TYPE == 'SCURVE')
	{
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];
			
        $PRJCODEH	= '';
        $sqlPRJHO 	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
        $resPRJHO	= $this->db->query($sqlPRJHO)->result();
        foreach($resPRJHO as $rwPRJHO):
            $PRJCODEH 	= $rwPRJHO->PRJCODE;
        endforeach;

		$this->db->trans_begin();
			$BOQH_CODEX		= '';
			$sqlBoQC		= "tbl_projprogres WHERE proj_Code = '$PRJCODE'";
			$resBoQC		= $this->db->count_all($sqlBoQC);
			
			if(isset($_POST['IMP_CODEX']))	// MARKETING
			{$sqldelPRGG		= "DELETE FROM tbl_projprogres WHERE proj_Code = '$PRJCODE'";
				$this->db->query($sqldelPRGG);
				
				$sqlUpdPRGG1	= "UPDATE tbl_progg_uphist SET PROGG_STAT = 3 WHERE PROGG_STAT = 2 AND PROGG_PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpdPRGG1);

				$PROGG_FN		= '';
				$PROGG_CODEX	= $_POST['IMP_CODEX'];
				$PROGG_CODE		= $_POST['IMP_CODEX'];
				$sqlPrgg		= "SELECT PROGG_FN FROM tbl_progg_uphist WHERE PROGG_CODE = '$PROGG_CODEX'";
				$reslPrgg		= $this->db->query($sqlPrgg)->result();
				foreach($reslPrgg as $rowPrgg) :
					$PROGG_FN	= $rowPrgg->PROGG_FN;
				endforeach;

				$sqlISHO 		= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$PRJCODE_HO	= $rowISHO->PRJCODE_HO;
				endforeach;
				$proj_Code_HO 	= $PRJCODE_HO;
				
				$myXlsFile	= "$PROGG_FN";
				// ADA PERUBAHAN PROSEDURE PEMBACAAN FILE EXCEL
				// PROSEDURE LAMA
					/*if($BOQH_TYPE == 1)
						$myPath 	= "import_excel/import_boq/master/$myXlsFile";
					else
						$myPath 	= "import_excel/import_boq/period/$myXlsFile";
						
					$file 		= file(base_url() . "$myPath"); # read file into array
					$count 		= count($file);
					$data 		= new Spreadsheet_Excel_Reader($myPath);
					echo $count;
					return false;
					$totCol		= 0;
					if($BOQH_TYPE == 2)
					{
						$colName[]	= array();
						for ($i=1; $i <= 100; $i++)
						{
							$colNm			= $data->val(1, $i);
							$colName[$i]	= $data->val(1, $i);
							if($colNm == '')
								break;
							else
								$totCol		= $totCol+1;
						}
					}
					
					// membaca jumlah baris dari data excel
					$baris = $data->rowcount($sheet_index=0);*/

				// START : PROSEDURE BARU
					$myPath = APPPATH."xlsxfile/import_scurve/$myXlsFile";

					$reader 	= ReaderEntityFactory::createXLSXReader($myPath);

					$reader->open($myPath);
					$rowCellH	= 0;
					$totCol 	= 0;
					$isError 	= 0;

					// CREATE ARRAY HEADER
						foreach ($reader->getSheetIterator() as $sheet)
						{
							foreach ($sheet->getRowIterator() as $row)
							{
								$rowCellH 	= $rowCellH+1;
								if($rowCellH == 1)
								{
									$arrHn[]	= array();
									for($cl=0;$cl<=100;$cl++)
									{
							        	$cells 		= $row->getCells();
							        	try 
							        	{
										  	if(isset($cells[$cl]))
										  	{
										  		$HeadCells 		= $cells[$cl];
										  		$HeadNm 		= $HeadCells->getValue() ?: '';
										  		$totCol			= $totCol+1;
										  		$arrHn[$cl]		= $HeadNm;
										  	}
										}
										catch (exceuta $e)
										{
										   	//
										}
									}
								}
								$percent 	= intval(100)."%";
								$collected 	= intval($rowCellH);
								echo '<script>
									  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

							    ob_flush(); 
							    flush();
							}
						}

					// SART : GET ALL ROW DATA AND INSERT INTO TABLE
						function fromExcelToLinux($excel_time) {
						    return ($excel_time-25569)*86400;
						}

				        $baris 		= $rowCellH;
				        $barisD 	= $rowCellH-1;				// DIKURANGI HEADER
						$rata2 		= $baris / 100;				// TOTAL RATA2 PER / 100 --> TOTAL LOOP 100
						$rata2a		= $rata2 * 1;				// TOTAL VALUE PER LOOP
						$rata2b		= intval($rata2 * 1);		// TOTAL LOOP = 100
						$totLoop 	= intval($baris / $rata2a);	// TOTAL LOOP = 100
						$rowCellD	= 0;
						$ORD_ID		= 0;
						$ORD_IDH 	= 0;
						$PATT_NO 	= 0;

						foreach ($reader->getSheetIterator() as $sheet)
						{
							$rowCellD	= 0;
							foreach ($sheet->getRowIterator() as $row)
							{
								$rowCellD 	= $rowCellD+1;
								if($rowCellD > 1)
								{
									$ORDID 		= $rowCellD-1;
									$ITM_CODE 	= "";
									$JOBDESC 	= "";
									for($rw=0;$rw<$totCol;$rw++)
									{
							        	$cells 		= $row->getCells();
						        		$hdName 	= $arrHn[$rw];
									  	$HeadCells 	= $cells[$rw];
									  	$RowData 	= '';
									  	if(null !== $HeadCells)
								  			$RowData 	= $HeadCells->getValue();

										if($hdName == 'Prg_ID')
											$Prg_ID 	= $RowData;
										elseif($hdName == 'proj_Code')
											$proj_Code 	= $RowData;
										elseif($hdName == 'Prg_Step')
											$Prg_Step 	= $RowData;
										elseif($hdName == 'Prg_Date1')
											$Prg_Date1 = $RowData->format('Y-m-d');
										elseif($hdName == 'Prg_Date2')
											$Prg_Date2 = $RowData->format('Y-m-d');
										elseif($hdName == 'Prg_Plan')
										{
											$Prg_Plan1 		= $RowData;
											$Prg_Plan2 		= preg_replace('/[^A-Za-z0-8\.]/', '', $Prg_Plan1);
											$Prg_Plan 		= sprintf("%f", $Prg_Plan1);
											if($Prg_Plan == '')
												$Prg_Plan	= 0;

											$isShow 		= 1;
										}
										elseif($hdName == 'Prg_PlanAkum')
										{
											$Prg_PlanAkum1 	= $RowData;
											$Prg_PlanAkum2	= preg_replace('/[^A-Za-z0-8\.]/', '', $Prg_PlanAkum1);
											$Prg_PlanAkum 	= sprintf("%f", $Prg_PlanAkum1);
											if($Prg_PlanAkum == '')
												$Prg_PlanAkum	= 0;
										}
										elseif($hdName == 'Prg_Real')
										{
											$Prg_Real1 	= $RowData;
											$Prg_Real2	= preg_replace('/[^A-Za-z0-8\.]/', '', $Prg_Real1);
											$Prg_Real 	= sprintf("%f", $Prg_Real1);
											if($Prg_Real == '')
												$Prg_Real	= 0;
										}
										elseif($hdName == 'Prg_RealAkum')
										{
											$Prg_RealAkum1 	= $RowData;
											$Prg_RealAkum2	= preg_replace('/[^A-Za-z0-8\.]/', '', $Prg_RealAkum1);
											$Prg_RealAkum 	= sprintf("%f", $Prg_RealAkum1);
											if($Prg_RealAkum == '')
												$Prg_RealAkum	= 0;

											$isShowRA 		= 0;
											if($Prg_RealAkum > 0)
												$isShowRA 	= 1;
										}
										elseif($hdName == 'Prg_Dev')
										{
											$Prg_Dev1 	= $RowData;
											$Prg_Dev2	= preg_replace('/[^A-Za-z0-8\.]/', '', $Prg_Dev1);
											$Prg_Dev 	= sprintf("%f", $Prg_Dev1);
											if($Prg_Dev == '')
												$Prg_Dev	= 0;

											$isShowDev 		= 0;
											if($Prg_Dev > 0)
												$isShowDev 	= 1;
										}
									}
									$progress_Type	= 3;
									$Prg_Year		= substr($Prg_Date1, 0,4);
									$Prg_Month		= (int)substr($Prg_Date1, 5,2);
									$lastStepPS 	= 0;
									$Prg_LastUpdate = date('Y-m-d');
									$Prg_ProjNotes 	= "";
									$Prg_PstNotes 	= "";

									// INSERT INTO PRGG
										$sqlInsPRGG		= "INSERT INTO tbl_projprogres (proj_Code, proj_Code_HO, progress_Type, Prg_Year, Prg_Month, Prg_Step, 
																Prg_Date1, Prg_Date2, Prg_Plan, Prg_PlanAkum, Prg_Real, Prg_RealAkum, Prg_Dev,
																Prg_ProjNotes, Prg_PstNotes, isShow, isShowRA, isShowDev, lastStepPS, Prg_LastUpdate)
															VALUES ('$proj_Code', '$proj_Code_HO', '$progress_Type', '$Prg_Year', '$Prg_Month', '$Prg_Step', 
																'$Prg_Date1', '$Prg_Date2', '$Prg_Plan', '$Prg_PlanAkum', '$Prg_Real', '$Prg_RealAkum', '$Prg_Dev',
																'$Prg_ProjNotes','Prg_PstNotes','$isShow','$isShowRA','$isShowDev','$lastStepPS','$Prg_LastUpdate')";
										$this->db->query($sqlInsPRGG);

									// TOTAL ROW IMPORTED
								        $sIMP	= "tbl_projprogres WHERE proj_Code = '$PRJCODE'";
										$rIMP	= $this->db->count_all($sIMP);

									// START : SENDING PROCESS
										$modRow		= $rowCellD % 10;
										$percent 	= intval($rIMP/$baris * 100)."%";
										/*if($modRow == 0)
										{*/
											//sleep(1); // Here call your time taking function like sending bulk sms etc.
											
											echo '<script>
									    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed</span></div>";</script>';

										    ob_flush(); 
										    flush();
										//}
									// END : SENDING PROCESS
								}
							}
							$percent = intval(100)."%";    
							/*echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-succes cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';*/
							echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed completed</span></div>";</script>';

						    ob_flush(); 
						    flush();
						}
						$reader->close();
					// END : GET ALL ROW DATA AND INSERT INTO TABLE
				// END : PROSEDURE BARU

				$percent = intval(100)."%";
				echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';

			    ob_flush(); 
			    flush();

				// IMPORT CONCLUSION
					$sqlUpdPRGG		= "UPDATE tbl_progg_uphist SET PROGG_STAT = 2 WHERE PROGG_CODE = '$PROGG_CODEX'";
					$this->db->query($sqlUpdPRGG);

				// IMPORT COMPLETE INFO
					$percent = intval(100)."%";
					echo '<script>
					    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
					echo '<script>
					    parent.document.getElementById("loading_1").style.display ="none";
					    parent.document.getElementById("loading_2").style.display ="none";
					    parent.updStat();</script>';
			}
			else
			{
				//echo "Sudah diimport sebelumnya";
				//return false;
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
	else if($IMP_TYPE == 'IMP-TRX')
	{
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];
		$JRNH_CODE	= $_POST['IMP_CODEX'];
		$s_00		= "DELETE FROM tbl_journaldetail_imp WHERE JournalH_Code = '$JRNH_CODE'";
		$this->db->query($s_00);

		$s_001		= "SELECT Reference_Number, Manual_No
						FROM tbl_journalheader_imp WHERE JournalH_Code = '$JRNH_CODE'";
		$r_001 		= $this->db->query($s_001)->result();
		foreach($r_001 as $rw_001) :
			$FILE_NM 	= $rw_001->Reference_Number;
			$MAN_NO 	= $rw_001->Manual_No;
		endforeach;
		$CurrDate 		= date('Y-m-d H:i:s');

		// START : PROSEDURE BARU
			$myPath 	= APPPATH."xlsxfile/import_trx/$FILE_NM";
			$reader 	= ReaderEntityFactory::createXLSXReader($myPath);

			$reader->open($myPath);
			$rowCellH	= 0;
			$totCol 	= 0;
			$isError 	= 0;

			// CREATE ARRAY HEADER
				foreach ($reader->getSheetIterator() as $sheet)
				{
					foreach ($sheet->getRowIterator() as $row)
					{
						$rowCellH 	= $rowCellH+1;
						if($rowCellH == 1)
						{
							$arrHn[]	= array();
							for($cl=0;$cl<=100;$cl++)
							{
					        	$cells 		= $row->getCells();
					        	try 
					        	{
								  	if(isset($cells[$cl]))
								  	{
								  		$HeadCells 		= $cells[$cl];
								  		$HeadNm 		= $HeadCells->getValue() ?: '';
								  		$totCol			= $totCol+1;
								  		$arrHn[$cl]		= $HeadNm;
								  	}
								}
								catch (exceuta $e)
								{
								   	//
								}
							}
						}
						$percent 	= intval(100)."%";
						$collected 	= intval($rowCellH);
						echo '<script>
							  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-primary cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$collected.' collected</span></div>";</script>';

					    ob_flush(); 
					    flush();
					}
				}

			// SART : GET ALL ROW DATA AND INSERT INTO TABLE
		        $baris 		= $rowCellH;
		        $barisD 	= $rowCellH-1;				// DIKURANGI HEADER
				$rata2 		= $baris / 100;				// TOTAL RATA2 PER / 100 --> TOTAL LOOP 100
				$rata2a		= $rata2 * 1;				// TOTAL VALUE PER LOOP
				$rata2b		= intval($rata2 * 1);		// TOTAL LOOP = 100
				$totLoop 	= intval($baris / $rata2a);	// TOTAL LOOP = 100
				$rowCellD	= 0;
				$ORD_ID		= 0;
				$ORD_IDH 	= 0;
				$PATT_NO 	= 0;

				foreach ($reader->getSheetIterator() as $sheet)
				{
					$rowCellD	= 0;
					foreach ($sheet->getRowIterator() as $row)
					{
						$rowCellD 	= $rowCellD+1;
						if($rowCellD > 1)
						{
							$ORDID 		= $rowCellD-1;
							$ITM_CODE 	= "";
							$JOBDESC 	= "";
							for($rw=0;$rw<$totCol;$rw++)
							{
					        	$cells 		= $row->getCells();
				        		$hdName 	= $arrHn[$rw];
							  	$HeadCells 	= $cells[$rw];
							  	$RowData 	= '';
							  	if(null !== $HeadCells)
						  			$RowData 	= $HeadCells->getValue();

								if($hdName == 'TANGGAL')
									$JournalH_Date 	= $RowData->format('Y-m-d');
								elseif($hdName == 'NO_JURNAL')
									$Manual_No 		= $RowData;
								elseif($hdName == 'KODE_AKUN')
									$Acc_Id 		= $RowData;
								elseif($hdName == 'URAIAN')
									$Other_Desc 	= addslashes($RowData);
								elseif($hdName == 'DEBET')
								{
									$COA_Debet		= $RowData;
									//$COA_Debet		= preg_replace('/[^A-Za-z0-8\.]/', '', $COA_Debet1);
									//$COA_Debet	= sprintf("%f", $COA_Debet2);
									if($COA_Debet == '')
										$COA_Debet= 0;
								}
								elseif($hdName == 'KREDIT')
								{
									$COA_Kredit	= $RowData;
									//$COA_Kredit		= preg_replace('/[^A-Za-z0-8\.]/', '', $COA_Kredit1);
									//$COA_Kredit		= sprintf("%f", $COA_Kredit2);
									if($COA_Kredit == '')
										$COA_Kredit = 0;
								}
								elseif($hdName == 'PROYEK')
									$proj_Code 		= $RowData;
								elseif($hdName == 'GROUP')
									$BudgetGroup	= $RowData;
								elseif($hdName == 'USER')
									$USER 			= $RowData;
								elseif($hdName == 'KETERANGAN')
									$KETERANGAN		= $RowData;
							}

							$PATT_NO  		= $PATT_NO + 1;
							$Patt_Number 	= $PATT_NO;

							if($COA_Debet == 0)
								$Journal_DK = 'K';
							if($COA_Kredit == 0)
								$Journal_DK = 'D';

							$s_AccNmC 		= "tbl_chartaccount WHERE Account_Number = '$Acc_Id'";
							$r_AccNmC		= $this->db->count_all($s_AccNmC);

							$proj_CodeHO 	= "";
							$Acc_NExist 	= "Y";
							$Acc_NExistD 	= "";
							$Acc_Name 		= "-";

							$sqlISHO		= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$proj_Code'";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowisho) :
								$proj_CodeHO = $rowisho->PRJCODE_HO;
							endforeach;

							if($r_AccNmC == 0)
							{
								$Acc_NExist 	= "N";
								$Acc_NExistD 	= "Kode Akun tidak ditemukan.";
								$Acc_Name 		= "";
							}
							else
							{
								$s_AccNm 		= "SELECT PRJCODE_HO, Account_NameId AS Acc_Name FROM tbl_chartaccount
													WHERE Account_Number = '$Acc_Id' LIMIT 1";
								$r_AccNm		= $this->db->query($s_AccNm)->result();
								foreach($r_AccNm as $rw_AccNm):
									$proj_CodeHO= $rw_AccNm->PRJCODE_HO;
									$Acc_Name	= $rw_AccNm->Acc_Name;
								endforeach;
							}

							/*$insSQL	= "INSERT INTO tbl_journaldetail_imp (JournalH_Code, JournalH_Date, JournalType,
											Acc_Id, Acc_Name, proj_Code, proj_CodeHO, PRJPERIOD, Currency_id,
											JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit, COA_Debet, COA_Kredit,
											curr_rate, isDirect, Manual_No, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											GEJ_STAT, ISPERSL, LastUpdate, Acc_Id_Cross, oth_reason)
										VALUES
											('$JRNH_CODE', '$JournalH_Date', 'IMP-TRX',
											'$Acc_Id', '$Acc_Name', '$proj_Code', '$proj_CodeHO', '$proj_Code', 'IDR',
											'$COA_Debet', '$COA_Kredit', '$COA_Debet', '$COA_Kredit', '$COA_Debet', '$COA_Kredit',
											1, 1, '$Manual_No', '$MAN_NO', '$Other_Desc', '$Journal_DK', '$BudgetGroup',
											3, 0, '$CurrDate', '$Acc_NExist', '$Acc_NExistD')";*/

							$insSQL	= "INSERT INTO tbl_journaldetail_imp (JournalH_Code, JournalH_Date, JournalType,
											Acc_Id, Acc_Name, proj_Code, proj_CodeHO, PRJPERIOD, Currency_id,
											JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit, COA_Debet, COA_Kredit,
											curr_rate, isDirect, Manual_No, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											GEJ_STAT, ISPERSL, LastUpdate, Acc_Id_Cross, oth_reason)
										VALUES
											('$JRNH_CODE', '$JournalH_Date', 'IMP-TRX',
											'$Acc_Id', '$Acc_Name', '$proj_Code', '$proj_CodeHO', '$proj_Code', 'IDR',
											'$COA_Debet', '$COA_Kredit', '$COA_Debet', '$COA_Kredit', '$COA_Debet', '$COA_Kredit',
											1, 1, '$Manual_No', '$MAN_NO', '$Other_Desc', '$Journal_DK', '$BudgetGroup',
											3, 0, '$CurrDate', '$Acc_NExist', '$KETERANGAN')";
							$this->db->query($insSQL);

							// TOTAL ROW IMPORTED
						        $sIMP	= "tbl_journaldetail_imp WHERE JournalH_Code = '$JRNH_CODE'";
								$rIMP	= $this->db->count_all($sIMP);

							// START : SENDING PROCESS
								$percent 	= intval($rIMP/$baris * 100)."%";
									
								echo '<script>
							    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed</span></div>";</script>';

							    ob_flush(); 
							    flush(); 
								//}
							// END : SENDING PROCESS
						}
					}
					$percent = intval(100)."%";    
					/*echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-succes cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';*/
					echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$baris.') processed completed</span></div>";</script>';

				    ob_flush(); 
				    flush();
				}
				$reader->close();
			// END : GET ALL ROW DATA AND INSERT INTO TABLE
		// END : PROSEDURE BARU

		// COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
			    parent.document.getElementById("loading_2").style.display ="none";
				    parent.updStat();</script>';
	}
	else if($IMP_TYPE == 'IMP-TRX2')
	{
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];
		$rIMP 		= 0;

		$JRNH_CODE	= $_POST['IMP_CODEX'];
		$s_00		= "tbl_journaldetail_imp WHERE JournalH_Code = '$JRNH_CODE'";
		$r_00 		= $this->db->count_all($s_00);

		$s_02		= "SELECT Reference_Number, Manual_No
						FROM tbl_journalheader_imp WHERE JournalH_Code = '$JRNH_CODE' AND proj_Code = '$PRJCODE'";
		$r_02 		= $this->db->query($s_02)->result();
		foreach($r_02 as $rw_02) :
			$FILE_NM 	= $rw_02->Reference_Number;
			$MAN_NO 	= $rw_02->Manual_No;
		endforeach;
		$CurrDate 		= date('Y-m-d H:i:s');

		// SART : GET ALL ROW DATA AND INSERT INTO TABLE
			$s_02		= "SELECT * FROM tbl_journaldetail_imp WHERE JournalH_Code = '$JRNH_CODE' aa";
			$r_02 		= $this->db->query($s_02)->result();
			foreach($r_02 as $rw_02) :
				$JournalH_Date 	= $rw_02->JournalH_Date;
				$accYr 			= date('Y', strtotime($JournalH_Date));
				$JournalType 	= $rw_02->JournalType;
				$Acc_Id 		= $rw_02->Acc_Id;
				$Acc_Name 		= $rw_02->Acc_Name;
				$proj_Code 		= $rw_02->proj_Code;
				$proj_CodeHO 	= $rw_02->proj_CodeHO;
				$PRJPERIOD 		= $rw_02->PRJPERIOD;
				$Currency_id 	= $rw_02->Currency_id;
				$COA_Debet 		= $rw_02->COA_Debet;
				$COA_Kredit 	= $rw_02->COA_Kredit;
				$curr_rate 		= $rw_02->curr_rate;
				$Manual_No 		= $rw_02->Manual_No;
				$Ref_Number 	= $rw_02->Ref_Number;
				$Other_Desc 	= $rw_02->Other_Desc;
				$Journal_DK 	= $rw_02->Journal_DK;
				$Journal_Type 	= $rw_02->Journal_Type;
				$GEJ_STAT 		= $rw_02->GEJ_STAT;
				$ISPERSL 		= $rw_02->ISPERSL;
				$oth_reason		= $rw_02->oth_reason;
				$LastUpdate 	= date('Y-m-d H:i:s');

				$insSQL	= "INSERT INTO tbl_journaldetail_imp (JournalH_Code, JournalH_Date, JournalType,
								Acc_Id, Acc_Name, proj_Code, proj_CodeHO, PRJPERIOD, Currency_id,
								JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit, COA_Debet, COA_Kredit,
								curr_rate, isDirect, Manual_No, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
								GEJ_STAT, ISPERSL, LastUpdate, oth_reason)
							VALUES
								('$JRNH_CODE', '$JournalH_Date', '$JournalType',
								'$Acc_Id', '$Acc_Name', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', '$Currency_id',
								'$COA_Debet', '$COA_Kredit', '$COA_Debet', '$COA_Kredit', '$COA_Debet', '$COA_Kredit',
								'$curr_rate', 1, '$Manual_No', '$Ref_Number', '$Other_Desc', '$Journal_DK', '$Journal_Type',
								'$GEJ_STAT', '$ISPERSL', '$LastUpdate', '$oth_reason')";
				$this->db->query($insSQL);
	                    
                $syncPRJ	= '';
                $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Id' LIMIT 1";
                $resISHO	= $this->db->query($sqlISHO)->result();
                foreach($resISHO as $rowISHO):
                    $syncPRJ= $rowISHO->syncPRJ;
                endforeach;
                $dataPecah 	= explode("~",$syncPRJ);
                $jmD 		= count($dataPecah);
            
                if($jmD > 0)
				{
					$SYNC_PRJ	= '';
					for($i=0; $i < $jmD; $i++)
					{
						$SYNC_PRJ	= $dataPecah[$i];

						if($Journal_DK == 'D')
						{
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$COA_Debet, 
												Base_Debet2 = Base_Debet2+$COA_Debet, BaseD_$accYr = BaseD_$accYr+$COA_Debet
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
						}
						else
						{
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$COA_Kredit, 
												Base_Kredit2 = Base_Kredit2+$COA_Kredit, BaseK_$accYr = BaseK_$accYr+$COA_Kredit
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
						}
						$this->db->query($sqlUpdCOA);
					}
				}

				// TOTAL ROW IMPORTED
			        $sIMP	= "tbl_journaldetail WHERE JournalH_Code = '$JRNH_CODE'";
					$rIMP	= $this->db->count_all($sIMP);

				// START : SENDING PROCESS
					$percent 	= intval($rIMP/$r_00 * 100)."%";
						
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rIMP. ' / '.$r_00.') processed</span></div>";</script>';

				    ob_flush(); 
				    flush(); 
					//}
				// END : SENDING PROCESS
			endforeach;
			$reader->close();
		// END : GET ALL ROW DATA AND INSERT INTO TABLE


		// COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			/*echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';*/
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
			    parent.document.getElementById("loading_2").style.display ="none";
				    parent.updStat();</script>';
	}
	else if($IMP_TYPE == 'RESETJLH_220524')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');
		$JOBCODEID	= $_POST['DESCRIPT'];

		// START : RESET HEDAER
			/*$sql1	= "UPDATE tbl_boqlist SET JOBCOST = 0, BOQ_JOBCOST = 0 WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql1);
			$sql1	= "UPDATE tbl_joblist SET JOBCOST = 0, BOQ_JOBCOST = 0 WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql1);
			$sql1	= "UPDATE tbl_joblist_detail SET ITM_BUDG = 0, BOQ_JOBCOST = 0 WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql1);


			$sql2	= "UPDATE tbl_boqlist SET JOBCOST = 0, BOQ_JOBCOST = 0 WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLAST = 0 AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql2);
			$sql2	= "UPDATE tbl_joblist SET JOBCOST = 0, BOQ_JOBCOST = 0 WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLAST = 0 AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql2);
			$sql2	= "UPDATE tbl_joblist_detail SET ITM_BUDG = 0, BOQ_JOBCOST = 0 WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLAST = 0 AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql2);*/

			$sql3a	= "UPDATE tbl_boqlist SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql3a);
			$sql3b	= "UPDATE tbl_joblist SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql3b);
			$sql3c	= "UPDATE tbl_joblist_detail SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql3c);
			//echo "$sql3a<br>$sql3b<br>$sql3c";
			//return false;
		// END : RESET HEDAER

			$percent = intval(0)."%";
			echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

		    ob_flush();
		    flush();

	    // TOTAL BARIS LAST HEADER
			$sqlJLT		= "tbl_joblist_detail WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
			$resJLT		= $this->db->count_all($sqlJLT);

		// START : PROCEDUR - RESET ORDER
			if($resJLT == 0)
			{
				$TOT_RAP = 0;
				$TOT_RAB = 0;
				$sql_01	= "SELECT (IFNULL(SUM(ITM_BUDG), 0)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), 0)) AS TOT_RAB
							FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				$res_01 = $this->db->query($sql_01)->result();
				foreach($res_01 as $row_01) :
					$TOT_RAP	= $row_01->TOT_RAP;
					$TOT_RAB	= $row_01->TOT_RAB;

					$sql_01A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP, BOQ_JOBCOST = $TOT_RAB WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sql_01A);
					$sql_01A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP, BOQ_JOBCOST = $TOT_RAB WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sql_01A);
					$sql_01A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP, BOQ_JOBCOST = $TOT_RAB WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sql_01A);
				endforeach;
			}
			else
			{
				$tRow 	= 0;
				$sql_01	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
				$res_01 = $this->db->query($sql_01)->result();
				foreach($res_01 as $row_01) :
					$tRow 		= $tRow+1;
					$JOBD_01	= $row_01->JOBCODEID;
					$JOBH_01	= $row_01->JOBPARENT;
					$BOQV_01	= $row_01->BOQVAL;
					$BUDV_01	= $row_01->BUDVAL;
					$TOT_RAP_01 = 0;
					$TOT_RAB_01 = 0;
					$sql_01		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_01)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_01)) AS TOT_RAB
									FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
					$res_01 	= $this->db->query($sql_01)->result();
					foreach($res_01 as $row_01) :
						$TOT_RAP_01	= $row_01->TOT_RAP;
						$TOT_RAB_01	= $row_01->TOT_RAB;

						$sql_01A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_01, BOQ_JOBCOST = $TOT_RAB_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql_01A);
						$sql_01A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_01, BOQ_JOBCOST = $TOT_RAB_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql_01A);
						$sql_01A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_01, BOQ_JOBCOST = $TOT_RAB_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql_01A);
						//echo "$sql_01<br>$sql_01A<br>";

						$sqlJLH_02		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_01' AND PRJCODE = '$PRJCODE'";
						$resJLH_02		= $this->db->count_all($sqlJLH_02);
						if($resJLH_02 > 0)
						{
							$sql_02	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_01' AND PRJCODE = '$PRJCODE'";
							$res_02 = $this->db->query($sql_02)->result();
							foreach($res_02 as $row_02) :
								$JOBD_02	= $row_02->JOBCODEID;
								$JOBH_02	= $row_02->JOBPARENT;
								$BOQV_02	= $row_02->BOQVAL;
								$BUDV_02	= $row_02->BUDVAL;
								$TOT_RAP_02 = 0;
								$TOT_RAB_02 = 0;
								$sql_02		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_02)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_02)) AS TOT_RAB
												FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
								$res_02 	= $this->db->query($sql_02)->result();
								foreach($res_02 as $row_02) :
									$TOT_RAP_02	= $row_02->TOT_RAP;
									$TOT_RAB_02	= $row_02->TOT_RAB;

									$sql_02A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_02, BOQ_JOBCOST = $TOT_RAB_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
									$this->db->query($sql_02A);
									$sql_02A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_02, BOQ_JOBCOST = $TOT_RAB_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
									$this->db->query($sql_02A);
									$sql_02A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_02, BOQ_JOBCOST = $TOT_RAB_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
									$this->db->query($sql_02A);


									$sqlJLH_03		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_02' AND PRJCODE = '$PRJCODE'";
									$resJLH_03		= $this->db->count_all($sqlJLH_03);
									if($resJLH_03 > 0)
									{
										$sql_03	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_02' AND PRJCODE = '$PRJCODE'";
										$res_03 = $this->db->query($sql_03)->result();
										foreach($res_03 as $row_03) :
											$JOBD_03	= $row_03->JOBCODEID;
											$JOBH_03	= $row_03->JOBPARENT;
											$BOQV_03	= $row_03->BOQVAL;
											$BUDV_03	= $row_03->BUDVAL;
											$TOT_RAP_03 = 0;
											$TOT_RAB_03 = 0;
											$sql_03		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_03)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_03)) AS TOT_RAB
															FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
											$res_03 	= $this->db->query($sql_03)->result();
											foreach($res_03 as $row_03) :
												$TOT_RAP_03	= $row_03->TOT_RAP;
												$TOT_RAB_03	= $row_03->TOT_RAB;

												$sql_03A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_03, BOQ_JOBCOST = $TOT_RAB_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
												$this->db->query($sql_03A);
												$sql_03A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_03, BOQ_JOBCOST = $TOT_RAB_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
												$this->db->query($sql_03A);
												$sql_03A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_03, BOQ_JOBCOST = $TOT_RAB_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
												$this->db->query($sql_03A);

												$sqlJLH_04		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_03' AND PRJCODE = '$PRJCODE'";
												$resJLH_04		= $this->db->count_all($sqlJLH_04);
												if($resJLH_04 > 0)
												{
													$sql_04	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_03' AND PRJCODE = '$PRJCODE'";
													$res_04 = $this->db->query($sql_04)->result();
													foreach($res_04 as $row_04) :
														$JOBD_04	= $row_04->JOBCODEID;
														$JOBH_04	= $row_04->JOBPARENT;
														$BOQV_04	= $row_04->BOQVAL;
														$BUDV_04	= $row_04->BUDVAL;
														$TOT_RAP_04 = 0;
														$TOT_RAB_04 = 0;
														$sql_04		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_04)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_04)) AS TOT_RAB
																		FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
														$res_04 	= $this->db->query($sql_04)->result();
														foreach($res_04 as $row_04) :
															$TOT_RAP_04	= $row_04->TOT_RAP;
															$TOT_RAB_04	= $row_04->TOT_RAB;

															$sql_04A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_04, BOQ_JOBCOST = $TOT_RAB_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
															$this->db->query($sql_04A);
															$sql_04A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_04, BOQ_JOBCOST = $TOT_RAB_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
															$this->db->query($sql_04A);
															$sql_04A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_04, BOQ_JOBCOST = $TOT_RAB_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
															$this->db->query($sql_04A);

															$sqlJLH_05		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_04' AND PRJCODE = '$PRJCODE'";
															$resJLH_05		= $this->db->count_all($sqlJLH_05);
															if($resJLH_05 > 0)
															{
																$sql_05	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_04' AND PRJCODE = '$PRJCODE'";
																$res_05 = $this->db->query($sql_05)->result();
																foreach($res_05 as $row_05) :
																	$JOBD_05	= $row_05->JOBCODEID;
																	$JOBH_05	= $row_05->JOBPARENT;
																	$BOQV_05	= $row_05->BOQVAL;
																	$BUDV_05	= $row_05->BUDVAL;
																	$TOT_RAP_05 = 0;
																	$TOT_RAB_05 = 0;
																	$sql_05		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_05)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_05)) AS TOT_RAB
																					FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																	$res_05 	= $this->db->query($sql_05)->result();
																	foreach($res_05 as $row_05) :
																		$TOT_RAP_05	= $row_05->TOT_RAP;
																		$TOT_RAB_05	= $row_05->TOT_RAB;

																		$sql_05A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_05, BOQ_JOBCOST = $TOT_RAB_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																		$this->db->query($sql_05A);
																		$sql_05A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_05, BOQ_JOBCOST = $TOT_RAB_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																		$this->db->query($sql_05A);
																		$sql_05A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_05, BOQ_JOBCOST = $TOT_RAB_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																		$this->db->query($sql_05A);

																		$sqlJLH_06		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_05' AND PRJCODE = '$PRJCODE'";
																		$resJLH_06		= $this->db->count_all($sqlJLH_06);
																		if($resJLH_06 > 0)
																		{
																			$sql_06	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_05' AND PRJCODE = '$PRJCODE'";
																			$res_06 = $this->db->query($sql_06)->result();
																			foreach($res_06 as $row_06) :
																				$JOBD_06	= $row_06->JOBCODEID;
																				$JOBH_06	= $row_06->JOBPARENT;
																				$BOQV_06	= $row_06->BOQVAL;
																				$BUDV_06	= $row_06->BUDVAL;
																				$TOT_RAP_06 = 0;
																				$TOT_RAB_06 = 0;
																				$sql_06		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_06)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_06)) AS TOT_RAB
																								FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																				$res_06 	= $this->db->query($sql_06)->result();
																				foreach($res_06 as $row_06) :
																					$TOT_RAP_06	= $row_06->TOT_RAP;
																					$TOT_RAB_06	= $row_06->TOT_RAB;

																					$sql_06A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_06, BOQ_JOBCOST = $TOT_RAB_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																					$this->db->query($sql_06A);
																					$sql_06A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_06, BOQ_JOBCOST = $TOT_RAB_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																					$this->db->query($sql_06A);
																					$sql_06A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_06, BOQ_JOBCOST = $TOT_RAB_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																					$this->db->query($sql_06A);

																					$sqlJLH_07		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_06' AND PRJCODE = '$PRJCODE'";
																					$resJLH_07		= $this->db->count_all($sqlJLH_07);
																					if($resJLH_07 > 0)
																					{
																						$sql_07	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_06' AND PRJCODE = '$PRJCODE'";
																						$res_07 = $this->db->query($sql_07)->result();
																						foreach($res_07 as $row_07) :
																							$JOBD_07	= $row_07->JOBCODEID;
																							$JOBH_07	= $row_07->JOBPARENT;
																							$BOQV_07	= $row_07->BOQVAL;
																							$BUDV_07	= $row_07->BUDVAL;
																							$TOT_RAP_07 = 0;
																							$TOT_RAB_07 = 0;
																							$sql_07		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_07)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_07)) AS TOT_RAB
																											FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																							$res_07 	= $this->db->query($sql_07)->result();
																							foreach($res_07 as $row_07) :
																								$TOT_RAP_07	= $row_07->TOT_RAP;
																								$TOT_RAB_07	= $row_07->TOT_RAB;

																								$sql_07A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_07, BOQ_JOBCOST = $TOT_RAB_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																								$this->db->query($sql_07A);
																								$sql_07A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_07, BOQ_JOBCOST = $TOT_RAB_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																								$this->db->query($sql_07A);
																								$sql_07A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_07, BOQ_JOBCOST = $TOT_RAB_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																								$this->db->query($sql_07A);

																								$sqlJLH_08		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_07' AND PRJCODE = '$PRJCODE'";
																								$resJLH_08		= $this->db->count_all($sqlJLH_08);
																								if($resJLH_08 > 0)
																								{
																									$sql_08	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_07' AND PRJCODE = '$PRJCODE'";
																									$res_08 = $this->db->query($sql_08)->result();
																									foreach($res_08 as $row_08) :
																										$JOBD_08	= $row_08->JOBCODEID;
																										$JOBH_08	= $row_08->JOBPARENT;
																										$BOQV_08	= $row_08->BOQVAL;
																										$BUDV_08	= $row_08->BUDVAL;
																										$TOT_RAP_08 = 0;
																										$TOT_RAB_08 = 0;
																										$sql_08		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_08)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_08)) AS TOT_RAB
																														FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																										$res_08 	= $this->db->query($sql_08)->result();
																										foreach($res_08 as $row_08) :
																											$TOT_RAP_08	= $row_08->TOT_RAP;
																											$TOT_RAB_08	= $row_08->TOT_RAB;

																											$sql_08A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_08, BOQ_JOBCOST = $TOT_RAB_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																											$this->db->query($sql_08A);
																											$sql_08A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_08, BOQ_JOBCOST = $TOT_RAB_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																											$this->db->query($sql_08A);
																											$sql_08A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_08, BOQ_JOBCOST = $TOT_RAB_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																											$this->db->query($sql_08A);

																											$sqlJLH_09		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_08' AND PRJCODE = '$PRJCODE'";
																											$resJLH_09		= $this->db->count_all($sqlJLH_09);
																											if($resJLH_09 > 0)
																											{
																												$sql_09	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_08' AND PRJCODE = '$PRJCODE'";
																												$res_09 = $this->db->query($sql_09)->result();
																												foreach($res_09 as $row_09) :
																													$JOBD_09	= $row_09->JOBCODEID;
																													$JOBH_09	= $row_09->JOBPARENT;
																													$BOQV_09	= $row_09->BOQVAL;
																													$BUDV_09	= $row_09->BUDVAL;
																													$TOT_RAP_09 = 0;
																													$TOT_RAB_09 = 0;
																													$sql_09		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_09)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_09)) AS TOT_RAB
																																	FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																													$res_09 	= $this->db->query($sql_09)->result();
																													foreach($res_09 as $row_09) :
																														$TOT_RAP_09	= $row_09->TOT_RAP;
																														$TOT_RAB_09	= $row_09->TOT_RAB;

																														$sql_09A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_09, BOQ_JOBCOST = $TOT_RAB_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																														$this->db->query($sql_09A);
																														$sql_09A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_09, BOQ_JOBCOST = $TOT_RAB_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																														$this->db->query($sql_09A);
																														$sql_09A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_09, BOQ_JOBCOST = $TOT_RAB_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																														$this->db->query($sql_09A);

																														$sqlJLH_10		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_09' AND PRJCODE = '$PRJCODE'";
																														$resJLH_10		= $this->db->count_all($sqlJLH_10);
																														if($resJLH_10 > 0)
																														{
																															$sql_10	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_09' AND PRJCODE = '$PRJCODE'";
																															$res_10 = $this->db->query($sql_10)->result();
																															foreach($res_10 as $row_10) :
																																$JOBD_10	= $row_10->JOBCODEID;
																																$JOBH_10	= $row_10->JOBPARENT;
																																$BOQV_10	= $row_10->BOQVAL;
																																$BUDV_10	= $row_10->BUDVAL;
																																$TOT_RAP_10 = 0;
																																$TOT_RAB_10 = 0;
																																$sql_10		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_10)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_10)) AS TOT_RAB
																																				FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																$res_10 	= $this->db->query($sql_10)->result();
																																foreach($res_10 as $row_10) :
																																	$TOT_RAP_10	= $row_10->TOT_RAP;
																																	$TOT_RAB_10	= $row_10->TOT_RAB;

																																	$sql_10A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_10, BOQ_JOBCOST = $TOT_RAB_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																	$this->db->query($sql_10A);
																																	$sql_10A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_10, BOQ_JOBCOST = $TOT_RAB_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																	$this->db->query($sql_10A);
																																	$sql_10A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_10, BOQ_JOBCOST = $TOT_RAB_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																	$this->db->query($sql_10A);
																																endforeach;
																															endforeach;
																														}
																													endforeach;
																												endforeach;
																											}
																										endforeach;
																									endforeach;
																								}
																							endforeach;
																						endforeach;
																					}
																				endforeach;
																			endforeach;
																		}
																	endforeach;
																endforeach;
															}
														endforeach;
													endforeach;
												}
											endforeach;
										endforeach;
									}
								endforeach;
							endforeach;
						}

					    // START : BAR PERCENTATION PROGRESSED
							$sqlJLTP	= "tbl_joblist_detail WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
							$resJLTP	= $this->db->count_all($sqlJLTP);

							$percent 	= intval($resJLTP/$resJLT * 100)."%";
							echo '<script>
									    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resJLTP. ' / '.$resJLT.') processed</span></div>";</script>';

										    ob_flush(); 
										    flush();
					    // END : BAR PERCENTATION PROGRESSED
					endforeach;
				endforeach;
			}

			// 3.	FINISHING INFO
				/*$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Finishing ...</span></div>";</script>';*/

			// 2.	UPDATE IS HEADER OR IS LAST
				/*$sql_ISHD	= "UPDATE tbl_joblist_detail SET ISLAST = 0 WHERE PRJCODE = '$SYNC_PRJ'
								AND JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ')";
				$this->db->query($sql_ISHD);

				$sql_ISD	= "UPDATE tbl_joblist_detail SET ISLAST = 1 WHERE PRJCODE = '$SYNC_PRJ'
								AND JOBCODEID NOT IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ')";
				$this->db->query($sql_ISD);*/

			// 3.	COMPLETE INFO
				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
					    parent.clsBarX();
					    parent.updStat();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'RESETJLH')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');
		$JOBCODEID	= $_POST['DESCRIPT'];

		// START : RESET HEDAER
			/*$sql1	= "UPDATE tbl_boqlist SET JOBCOST = 0, BOQ_JOBCOST = 0 WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql1);
			$sql1	= "UPDATE tbl_joblist SET JOBCOST = 0, BOQ_JOBCOST = 0 WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql1);
			$sql1	= "UPDATE tbl_joblist_detail SET ITM_BUDG = 0, BOQ_JOBCOST = 0 WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql1);


			$sql2	= "UPDATE tbl_boqlist SET JOBCOST = 0, BOQ_JOBCOST = 0 WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLAST = 0 AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql2);
			$sql2	= "UPDATE tbl_joblist SET JOBCOST = 0, BOQ_JOBCOST = 0 WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLAST = 0 AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql2);
			$sql2	= "UPDATE tbl_joblist_detail SET ITM_BUDG = 0, BOQ_JOBCOST = 0 WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLAST = 0 AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql2);*/

			$sql3a	= "UPDATE tbl_boqlist SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql3a);
			$sql3b	= "UPDATE tbl_joblist SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql3b);
			$sql3c	= "UPDATE tbl_joblist_detail SET BOQ_JOBCOST = BOQ_VOLM*BOQ_PRICE WHERE JOBPARENT LIKE '$JOBCODEID%' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql3c);
			//echo "$sql3a<br>$sql3b<br>$sql3c";
			//return false;
		// END : RESET HEDAER

			$percent = intval(0)."%";
			echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

		    ob_flush();
		    flush();

	    // TOTAL BARIS LAST HEADER
			$sqlJLT		= "tbl_joblist_detail WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
			$resJLT		= $this->db->count_all($sqlJLT);

		// START : PROCEDUR - RESET ORDER
			if($resJLT == 0)
			{
				$TOT_RAP = 0;
				$TOT_RAB = 0;
				$sql_01	= "SELECT (IFNULL(SUM(ITM_BUDG), 0)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), 0)) AS TOT_RAB
							FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				$res_01 = $this->db->query($sql_01)->result();
				foreach($res_01 as $row_01) :
					$TOT_RAP	= $row_01->TOT_RAP;
					$TOT_RAB	= $row_01->TOT_RAB;

					$sql_01A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sql_01A);
					$sql_01A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sql_01A);
					$sql_01A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sql_01A);
				endforeach;
			}
			else
			{
				$tRow 	= 0;
				$sql_01	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
				$res_01 = $this->db->query($sql_01)->result();
				foreach($res_01 as $row_01) :
					$tRow 		= $tRow+1;
					$JOBD_01	= $row_01->JOBCODEID;
					$JOBH_01	= $row_01->JOBPARENT;
					$BOQV_01	= $row_01->BOQVAL;
					$BUDV_01	= $row_01->BUDVAL;
					$TOT_RAP_01 = 0;
					$TOT_RAB_01 = 0;
					$sql_01		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_01)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_01)) AS TOT_RAB
									FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
					$res_01 	= $this->db->query($sql_01)->result();
					foreach($res_01 as $row_01) :
						$TOT_RAP_01	= $row_01->TOT_RAP;
						$TOT_RAB_01	= $row_01->TOT_RAB;

						$sql_01A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql_01A);
						$sql_01A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql_01A);
						$sql_01A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_01 WHERE JOBCODEID = '$JOBD_01' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sql_01A);
						//echo "$sql_01<br>$sql_01A<br>";

						$sqlJLH_02		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_01' AND PRJCODE = '$PRJCODE'";
						$resJLH_02		= $this->db->count_all($sqlJLH_02);
						if($resJLH_02 > 0)
						{
							$sql_02	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_01' AND PRJCODE = '$PRJCODE'";
							$res_02 = $this->db->query($sql_02)->result();
							foreach($res_02 as $row_02) :
								$JOBD_02	= $row_02->JOBCODEID;
								$JOBH_02	= $row_02->JOBPARENT;
								$BOQV_02	= $row_02->BOQVAL;
								$BUDV_02	= $row_02->BUDVAL;
								$TOT_RAP_02 = 0;
								$TOT_RAB_02 = 0;
								$sql_02		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_02)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_02)) AS TOT_RAB
												FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
								$res_02 	= $this->db->query($sql_02)->result();
								foreach($res_02 as $row_02) :
									$TOT_RAP_02	= $row_02->TOT_RAP;
									$TOT_RAB_02	= $row_02->TOT_RAB;

									$sql_02A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
									$this->db->query($sql_02A);
									$sql_02A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
									$this->db->query($sql_02A);
									$sql_02A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_02 WHERE JOBCODEID = '$JOBD_02' AND PRJCODE = '$PRJCODE'";
									$this->db->query($sql_02A);


									$sqlJLH_03		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_02' AND PRJCODE = '$PRJCODE'";
									$resJLH_03		= $this->db->count_all($sqlJLH_03);
									if($resJLH_03 > 0)
									{
										$sql_03	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_02' AND PRJCODE = '$PRJCODE'";
										$res_03 = $this->db->query($sql_03)->result();
										foreach($res_03 as $row_03) :
											$JOBD_03	= $row_03->JOBCODEID;
											$JOBH_03	= $row_03->JOBPARENT;
											$BOQV_03	= $row_03->BOQVAL;
											$BUDV_03	= $row_03->BUDVAL;
											$TOT_RAP_03 = 0;
											$TOT_RAB_03 = 0;
											$sql_03		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_03)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_03)) AS TOT_RAB
															FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
											$res_03 	= $this->db->query($sql_03)->result();
											foreach($res_03 as $row_03) :
												$TOT_RAP_03	= $row_03->TOT_RAP;
												$TOT_RAB_03	= $row_03->TOT_RAB;

												$sql_03A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
												$this->db->query($sql_03A);
												$sql_03A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
												$this->db->query($sql_03A);
												$sql_03A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_03 WHERE JOBCODEID = '$JOBD_03' AND PRJCODE = '$PRJCODE'";
												$this->db->query($sql_03A);

												$sqlJLH_04		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_03' AND PRJCODE = '$PRJCODE'";
												$resJLH_04		= $this->db->count_all($sqlJLH_04);
												if($resJLH_04 > 0)
												{
													$sql_04	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_03' AND PRJCODE = '$PRJCODE'";
													$res_04 = $this->db->query($sql_04)->result();
													foreach($res_04 as $row_04) :
														$JOBD_04	= $row_04->JOBCODEID;
														$JOBH_04	= $row_04->JOBPARENT;
														$BOQV_04	= $row_04->BOQVAL;
														$BUDV_04	= $row_04->BUDVAL;
														$TOT_RAP_04 = 0;
														$TOT_RAB_04 = 0;
														$sql_04		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_04)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_04)) AS TOT_RAB
																		FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
														$res_04 	= $this->db->query($sql_04)->result();
														foreach($res_04 as $row_04) :
															$TOT_RAP_04	= $row_04->TOT_RAP;
															$TOT_RAB_04	= $row_04->TOT_RAB;

															$sql_04A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
															$this->db->query($sql_04A);
															$sql_04A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
															$this->db->query($sql_04A);
															$sql_04A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_04 WHERE JOBCODEID = '$JOBD_04' AND PRJCODE = '$PRJCODE'";
															$this->db->query($sql_04A);

															$sqlJLH_05		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_04' AND PRJCODE = '$PRJCODE'";
															$resJLH_05		= $this->db->count_all($sqlJLH_05);
															if($resJLH_05 > 0)
															{
																$sql_05	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_04' AND PRJCODE = '$PRJCODE'";
																$res_05 = $this->db->query($sql_05)->result();
																foreach($res_05 as $row_05) :
																	$JOBD_05	= $row_05->JOBCODEID;
																	$JOBH_05	= $row_05->JOBPARENT;
																	$BOQV_05	= $row_05->BOQVAL;
																	$BUDV_05	= $row_05->BUDVAL;
																	$TOT_RAP_05 = 0;
																	$TOT_RAB_05 = 0;
																	$sql_05		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_05)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_05)) AS TOT_RAB
																					FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																	$res_05 	= $this->db->query($sql_05)->result();
																	foreach($res_05 as $row_05) :
																		$TOT_RAP_05	= $row_05->TOT_RAP;
																		$TOT_RAB_05	= $row_05->TOT_RAB;

																		$sql_05A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																		$this->db->query($sql_05A);
																		$sql_05A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																		$this->db->query($sql_05A);
																		$sql_05A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_05 WHERE JOBCODEID = '$JOBD_05' AND PRJCODE = '$PRJCODE'";
																		$this->db->query($sql_05A);

																		$sqlJLH_06		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_05' AND PRJCODE = '$PRJCODE'";
																		$resJLH_06		= $this->db->count_all($sqlJLH_06);
																		if($resJLH_06 > 0)
																		{
																			$sql_06	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_05' AND PRJCODE = '$PRJCODE'";
																			$res_06 = $this->db->query($sql_06)->result();
																			foreach($res_06 as $row_06) :
																				$JOBD_06	= $row_06->JOBCODEID;
																				$JOBH_06	= $row_06->JOBPARENT;
																				$BOQV_06	= $row_06->BOQVAL;
																				$BUDV_06	= $row_06->BUDVAL;
																				$TOT_RAP_06 = 0;
																				$TOT_RAB_06 = 0;
																				$sql_06		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_06)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_06)) AS TOT_RAB
																								FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																				$res_06 	= $this->db->query($sql_06)->result();
																				foreach($res_06 as $row_06) :
																					$TOT_RAP_06	= $row_06->TOT_RAP;
																					$TOT_RAB_06	= $row_06->TOT_RAB;

																					$sql_06A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																					$this->db->query($sql_06A);
																					$sql_06A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																					$this->db->query($sql_06A);
																					$sql_06A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_06 WHERE JOBCODEID = '$JOBD_06' AND PRJCODE = '$PRJCODE'";
																					$this->db->query($sql_06A);

																					$sqlJLH_07		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_06' AND PRJCODE = '$PRJCODE'";
																					$resJLH_07		= $this->db->count_all($sqlJLH_07);
																					if($resJLH_07 > 0)
																					{
																						$sql_07	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_06' AND PRJCODE = '$PRJCODE'";
																						$res_07 = $this->db->query($sql_07)->result();
																						foreach($res_07 as $row_07) :
																							$JOBD_07	= $row_07->JOBCODEID;
																							$JOBH_07	= $row_07->JOBPARENT;
																							$BOQV_07	= $row_07->BOQVAL;
																							$BUDV_07	= $row_07->BUDVAL;
																							$TOT_RAP_07 = 0;
																							$TOT_RAB_07 = 0;
																							$sql_07		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_07)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_07)) AS TOT_RAB
																											FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																							$res_07 	= $this->db->query($sql_07)->result();
																							foreach($res_07 as $row_07) :
																								$TOT_RAP_07	= $row_07->TOT_RAP;
																								$TOT_RAB_07	= $row_07->TOT_RAB;

																								$sql_07A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																								$this->db->query($sql_07A);
																								$sql_07A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																								$this->db->query($sql_07A);
																								$sql_07A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_07 WHERE JOBCODEID = '$JOBD_07' AND PRJCODE = '$PRJCODE'";
																								$this->db->query($sql_07A);

																								$sqlJLH_08		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_07' AND PRJCODE = '$PRJCODE'";
																								$resJLH_08		= $this->db->count_all($sqlJLH_08);
																								if($resJLH_08 > 0)
																								{
																									$sql_08	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_07' AND PRJCODE = '$PRJCODE'";
																									$res_08 = $this->db->query($sql_08)->result();
																									foreach($res_08 as $row_08) :
																										$JOBD_08	= $row_08->JOBCODEID;
																										$JOBH_08	= $row_08->JOBPARENT;
																										$BOQV_08	= $row_08->BOQVAL;
																										$BUDV_08	= $row_08->BUDVAL;
																										$TOT_RAP_08 = 0;
																										$TOT_RAB_08 = 0;
																										$sql_08		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_08)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_08)) AS TOT_RAB
																														FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																										$res_08 	= $this->db->query($sql_08)->result();
																										foreach($res_08 as $row_08) :
																											$TOT_RAP_08	= $row_08->TOT_RAP;
																											$TOT_RAB_08	= $row_08->TOT_RAB;

																											$sql_08A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																											$this->db->query($sql_08A);
																											$sql_08A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																											$this->db->query($sql_08A);
																											$sql_08A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_08 WHERE JOBCODEID = '$JOBD_08' AND PRJCODE = '$PRJCODE'";
																											$this->db->query($sql_08A);

																											$sqlJLH_09		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_08' AND PRJCODE = '$PRJCODE'";
																											$resJLH_09		= $this->db->count_all($sqlJLH_09);
																											if($resJLH_09 > 0)
																											{
																												$sql_09	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_08' AND PRJCODE = '$PRJCODE'";
																												$res_09 = $this->db->query($sql_09)->result();
																												foreach($res_09 as $row_09) :
																													$JOBD_09	= $row_09->JOBCODEID;
																													$JOBH_09	= $row_09->JOBPARENT;
																													$BOQV_09	= $row_09->BOQVAL;
																													$BUDV_09	= $row_09->BUDVAL;
																													$TOT_RAP_09 = 0;
																													$TOT_RAB_09 = 0;
																													$sql_09		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_09)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_09)) AS TOT_RAB
																																	FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																													$res_09 	= $this->db->query($sql_09)->result();
																													foreach($res_09 as $row_09) :
																														$TOT_RAP_09	= $row_09->TOT_RAP;
																														$TOT_RAB_09	= $row_09->TOT_RAB;

																														$sql_09A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																														$this->db->query($sql_09A);
																														$sql_09A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																														$this->db->query($sql_09A);
																														$sql_09A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_09 WHERE JOBCODEID = '$JOBD_09' AND PRJCODE = '$PRJCODE'";
																														$this->db->query($sql_09A);

																														$sqlJLH_10		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBH_09' AND PRJCODE = '$PRJCODE'";
																														$resJLH_10		= $this->db->count_all($sqlJLH_10);
																														if($resJLH_10 > 0)
																														{
																															$sql_10	= "SELECT JOBCODEID, JOBPARENT, (IFNULL(ITM_BUDG, 0)) AS BUDVAL, (IFNULL(BOQ_JOBCOST, 0)) AS BOQVAL FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBH_09' AND PRJCODE = '$PRJCODE'";
																															$res_10 = $this->db->query($sql_10)->result();
																															foreach($res_10 as $row_10) :
																																$JOBD_10	= $row_10->JOBCODEID;
																																$JOBH_10	= $row_10->JOBPARENT;
																																$BOQV_10	= $row_10->BOQVAL;
																																$BUDV_10	= $row_10->BUDVAL;
																																$TOT_RAP_10 = 0;
																																$TOT_RAB_10 = 0;
																																$sql_10		= "SELECT (IFNULL(SUM(ITM_BUDG), $BUDV_10)) AS TOT_RAP, (IFNULL(SUM(BOQ_JOBCOST), $BOQV_10)) AS TOT_RAB
																																				FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																$res_10 	= $this->db->query($sql_10)->result();
																																foreach($res_10 as $row_10) :
																																	$TOT_RAP_10	= $row_10->TOT_RAP;
																																	$TOT_RAB_10	= $row_10->TOT_RAB;

																																	$sql_10A	= "UPDATE tbl_boqlist SET JOBCOST = $TOT_RAP_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																	$this->db->query($sql_10A);
																																	$sql_10A	= "UPDATE tbl_joblist SET JOBCOST = $TOT_RAP_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																	$this->db->query($sql_10A);
																																	$sql_10A	= "UPDATE tbl_joblist_detail SET ITM_BUDG = $TOT_RAP_10 WHERE JOBCODEID = '$JOBD_10' AND PRJCODE = '$PRJCODE'";
																																	$this->db->query($sql_10A);
																																endforeach;
																															endforeach;
																														}
																													endforeach;
																												endforeach;
																											}
																										endforeach;
																									endforeach;
																								}
																							endforeach;
																						endforeach;
																					}
																				endforeach;
																			endforeach;
																		}
																	endforeach;
																endforeach;
															}
														endforeach;
													endforeach;
												}
											endforeach;
										endforeach;
									}
								endforeach;
							endforeach;
						}

					    // START : BAR PERCENTATION PROGRESSED
							$sqlJLTP	= "tbl_joblist_detail WHERE JOBPARENT LIKE '$JOBCODEID%' AND ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
							$resJLTP	= $this->db->count_all($sqlJLTP);

							$percent 	= intval($resJLTP/$resJLT * 100)."%";
							echo '<script>
									    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$resJLTP. ' / '.$resJLT.') processed</span></div>";</script>';

										    ob_flush(); 
										    flush();
					    // END : BAR PERCENTATION PROGRESSED
					endforeach;
				endforeach;
			}

			// 3.	FINISHING INFO
				/*$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Finishing ...</span></div>";</script>';*/

			// 2.	UPDATE IS HEADER OR IS LAST
				/*$sql_ISHD	= "UPDATE tbl_joblist_detail SET ISLAST = 0 WHERE PRJCODE = '$SYNC_PRJ'
								AND JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ')";
				$this->db->query($sql_ISHD);

				$sql_ISD	= "UPDATE tbl_joblist_detail SET ISLAST = 1 WHERE PRJCODE = '$SYNC_PRJ'
								AND JOBCODEID NOT IN (SELECT JOBPARENT FROM tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ')";
				$this->db->query($sql_ISD);*/

			// 3.	COMPLETE INFO
				$percent = intval(100)."%";
				echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
				echo '<script>
				    parent.document.getElementById("loading_1").style.display ="none";
					    parent.clsBarX();
					    parent.updStat();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'SYNCTRXJLD')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');

		$SYNC_PRJ	= $PRJCODE;

		// CLEAR BY PROJECT
			$s_CLR	= "DELETE FROM tbl_joblist_report WHERE PRJCODE = '$SYNC_PRJ'";
			$this->db->query($s_CLR);

			$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = 0, REQ_AMOUNT = 0, PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0,
								WO_QTY = 0, WO_AMOUNT = 0, OPN_QTY = 0, OPN_AMOUNT = 0, ITM_USED = 0, ITM_USED_AM = 0,
								ADD_VOLM = 0, ADD_PRICE = 0, ADD_JOBCOST = 0
							WHERE PRJCODE = '$SYNC_PRJ'";
			$this->db->query($s_UPDJLD);

		// START : PR - PURCHASE REQUEST
			$s_DOCC		= "tbl_pr_detail WHERE PRJCODE = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$s_UPDDET 	= "UPDATE tbl_pr_detail A, tbl_pr_header B SET A.PR_DATE = B.PR_DATE, A.PR_STAT = B.PR_STAT WHERE A.PR_NUM = B.PR_NUM";
			$this->db->query($s_UPDDET);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PR_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.PR_VOLM) AS TOT_VOL, SUM(A.PR_TOTAL) AS TOT_VAL,
									SUM(A.PR_CVOL) AS TOT_CVOL, SUM(A.PR_CTOTAL) AS TOT_CVAL
								FROM tbl_pr_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.PR_STAT IN (3,6)
								GROUP BY A.JOBCODEID, A.PR_DATE ORDER BY A.PR_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;
				$TOT_CVOL 	= $rw_DOC->TOT_CVOL;
				$TOT_CVAL 	= $rw_DOC->TOT_CVAL;

				$GTOT_VOL 	= $TOT_VOL - $TOT_CVOL;
				$GTOT_VAL 	= $TOT_VAL - $TOT_CVAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, PR_VOL, PR_VAL, PR_CVOL, PR_CVAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL, $TOT_CVOL, $TOT_CVAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET PR_VOL = PR_VOL+$TOT_VOL, PR_VAL = PR_VAL+$TOT_VAL,
									 PR_CVOL = PR_CVOL+$TOT_CVOL, PR_CVAL = PR_CVAL+$TOT_CVAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$GTOT_VOL, REQ_AMOUNT = REQ_AMOUNT+$GTOT_VAL
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPP processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPP processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.PR_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.PR_VOLM) AS TOT_VOL, SUM(A.PR_TOTAL) AS TOT_VAL
								FROM tbl_pr_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.PR_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.PR_DATE ORDER BY A.PR_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, PR_VOL_R, PR_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET PR_VOL_R = PR_VOL_R+$TOT_VOL, PR_VAL_R = PR_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPP processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPP processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : PR - PURCHASE REQUEST

		// START : PO - PURCHASE ORDER
			$s_DOCC		= "tbl_po_detail WHERE PRJCODE = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$s_UPDDET 	= "UPDATE tbl_po_detail A, tbl_po_header B SET A.PO_DATE = B.PO_DATE, A.PO_STAT = B.PO_STAT WHERE A.PO_NUM = B.PO_NUM";
			$this->db->query($s_UPDDET);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PO_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.PO_VOLM) AS TOT_VOL, SUM(A.PO_COST) AS TOT_VAL
								FROM tbl_po_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.PO_STAT IN (3,6)
								GROUP BY A.JOBCODEID, A.PO_DATE ORDER BY A.PO_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, PO_VOL, PO_VAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET PO_VOL = PO_VOL+$TOT_VOL, PO_VAL = PO_VAL+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET PO_VOLM = PO_VOLM+$TOT_VOL, PO_AMOUNT = PO_AMOUNT+$TOT_VAL
									/*,ITM_USED = ITM_USED+$TOT_VOL, ITM_USED_AM = ITM_USED_AM+$TOT_VAL*/
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') OP processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') OP processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.PO_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.PO_VOLM) AS TOT_VOL, SUM(A.PO_COST) AS TOT_VAL
								FROM tbl_po_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.PO_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.PO_DATE ORDER BY A.PO_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, PO_VOL_R, PO_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET PO_VOL_R = PO_VOL_R+$TOT_VOL, PO_VAL_R = PO_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') OP processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') OP processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : PO - PURCHASE ORDER

		// START : IR - ITEM RECEIPT
			$s_DOCC		= "tbl_ir_detail WHERE PRJCODE = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$s_UPDDET 	= "UPDATE tbl_ir_detail A, tbl_ir_header B SET A.IR_DATE = B.IR_DATE, A.IR_STAT = B.IR_STAT WHERE A.IR_NUM = B.IR_NUM";
			$this->db->query($s_UPDDET);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.IR_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_QTY) AS TOT_VOL, SUM(A.ITM_TOTAL) AS TOT_VAL
								FROM tbl_ir_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.IR_STAT IN (3,6)
								GROUP BY A.JOBCODEID, A.IR_DATE ORDER BY A.IR_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, IR_VOL, IR_VAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET IR_VOL = IR_VOL+$TOT_VOL, IR_VAL = IR_VAL+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET IR_VOLM = IR_VOLM+$TOT_VOL, IR_AMOUNT = IR_AMOUNT+$TOT_VAL
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') LPM processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') LPM processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.IR_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_QTY) AS TOT_VOL, SUM(A.ITM_TOTAL) AS TOT_VAL
								FROM tbl_ir_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.IR_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.IR_DATE ORDER BY A.IR_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, IR_VOL_R, IR_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET IR_VOL_R = IR_VOL_R+$TOT_VOL, IR_VAL_R = IR_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') LPM processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') LPM processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : IR - ITEM RECEIPT

		// START : WO - WORK ORDER / SPK
			$s_DOCC		= "tbl_wo_detail WHERE PRJCODE = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$s_UPDDET 	= "UPDATE tbl_wo_detail A, tbl_wo_header B SET A.WO_DATE = B.WO_DATE, A.WO_STAT = B.WO_STAT WHERE A.WO_NUM = B.WO_NUM";
			$this->db->query($s_UPDDET);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.WO_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.WO_VOLM) AS TOT_VOL, SUM(A.WO_TOTAL) AS TOT_VAL,
									SUM(A.WO_CVOL) AS TOT_CVOL, SUM(A.WO_CAMN) AS TOT_CVAL
								FROM tbl_wo_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.WO_STAT IN (3,6)
								GROUP BY A.JOBCODEID, A.WO_DATE ORDER BY A.WO_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;
				$TOT_CVOL 	= $rw_DOC->TOT_CVOL;
				$TOT_CVAL 	= $rw_DOC->TOT_CVAL;

				$GTOT_VOL 	= $TOT_VOL - $TOT_CVOL;
				$GTOT_VAL 	= $TOT_VAL - $TOT_CVAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, WO_VOL, WO_VAL, WO_CVOL, WO_CVAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL, $TOT_CVOL, $TOT_CVAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET WO_VOL = WO_VOL+$TOT_VOL, WO_VAL = WO_VAL+$TOT_VAL,
									 WO_CVOL = WO_CVOL+$TOT_CVOL, WO_CVAL = WO_CVAL+$TOT_CVAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET WO_QTY = WO_QTY+$GTOT_VOL, WO_AMOUNT = WO_AMOUNT+$GTOT_VAL,
									REQ_VOLM = REQ_VOLM+$GTOT_VOL, REQ_AMOUNT = REQ_AMOUNT+$GTOT_VAL
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPK processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPK processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.WO_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.WO_VOLM) AS TOT_VOL, SUM(A.WO_TOTAL) AS TOT_VAL
								FROM tbl_wo_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.WO_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.WO_DATE ORDER BY A.WO_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, WO_VOL_R, WO_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET WO_VOL_R = WO_VOL_R+$TOT_VOL, WO_VAL_R = WO_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPK processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPK processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : WO - WORK ORDER / SPK

		// START : OPN - OPNAME
			$s_DOCC		= "tbl_opn_detail WHERE PRJCODE = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$s_UPDDET 	= "UPDATE tbl_opn_detail A, tbl_opn_header B SET A.OPNH_DATE = B.OPNH_DATE, A.OPNH_STAT = B.OPNH_STAT WHERE A.OPNH_NUM = B.OPNH_NUM";
			$this->db->query($s_UPDDET);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.OPNH_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.OPND_VOLM) AS TOT_VOL, SUM(A.OPND_ITMTOTAL) AS TOT_VAL
								FROM tbl_opn_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.OPNH_STAT IN (3,6)
								GROUP BY A.JOBCODEID, A.OPNH_DATE ORDER BY A.OPNH_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, OPN_VOL, OPN_VAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET OPN_VOL = OPN_VOL+$TOT_VOL, OPN_VAL = OPN_VAL+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY+$TOT_VOL, OPN_AMOUNT = OPN_AMOUNT+$TOT_VAL,
									ITM_USED = ITM_USED+$TOT_VOL, ITM_USED_AM = ITM_USED_AM+$TOT_VAL
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Opname processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Opname processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.OPNH_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.OPND_VOLM) AS TOT_VOL, SUM(A.OPND_ITMTOTAL) AS TOT_VAL
								FROM tbl_opn_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.OPNH_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.OPNH_DATE ORDER BY A.OPNH_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, OPN_VOL_R, OPN_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET OPN_VOL_R = OPN_VOL_R+$TOT_VOL, OPN_VAL_R = OPN_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Opname processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Opname processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : OPN - OPNAME

		// START : VCASH - VOUCHER CASH
			$s_DOCC		= "tbl_journaldetail_vcash WHERE proj_Code = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$s_UPDDET 	= "UPDATE tbl_journaldetail_vcash A, tbl_journalheader_vcash B SET A.Journalh_Date = B.Journalh_Date, A.GEJ_STAT = B.GEJ_STAT WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($s_UPDDET);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.Journalh_Date AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_VOLM) AS TOT_VOL, SUM(A.Base_Debet) AS TOT_VAL
								FROM tbl_journaldetail_vcash A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.proj_Code = B.PRJCODE
								WHERE A.proj_Code = '$SYNC_PRJ' AND A.GEJ_STAT IN (3,6)
								GROUP BY A.JOBCODEID, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, VCASH_VOL, VCASH_VAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET VCASH_VOL = VCASH_VOL+$TOT_VOL, VCASH_VAL = VCASH_VAL+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$TOT_VOL, REQ_AMOUNT = REQ_AMOUNT+$TOT_VAL,
									PO_VOLM = PO_VOLM+$TOT_VOL, PO_AMOUNT = PO_AMOUNT+$TOT_VAL,
									ITM_USED = ITM_USED+$TOT_VOL, ITM_USED_AM = ITM_USED_AM+$TOT_VAL
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.Journalh_Date AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_VOLM) AS TOT_VOL, SUM(A.Base_Debet) AS TOT_VAL
								FROM tbl_journaldetail_vcash A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.proj_Code = B.PRJCODE
								WHERE A.proj_Code = '$SYNC_PRJ' AND A.GEJ_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, VCASH_VOL_R, VCASH_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET VCASH_VOL_R = VCASH_VOL_R+$TOT_VOL, VCASH_VAL_R = VCASH_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : VCASH - VOUCHER CASH

		// START : PPD - PENYELESAIAN PD
			$s_DOCC		= "tbl_journaldetail_pd WHERE proj_Code = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$s_UPDDET 	= "UPDATE tbl_journaldetail_pd A, tbl_journalheader_pd B SET A.Journalh_Date = B.Journalh_Date, A.GEJ_STAT = B.GEJ_STAT WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($s_UPDDET);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.Journalh_Date AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_VOLM) AS TOT_VOL, SUM(A.Base_Debet) AS TOT_VAL
								FROM tbl_journaldetail_pd A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.proj_Code = B.PRJCODE
								WHERE A.proj_Code = '$SYNC_PRJ' AND A.GEJ_STAT IN (3,6)
								GROUP BY A.JOBCODEID, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, PPD_VOL, PPD_VAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET PPD_VOL = PPD_VOL+$TOT_VOL, PPD_VAL = PPD_VAL+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$TOT_VOL, REQ_AMOUNT = REQ_AMOUNT+$TOT_VAL,
									PO_VOLM = PO_VOLM+$TOT_VOL, PO_AMOUNT = PO_AMOUNT+$TOT_VAL,
									ITM_USED = ITM_USED+$TOT_VOL, ITM_USED_AM = ITM_USED_AM+$TOT_VAL
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.Journalh_Date AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_VOLM) AS TOT_VOL, SUM(A.Base_Debet) AS TOT_VAL
								FROM tbl_journaldetail_pd A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.proj_Code = B.PRJCODE
								WHERE A.proj_Code = '$SYNC_PRJ' AND A.GEJ_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, PPD_VOL_R, PPD_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET PPD_VOL_R = PPD_VOL_R+$TOT_VOL, PPD_VAL_R = PPD_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : PPD - PENYELESAIAN PD

		// START : VLK - VOUCHER LUAR KOTA
			$s_DOCC		= "tbl_journaldetail_cprj WHERE proj_Code = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$s_UPDDET 	= "UPDATE tbl_journaldetail_cprj A, tbl_journalheader_cprj B SET A.Journalh_Date = B.Journalh_Date, A.GEJ_STAT = B.GEJ_STAT WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($s_UPDDET);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.Journalh_Date AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_VOLM) AS TOT_VOL, SUM(A.Base_Debet) AS TOT_VAL
								FROM tbl_journaldetail_cprj A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.proj_Code = B.PRJCODE
								WHERE A.proj_Code = '$SYNC_PRJ' AND A.GEJ_STAT IN (3,6)
								GROUP BY A.JOBCODEID, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, VLK_VOL, VLK_VAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET VLK_VOL = VLK_VOL+$TOT_VOL, VLK_VAL = VLK_VAL+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$TOT_VOL, REQ_AMOUNT = REQ_AMOUNT+$TOT_VAL,
									PO_VOLM = PO_VOLM+$TOT_VOL, PO_AMOUNT = PO_AMOUNT+$TOT_VAL,
									ITM_USED = ITM_USED+$TOT_VOL, ITM_USED_AM = ITM_USED_AM+$TOT_VAL
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.Journalh_Date AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_VOLM) AS TOT_VOL, SUM(A.Base_Debet) AS TOT_VAL
								FROM tbl_journaldetail_cprj A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.proj_Code = B.PRJCODE
								WHERE A.proj_Code = '$SYNC_PRJ' AND A.GEJ_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, VLK_VOL_R, VLK_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET VLK_VOL_R = VLK_VOL_R+$TOT_VOL, VLK_VAL_R = VLK_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : VLK - VOUCHER LUAR KOTA

		// START : AMD - AMANDEMEN
			$s_DOCC		= "tbl_amd_detail WHERE PRJCODE = '$SYNC_PRJ'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT B.AMD_DATE AS DOC_DATE, A.JOBCODEID, A.JOBDESC, SUM(A.AMD_VOLM) AS TOT_VOL, SUM(A.AMD_TOTAL) AS TOT_VAL
								FROM tbl_amd_detail A INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND B.AMD_STAT IN (3,6)
								GROUP BY A.JOBCODEID, B.AMD_DATE ORDER BY B.AMD_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, AMD_VOL, AMD_VAL)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET AMD_VOL = AMD_VOL+$TOT_VOL, AMD_VAL = AMD_VAL+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$s_UPDJLD 	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM+$TOT_VOL, ADD_JOBCOST = ADD_JOBCOST+$TOT_VAL, ADD_PRICE = (ADD_JOBCOST/ADD_VOLM)
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($s_UPDJLD);

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Amandemen processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Amandemen processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT B.AMD_DATE AS DOC_DATE, A.JOBCODEID, A.JOBDESC, SUM(A.AMD_VOLM) AS TOT_VOL, SUM(A.AMD_TOTAL) AS TOT_VAL
								FROM tbl_amd_detail A INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND B.AMD_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, B.AMD_DATE ORDER BY B.AMD_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, AMD_VOL_R, AMD_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET AMD_VOL_R = AMD_VOL_R+$TOT_VOL, AMD_VAL_R = AMD_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Amandemen processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Amandemen processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : PR - PURCHASE REQUEST

		// 2.	COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else if($IMP_TYPE == 'SYNCDASHTRX')
	{
		date_default_timezone_set("Asia/Jakarta");
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');

		$SYNC_PRJ	= $PRJCODE;

		// CLEAR DASHBOARD
			$s_UPDFD 	= "TRUNCATE tbl_financial_dash";
			$this->db->query($s_UPDFD);

		// START : PR - PURCHASE REQUEST
			$s_UPDDET 	= "UPDATE tbl_pr_detail A, tbl_pr_header B SET A.PR_DATE = B.PR_DATE, A.PR_STAT = B.PR_STAT WHERE A.PR_NUM = B.PR_NUM";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_pr_detail A WHERE A.PR_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.PR_DATE AS DOC_DATE, SUM(A.PR_TOTAL) AS TOT_VAL, SUM(A.PR_CVOL) AS TOT_CVOL, SUM(A.PR_CTOTAL) AS TOT_CVAL
								FROM tbl_pr_detail A WHERE A.PR_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.PR_DATE ORDER BY A.PR_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;
				$TOT_CVOL 	= $rw_DOC->TOT_CVOL;
				$TOT_CVAL 	= $rw_DOC->TOT_CVAL;
				$TOT_VALR 	= $TOT_VAL - $TOT_CVAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, PR_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VALR)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET PR_VAL = PR_VAL+$TOT_VALR WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPP processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPP processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : PR - PURCHASE REQUEST

		// START : PO - PURCHASE ORDER
			$s_UPDDET 	= "UPDATE tbl_po_detail A, tbl_po_header B SET A.PO_DATE = B.PO_DATE, A.PO_STAT = B.PO_STAT WHERE A.PO_NUM = B.PO_NUM";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_po_detail A WHERE A.PO_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.PO_DATE AS DOC_DATE, SUM(A.PO_COST) AS TOT_VAL FROM tbl_po_detail A WHERE A.PO_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.PO_DATE ORDER BY A.PO_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, PO_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET PO_VAL = PO_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') OP processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') OP processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : PO - PURCHASE ORDER

		// START : IR - ITEM RECEIPT
			$s_UPDDET 	= "UPDATE tbl_ir_detail A, tbl_ir_header B SET A.IR_DATE = B.IR_DATE, A.IR_STAT = B.IR_STAT WHERE A.IR_NUM = B.IR_NUM";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_ir_detail A WHERE A.IR_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.IR_DATE AS DOC_DATE, SUM(A.ITM_TOTAL) AS TOT_VAL FROM tbl_ir_detail A WHERE A.IR_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.IR_DATE ORDER BY A.IR_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, IR_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET IR_VAL = IR_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') LPM processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') LPM processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : IR - ITEM RECEIPT

		// START : WO - WORK ORDER / SPK
			$s_UPDDET 	= "UPDATE tbl_wo_detail A, tbl_wo_header B SET A.WO_DATE = B.WO_DATE, A.WO_STAT = B.WO_STAT WHERE A.WO_NUM = B.WO_NUM";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_wo_detail A WHERE A.WO_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.WO_DATE AS DOC_DATE, SUM(A.WO_TOTAL) AS TOT_VAL, SUM(A.WO_CAMN) AS TOT_CVAL
								FROM tbl_wo_detail A WHERE A.WO_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.WO_DATE ORDER BY A.WO_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;
				$TOT_CVAL 	= $rw_DOC->TOT_CVAL;
				$TOT_VALR 	= $TOT_VAL - $TOT_CVAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, WO_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VALR)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET WO_VAL = WO_VAL+$TOT_VALR WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPK processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPK processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.WO_DATE AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.WO_VOLM) AS TOT_VOL, SUM(A.WO_TOTAL) AS TOT_VAL
								FROM tbl_wo_detail A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$SYNC_PRJ' AND A.WO_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.WO_DATE ORDER BY A.WO_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, WO_VOL_R, WO_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET WO_VOL_R = WO_VOL_R+$TOT_VOL, WO_VAL_R = WO_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPK processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') SPK processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : WO - WORK ORDER / SPK

		// START : OPN - OPNAME
			$s_UPDDET 	= "UPDATE tbl_opn_detail A, tbl_opn_header B SET A.OPNH_DATE = B.OPNH_DATE, A.OPNH_STAT = B.OPNH_STAT WHERE A.OPNH_NUM = B.OPNH_NUM";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_opn_detail A WHERE A.OPNH_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.OPNH_DATE AS DOC_DATE, SUM(A.OPND_ITMTOTAL) AS TOT_VAL FROM tbl_opn_detail A WHERE A.OPNH_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.OPNH_DATE ORDER BY A.OPNH_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, OPN_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET OPN_VAL = OPN_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Opname processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Opname processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : OPN - OPNAME

		// START : VCASH - VOUCHER CASH
			$s_UPDDET 	= "UPDATE tbl_journaldetail_vcash A, tbl_journalheader_vcash B SET A.Journalh_Date = B.Journalh_Date, A.GEJ_STAT = B.GEJ_STAT WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_journaldetail_vcash A WHERE A.GEJ_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.proj_Code, A.Journalh_Date AS DOC_DATE, SUM(A.Base_Debet) AS TOT_VAL FROM tbl_journaldetail_vcash A WHERE A.GEJ_STAT IN (2,3,6)
								GROUP BY A.proj_Code, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->proj_Code;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, VCASH_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET VCASH_VAL = VCASH_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;

			$s_DOC		= "SELECT A.Journalh_Date AS DOC_DATE, A.JOBCODEID, B.JOBDESC, SUM(A.ITM_VOLM) AS TOT_VOL, SUM(A.Base_Debet) AS TOT_VAL
								FROM tbl_journaldetail_vcash A INNER JOIN tbl_joblist_detail B
									ON A.JOBCODEID = B.JOBCODEID AND A.proj_Code = B.PRJCODE
								WHERE A.proj_Code = '$SYNC_PRJ' AND A.GEJ_STAT IN (1,2,4,7)
								GROUP BY A.JOBCODEID, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$JOBCODEID 	= $rw_DOC->JOBCODEID;
				$JOBDESC 	= $rw_DOC->JOBDESC;
				$TOT_VOL 	= $rw_DOC->TOT_VOL;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_joblist_report WHERE PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$SYNC_PRJ'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_joblist_report (PRJCODE, PERIODE, JOBCODEID, JOBDESC, VCASH_VOL_R, VCASH_VAL_R)
								VALUES ('$SYNC_PRJ', '$DOC_DATE', '$JOBCODEID', '$JOBDESC', $TOT_VOL, $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_joblist_report SET VCASH_VOL_R = VCASH_VOL_R+$TOT_VOL, VCASH_VAL_R = VCASH_VAL_R+$TOT_VAL
								WHERE PRJCODE = '$SYNC_PRJ' AND PERIODE = '$DOC_DATE' AND JOBCODEID = '$JOBCODEID'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : VCASH - VOUCHER CASH

		// START : VLK - VOUCHER LUAR KOTA
			$s_UPDDET 	= "UPDATE tbl_journaldetail_cprj A, tbl_journalheader_cprj B SET A.Journalh_Date = B.Journalh_Date, A.GEJ_STAT = B.GEJ_STAT WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_journaldetail_cprj A WHERE A.GEJ_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.proj_Code, A.Journalh_Date AS DOC_DATE, SUM(A.Base_Debet) AS TOT_VAL FROM tbl_journaldetail_cprj A WHERE A.GEJ_STAT IN (2,3,6)
								GROUP BY A.proj_Code, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->proj_Code;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, VLK_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET VLK_VAL = VLK_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}
				
				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : VLK - VOUCHER LUAR KOTA

		// START : PPD - PINJAMAN DINAS (PD)
			$s_UPDDET 	= "UPDATE tbl_journaldetail_pd A, tbl_journalheader_pd B SET A.Journalh_Date = B.Journalh_Date, A.GEJ_STAT = B.GEJ_STAT WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_journaldetail_pd A WHERE A.GEJ_STAT IN (2,3,6) AND A.ISPERSL_REALIZ = '0'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.proj_Code, A.Journalh_Date AS DOC_DATE, SUM(A.Base_Debet) AS TOT_VAL FROM tbl_journaldetail_pd A WHERE A.GEJ_STAT IN (2,3,6) AND A.ISPERSL_REALIZ = 0
								GROUP BY A.proj_Code, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->proj_Code;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, PD_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET PD_VAL = PD_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : PPD - PINJAMAN DINAS (PD)

		// START : PPD - PENYELESAIAN PD
			$s_UPDDET 	= "UPDATE tbl_journaldetail_pd A, tbl_journalheader_pd B SET A.Journalh_Date = B.Journalh_Date, A.GEJ_STAT = B.GEJ_STAT WHERE A.JournalH_Code = B.JournalH_Code";
			$this->db->query($s_UPDDET);

			$s_DOCC		= "tbl_journaldetail_pd A WHERE A.GEJ_STAT IN (2,3,6) AND A.ISPERSL_REALIZ = '1'";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.proj_Code, A.Journalh_Date AS DOC_DATE, SUM(A.Base_Debet) AS TOT_VAL FROM tbl_journaldetail_pd A WHERE A.GEJ_STAT IN (2,3,6) AND A.ISPERSL_REALIZ = 1
								GROUP BY A.proj_Code, A.Journalh_Date ORDER BY A.Journalh_Date";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->proj_Code;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, PPD_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET PPD_VAL = PPD_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : PPD - PENYELESAIAN PD

		// START : AMD - AMANDEMEN
			$s_DOCC		= "tbl_amd_detail A INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM AND A.PRJCODE = B.PRJCODE
								WHERE B.AMD_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT B.PRJCODE, B.AMD_DATE AS DOC_DATE, SUM(A.AMD_TOTAL) AS TOT_VAL
								FROM tbl_amd_detail A INNER JOIN tbl_amd_header B ON A.AMD_NUM = B.AMD_NUM AND A.PRJCODE = B.PRJCODE
								WHERE B.AMD_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, B.AMD_DATE ORDER BY B.AMD_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, AMD_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET AMD_VAL = AMD_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Amandemen processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Amandemen processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : AMD - AMANDEMEN

		// START : TTK
			$s_DOCC		= "tbl_ttk_header A WHERE A.TTK_STAT IN (3)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.TTK_DATE AS DOC_DATE, SUM(A.TTK_GTOTAL) AS TOT_VAL FROM tbl_ttk_header A WHERE A.TTK_STAT IN (3)
								GROUP BY A.PRJCODE, A.TTK_DATE ORDER BY A.TTK_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, TTK_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET TTK_VAL = TTK_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : TTK

		// START : VOUCHER
			$s_DOCC		= "tbl_pinv_header A WHERE A.INV_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.INV_DATE AS DOC_DATE, SUM(A.INV_AMOUNT_TOT) AS TOT_VAL FROM tbl_pinv_header A WHERE A.INV_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.INV_DATE ORDER BY A.INV_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, INV_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET INV_VAL = INV_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : VOUCHER

		// START : DOWN PAYMENT
			$s_DOCC		= "tbl_dp_header A WHERE A.DP_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.DP_DATE AS DOC_DATE, SUM(A.DP_AMOUN_TOT) AS TOT_VAL FROM tbl_dp_header A WHERE A.DP_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.DP_DATE ORDER BY A.DP_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, DP_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET DP_VAL = DP_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : DOWN PAYMENT

		// START : BANK PAYMENT
			$s_DOCC		= "tbl_bp_header A WHERE A.CB_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.CB_DATE AS DOC_DATE, SUM(A.CB_TOTAM) AS TOT_VAL FROM tbl_bp_header A WHERE A.CB_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.CB_DATE ORDER BY A.CB_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, BP_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET BP_VAL = BP_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : BANK PAYMENT

		// START : MC PROJECT
			$s_DOCC		= "tbl_mcheader A WHERE A.MC_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.MC_DATE AS DOC_DATE, SUM(A.MC_TOTVAL) AS TOT_VAL FROM tbl_mcheader A WHERE A.MC_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.MC_DATE ORDER BY A.MC_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, MC_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET MC_VAL = MC_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : MC PROJECT

		// START : MC PROJECT
			$s_DOCC		= "tbl_projinv_header A WHERE A.PINV_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.PINV_DATE AS DOC_DATE, SUM(A.GPINV_TOTVAL) AS TOT_VAL FROM tbl_projinv_header A WHERE A.PINV_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.PINV_DATE ORDER BY A.PINV_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, PINV_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET PINV_VAL = PINV_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : MC PROJECT

		// START : BANK RECEIPT
			$s_DOCC		= "tbl_br_header A WHERE A.BR_STAT IN (2,3,6)";
			$r_DOCC		= $this->db->count_all($s_DOCC);

			$ROW_DOC 	= 0;
			$s_DOC		= "SELECT A.PRJCODE, A.BR_DATE AS DOC_DATE, SUM(A.BR_TOTAM) AS TOT_VAL FROM tbl_br_header A WHERE A.BR_STAT IN (2,3,6)
								GROUP BY A.PRJCODE, A.BR_DATE ORDER BY A.BR_DATE";
			$r_DOC		= $this->db->query($s_DOC)->result();
			foreach($r_DOC as $rw_DOC):
				$ROW_DOC 	= $ROW_DOC+1;
				$PRJCODE 	= $rw_DOC->PRJCODE;
				$DOC_DATE 	= $rw_DOC->DOC_DATE;
				$TOT_VAL 	= $rw_DOC->TOT_VAL;

				$s_00 		= "tbl_financial_dash WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
				$r_00  		= $this->db->count_all($s_00);
				if($r_00 == 0)
				{
					$s_01	= "INSERT INTO tbl_financial_dash (PRJCODE, PERIODE, BR_VAL) VALUES ('$PRJCODE', '$DOC_DATE', $TOT_VAL)";
					$this->db->query($s_01);
				}
				else
				{
					$s_01	= "UPDATE tbl_financial_dash SET BR_VAL = BR_VAL+$TOT_VAL WHERE PERIODE = '$DOC_DATE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

				$percent 	= intval($ROW_DOC / $r_DOCC * 100)."%";
				echo '<script>
					  parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
				echo '<script>
					  parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ROW_DOC. ' / '.$r_DOCC.') Voucher Cash processed</span></div>";</script>';

			    ob_flush(); 
			    flush();
			endforeach;
		// END : BANK RECEIPT

		// 2.	COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("loading_1").style.display ="none";
				    parent.updStat();</script>';
		// END : PROSES PROCEDUR - RESET ORDER
	}
	else 	// FRO IMPORT ITEM
	{
		$percent = intval(100)."%";
		echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
		echo '<script>
		    parent.document.getElementById("loading_1").style.display ="none";
		    parent.document.getElementById("loading_2").style.display ="none";
			    parent.updStat();</script>';
	}

	// ORIGINAL
		/*$total = 100;
		for($i=0;$i<$total;$i++)
		{
			//$sql1 = "INSERT INTO tbl_sample (fname, lname) VALUES ('AAAAAA','BBBBBBB')";
			//$this->db->query($sql1);

		    $percent = intval($i/$total * 100)."%";
			
		    sleep(1); // Here call your time taking function like sending bulk sms etc.


		    //echo '<script>
		    //parent.document.getElementById("progressbarXX").innerHTML="<div style=\"width:'.$percent.';background:linear-gradient(to bottom, rgba(125,126,125,1) 0%,rgba(14,14,14,1) 100%); ;height:35px;\">&nbsp;</div>";
		   // parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</div>";</script>';
		    echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

		    ob_flush(); 
		    flush();
		}
		$percent = intval(100)."%";
		echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';*/
}
?>
<script > 
    function sleep(milliseconds) { 
        let timeStart = new Date().getTime(); 
        while (true) { 
            let elapsedTime = new Date().getTime() - timeStart; 
            if (elapsedTime > milliseconds) { 
                break; 
            } 
        } 
    }
</script>

<!DOCTYPE html>
<html>
	<head>
		<title>How to Create Progress Bar for Data Insert in PHP using Ajax</title>  
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.8/js/bootstrap.min.js"></script>
	</head>

	<body>
		<br />
		<br />
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-body">
					<span id="success_message"></span>
					<form method="post" id="sample_form">
						<div class="form-group">
							<label>PRJCODE</label>
							<input type="text" name="PRJCODE" id="PRJCODE" class="form-control" value="" />
						</div>
						<div class="form-group">
							<label>PERIODE</label>
							<input type="text" name="PRJPERIOD" id="PRJPERIOD" class="form-control" value="" />
						</div>
						<div class="form-group">
							<label>IMPORT CODE</label>
							<input type="text" name="IMP_CODEX" id="IMP_CODEX" class="form-control" value="" />
						</div>
						<div class="form-group">
							<label>TIPE</label>
							<input type="text" name="IMP_TYPE" id="IMP_TYPE" class="form-control" value="" />
						</div>
						<div class="form-group">
							<label>TIPE</label>
							<input type="text" name="DESCRIPT" id="DESCRIPT" class="form-control" value="" />
						</div>
						<div class="form-group" align="center">
							<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
						</div>
					</form>
					<div class="form-group" id="process" style="display:none;">
						<div class="progress">
							<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
 	</body>
</html>