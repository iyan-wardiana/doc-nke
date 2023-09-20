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
if(isset($_POST['IMP_CODEX']))	// MARKETING
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
		 * A class for reading Microsoft Excel (97/2003) Spreadsheets.
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
		 * maintained by David Sanders.  Reads only Biff 7 and Biff 8 formats.
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
		 * @version	CVS: $Id: reader.php 19 2007-03-13 12:42:41Z shangxiao $
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
		define('START_BLOCK_POS', 0x74);
		define('SIZE_POS', 0x78);
		define('IDENTIFIER_OLE', pack("CCCCCCCC",0xd0,0xcf,0x11,0xe0,0xa1,0xb1,0x1a,0xe1));
		function GetInt4d($data, $pos) {
			$value = ord($data[$pos]) | (ord($data[$pos+1])	<< 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
			if ($value>=4294967294) {
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
		define('SPREADSHEET_EXCEL_READER_BIFF8',			 0x600);
		define('SPREADSHEET_EXCEL_READER_BIFF7',			 0x500);
		define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS',   0x5);
		define('SPREADSHEET_EXCEL_READER_WORKSHEET',		 0x10);
		define('SPREADSHEET_EXCEL_READER_TYPE_BOF',		  0x809);
		define('SPREADSHEET_EXCEL_READER_TYPE_EOF',		  0x0a);
		define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET',   0x85);
		define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION',	0x200);
		define('SPREADSHEET_EXCEL_READER_TYPE_ROW',		  0x208);
		define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL',	   0xd7);
		define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS',	 0x2f);
		define('SPREADSHEET_EXCEL_READER_TYPE_NOTE',		 0x1c);
		define('SPREADSHEET_EXCEL_READER_TYPE_TXO',		  0x1b6);
		define('SPREADSHEET_EXCEL_READER_TYPE_RK',		   0x7e);
		define('SPREADSHEET_EXCEL_READER_TYPE_RK2',		  0x27e);
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
		define('SPREADSHEET_EXCEL_READER_TYPE_STRING',	   0x207);
		define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA',	  0x406);
		define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2',	 0x6);
		define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT',	   0x41e);
		define('SPREADSHEET_EXCEL_READER_TYPE_XF',		   0xe0);
		define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR',	  0x205);
		define('SPREADSHEET_EXCEL_READER_TYPE_FONT',	  0x0031);
		define('SPREADSHEET_EXCEL_READER_TYPE_PALETTE',	  0x0092);
		define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN',	  0xffff);
		define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);
		define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS',  0xE5);
		define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS' ,	25569);
		define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);
		define('SPREADSHEET_EXCEL_READER_MSINADAY',		  86400);
		define('SPREADSHEET_EXCEL_READER_TYPE_HYPER',	     0x01b8);
		define('SPREADSHEET_EXCEL_READER_TYPE_COLINFO',	     0x7d);
		define('SPREADSHEET_EXCEL_READER_TYPE_DEFCOLWIDTH',  0x55);
		define('SPREADSHEET_EXCEL_READER_TYPE_STANDARDWIDTH', 0x99);
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
				return $this->colInfo[$sheet][$col]['width']/9142*200; 
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
				if (($ci <> 0x7FFF) && ($ci <> '')) {
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
				0x7 => "\$#,##0.00;(\$#,##0.00)",
				0x8 => "\$#,##0.00;[Red](\$#,##0.00)",
				0x9 => "0%",
				0xa => "0.00%",
				0xb => "0.00E+00",
				0x25 => "#,##0;(#,##0)",
				0x26 => "#,##0;[Red](#,##0)",
				0x27 => "#,##0.00;(#,##0.00)",
				0x28 => "#,##0.00;[Red](#,##0.00)",
				0x29 => "#,##0;(#,##0)",  // Not exactly
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
				0x07 => "#00FFFF",
				0x08 => "#000000",
				0x09 => "#FFFFFF",
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
				0x17 => "#808080",
				0x18 => "#9999FF",
				0x19 => "#993366",
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
				0x27 => "#0000FF",
				0x28 => "#00CCFF",
				0x29 => "#CCFFFF",
				0x2A => "#CCFFCC",
				0x2B => "#FFFF99",
				0x2C => "#99CCFF",
				0x2D => "#FF99CC",
				0x2E => "#CC99FF",
				0x2F => "#FFCC99",
		
				0x30 => "#3366FF",
				0x31 => "#33CCCC",
				0x32 => "#99CC00",
				0x33 => "#FFCC00",
				0x34 => "#FF9900",
				0x35 => "#FF6600",
				0x36 => "#666699",
				0x37 => "#969696",
				0x38 => "#003366",
				0x39 => "#339966",
				0x3A => "#003300",
				0x3B => "#333300",
				0x3C => "#993300",
				0x3D => "#993366",
				0x3E => "#333399",
				0x3F => "#333333",
				0x40 => "#000000",
				0x41 => "#FFFFFF",
				0x43 => "#000000",
				0x4D => "#000000",
				0x4E => "#FFFFFF",
				0x4F => "#000000",
				0x50 => "#FFFFFF",
				0x51 => "#000000",
				0x7FFF => "#000000"
			);
			var $lineStyles = array(
				0x00 => "",
				0x01 => "Thin",
				0x02 => "Medium",
				0x03 => "Dashed",
				0x04 => "Dotted",
				0x05 => "Thick",
				0x06 => "Double",
				0x07 => "Hair",
				0x08 => "Medium dashed",
				0x09 => "Thin dash-dotted",
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
				// 49==TEXT format
				// http://code.google.com/p/php-excel-reader/issues/detail?id=7
				if ( (!$f && $format=="%s") || ($f==49) || ($format=="GENERAL") ) { 
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
					($version != SPREADSHEET_EXCEL_READER_BIFF7)) {
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
									$formatString = substr($data, $pos+9, $numchars);
								} else {
									$formatString = substr($data, $pos+9, $numchars*2);
								}
							} else {
								$numchars = ord($data[$pos+6]);
								$formatString = substr($data, $pos+7, $numchars*2);
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
								if ((ord($data[$pos+19]) & 1) == 0){
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
										'bold' => ($weight==700),
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
									$this->colors[0x07 + $coli] = '#' . $this->myhex($colr) . $this->myhex($colg) . $this->myhex($colb);
								}
								break;
						case SPREADSHEET_EXCEL_READER_TYPE_XF:
								$fontIndexCode = (ord($data[$pos+4]) | ord($data[$pos+5]) << 8) - 1;
								$fontIndexCode = max(0,$fontIndexCode);
								$indexCode = ord($data[$pos+6]) | ord($data[$pos+7]) << 8;
								$alignbit = ord($data[$pos+10]) & 3;
								$bgi = (ord($data[$pos+22]) | ord($data[$pos+23]) << 8) & 0x3FFF;
								$bgcolor = ($bgi & 0x7F);
		//						$bgcolor = ($bgi & 0x3f80) >> 7;
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
								$border = ord($data[$pos+14]) | (ord($data[$pos+15]) << 8) | (ord($data[$pos+16]) << 16) | (ord($data[$pos+17]) << 24);
								$xf['borderLeft'] = $this->lineStyles[($border & 0xF)];
								$xf['borderRight'] = $this->lineStyles[($border & 0xF0) >> 4];
								$xf['borderTop'] = $this->lineStyles[($border & 0xF00) >> 8];
								$xf['borderBottom'] = $this->lineStyles[($border & 0xF000) >> 12];
								
								$xf['borderLeftColor'] = ($border & 0x7F0000) >> 16;
								$xf['borderRightColor'] = ($border & 0x3F800000) >> 23;
								$border = (ord($data[$pos+18]) | ord($data[$pos+19]) << 8);
								$xf['borderTopColor'] = ($border & 0x7F);
								$xf['borderBottomColor'] = ($border & 0x3F80) >> 7;
														
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
								$rec_visibilityFlag = ord($data[$pos+9]);
								$rec_length = ord($data[$pos+10]);
								if ($version == SPREADSHEET_EXCEL_READER_BIFF8){
									$chartype =  ord($data[$pos+11]);
									if ($chartype == 0){
										$rec_name	= substr($data, $pos+12, $rec_length);
									} else {
										$rec_name	= $this->_encodeUTF16(substr($data, $pos+12, $rec_length*2));
									}
								}elseif ($version == SPREADSHEET_EXCEL_READER_BIFF7){
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
				$substreamType = ord($data[$spos + 6]) | ord($data[$spos + 7])<<8;
				if (($version != SPREADSHEET_EXCEL_READER_BIFF8) && ($version != SPREADSHEET_EXCEL_READER_BIFF7)) {
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
								if (($length == 10) ||  ($version == SPREADSHEET_EXCEL_READER_BIFF7)){
									$this->sheets[$this->sn]['numRows'] = ord($data[$spos+2]) | ord($data[$spos+3]) << 8;
									$this->sheets[$this->sn]['numCols'] = ord($data[$spos+6]) | ord($data[$spos+7]) << 8;
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
								$fc =  ord($data[$spos + 8*$i + 6]) | ord($data[$spos + 8*$i + 7])<<8;
								$lc =  ord($data[$spos + 8*$i + 8]) | ord($data[$spos + 8*$i + 9])<<8;
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
							$numValue = $this->_GetIEEE754($rknum);
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
								$numValue = $this->_GetIEEE754($this->_GetInt4d($data, $tmppos + 2));
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
							elseif ($version == SPREADSHEET_EXCEL_READER_BIFF7){
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
							$rowInfo = ord($data[$spos + 6]) | ((ord($data[$spos+7]) << 8) & 0x7FFF);
							if (($rowInfo & 0x8000) > 0) {
								$rowHeight = -1;
							} else {
								$rowHeight = $rowInfo & 0x7FFF;
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
							$this->addcell($row, $column, substr($data, $spos + 8, ord($data[$spos + 6]) | ord($data[$spos + 7])<<8));
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_EOF:
							$cont = false;
							break;
						case SPREADSHEET_EXCEL_READER_TYPE_HYPER:
							//  Only handle hyperlinks to a URL
							$row	= ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
							$row2   = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
							$column = ord($this->data[$spos+4]) | ord($this->data[$spos+5])<<8;
							$column2 = ord($this->data[$spos+6]) | ord($this->data[$spos+7])<<8;
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
							$cxf = ord($data[$spos+6]) | ord($data[$spos+7]) << 8; 
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
						// See http://groups.google.com/group/php-excel-reader-discuss/browse_frm/thread/9c3f9790d12d8e10/f2045c2369ac79de
						$rectype = 'date';
						// Convert numeric value into a date
						$utcDays = floor($numValue - ($this->nineteenFour ? SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904 : SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS));
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
				$exp =  ($rknumhigh & 0x7ff00000) >> 20;
				$mantissa = (0x100000 | ($rknumhigh & 0x000fffff));
				$mantissalow1 = ($rknumlow & 0x80000000) >> 31;
				$mantissalow2 = ($rknumlow & 0x7fffffff);
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
			function _GetIEEE754($rknum) {
				if (($rknum & 0x02) != 0) {
						$value = $rknum >> 2;
				} else {
					//mmp
					// I got my info on IEEE754 encoding from
					// http://research.microsoft.com/~hollasch/cgindex/coding/ieeefloat.html
					// The RK format calls for using only the most significant 30 bits of the
					// 64 bit floating point value. The other 34 bits are assumed to be 0
					// So, we use the upper 30 bits of $rknum as follows...
					$sign = ($rknum & 0x80000000) >> 31;
					$exp = ($rknum & 0x7ff00000) >> 20;
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
				if ($value>=4294967294) {
					$value=-2;
				}
				return $value;
			}
		}
	// ---------- END EXCEL_READER2 ----------
	
	//set_error_handler("myerror");
		
	if($IMP_TYPE == 'COA')
	{
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

									if($hdName == 'PRJCODE')
										$PRJCODE 		= $RowData;
									elseif($hdName == 'PRJCODE_HO')
										$PRJCODE_HO 	= $RowData;
									elseif($hdName == 'Acc_ID')
										$Acc_ID 		= $RowData;
									elseif($hdName == 'Account_Class')
										$Account_Class 	= $RowData;
									elseif($hdName == 'Account_Number')
										$Account_Number = $RowData;
									elseif($hdName == 'Account_NameEn')
										$Account_NameEn = $RowData;
									elseif($hdName == 'Account_NameId')
										$Account_NameId = $RowData;
									elseif($hdName == 'Account_Category')
										$Account_Category = $RowData ?: 1;
									elseif($hdName == 'Account_Level')
										$Account_Level 	= $RowData;
									elseif($hdName == 'Acc_DirParent')
										$Acc_DirParent 	= $RowData;
									elseif($hdName == 'Acc_ParentList')
										$Acc_ParentList = $RowData;
									elseif($hdName == 'Acc_StatusLinked')
										$Acc_StatusLinked = $RowData;
									elseif($hdName == 'Acc_Enable')
										$Acc_Enable 	= $RowData ?: 1;
									elseif($hdName == 'Default_Acc')
										$Default_Acc 	= $RowData;
									elseif($hdName == 'Currency_id')
										$Currency_id 	= $RowData;
									elseif($hdName == 'Link_Report')
										$Link_Report 	= $RowData;
									elseif($hdName == 'syncPRJ')
										$syncPRJ 		= $RowData ?: $PRJCODE;
									elseif($hdName == 'isLast')
										$isLast 		= $RowData;

									$ISHEADER			= 0;
									if($isLast == 0)
										$ISHEADER		= 1;
								}

								// INSERT INTO COA
									$sqlInsCOA		= "INSERT INTO tbl_chartaccount (ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class,
															Account_Number, Account_NameEn,
															Account_NameId, Account_Category, Account_Level, Acc_DirParent, Acc_ParentList,
															Acc_StatusLinked, Acc_Enable, Company_ID, Default_Acc, Currency_id,
															Link_Report, isHO, syncPRJ, isLast)
														VALUES ('$rowCellD', '$PRJCODE', '$PRJCODE_HO', '$Acc_ID', '$Account_Class',
															'$Account_Number', '$Account_NameEn',
															'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', '$Acc_ParentList', 
															'$Acc_StatusLinked', '$Acc_Enable', '$COMPID', '$Default_Acc', '$Currency_id', 
															'$Link_Report', '$isHO', '$syncPRJ', '$isLast')";
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
	}
	else if($IMP_TYPE == 'BOQ')
	{
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
			        $barisD 	= $rowCellH-1;				// DIKURANGI HEADER
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

									if($hdName == 'JOBCODEDET')
										$JOBCODEDET 	= $RowData;
									elseif($hdName == 'JOBCODEID')
									{
										$JOBCODEID 		= $RowData;
										if($JOBCODEID == '')
											$JOBCODEID 	= $JOBCODEDET;
									}
									elseif($hdName == 'JOBPARENT')
										$JOBPARENT 		= $RowData;
									elseif($hdName == 'JOBCODEID_P')
										$JOBCODEID_P 	= $RowData;
									elseif($hdName == 'JOBCODE')
										$JOBCODE 		= $RowData;
									elseif($hdName == 'PRJCODE')
										$PRJCODE 		= $RowData;
									elseif($hdName == 'PRJPERIOD')
									{
										$PRJPERIOD 		= $RowData;
										$PRJPERIOD_P	= $PRJPERIOD;
									}
									elseif($hdName == 'JOBDESC')
										$JOBDESC 		= $RowData;
									elseif($hdName == 'ITM_GROUP')
										$ITM_GROUP 		= $RowData;
									elseif($hdName == 'GROUP_CATEG')
										$GROUP_CATEG 	= $RowData;
									elseif($hdName == 'ITM_CODE')
										$ITM_CODE 		= $RowData;
									elseif($hdName == 'ITM_UNIT')
										$ITM_UNIT 		= $RowData;
									elseif($hdName == 'ITM_VOLM')
									{
										$ITM_VOLM1 		= $RowData;
										$ITM_VOLM2 		= preg_replace('/[^A-Za-z0-9\.]/', '', $ITM_VOLM1);
										$ITM_VOLM 		= sprintf("%f", $ITM_VOLM1);
										if($ITM_VOLM == '')
											$ITM_VOLM	= 0;
									}
									elseif($hdName == 'ITM_PRICE')
									{
										$ITM_PRICE1 	= $RowData;
										$ITM_PRICE2		= preg_replace('/[^A-Za-z0-9\.]/', '', $ITM_PRICE1);
										$ITM_PRICE 		= sprintf("%f", $ITM_PRICE1);
										if($ITM_PRICE == '')
											$ITM_PRICE	= 0;
										$ITM_LASTP 		= $ITM_PRICE;
									}
									/*elseif($hdName == 'ITM_LASTP')
									{
										$ITM_LASTP1 	= $RowData;
										$ITM_LASTP 		= preg_replace('/[^A-Za-z0-9\.]/', '', $ITM_LASTP1);
										if($ITM_LASTP == '')
											$ITM_LASTP	= 0;
										
										$ITM_AVGP		= $ITM_LASTP;	
									}*/
									elseif($hdName == 'ITM_BUDG')
									{
										$ITM_BUDG1 		= $RowData;
										$ITM_BUDG2 		= preg_replace('/[^A-Za-z0-9\.]/', '', $ITM_BUDG1);
										$ITM_BUDG 		= sprintf("%f", $ITM_BUDG1);
										if($ITM_BUDG == '')
											$ITM_BUDG	= 0;
									}
									elseif($hdName == 'BOQ_VOLM')
									{
										$BOQ_VOLM1 		= $RowData;
										$BOQ_VOLM2 		= preg_replace('/[^A-Za-z0-9\.]/', '', $BOQ_VOLM1);
										$BOQ_VOLM 		= sprintf("%f", $BOQ_VOLM1);
										if($BOQ_VOLM == '')
											$BOQ_VOLM	= 0;
									}
									elseif($hdName == 'BOQ_PRICE')
									{
										$BOQ_PRICE1 	= $RowData;
										$BOQ_PRICE2		= preg_replace('/[^A-Za-z0-9\.]/', '', $BOQ_PRICE1);
										$BOQ_PRICE 		= sprintf("%f", $BOQ_PRICE1);
										if($BOQ_PRICE == '')
											$BOQ_PRICE	= 0;
									}
									elseif($hdName == 'BOQ_BUDG')
									{
										$BOQ_BUDG1 		= $RowData;
										$BOQ_BUDG2 		= preg_replace('/[^A-Za-z0-9\.]/', '', $BOQ_BUDG1);
										$BOQ_BUDG 		= sprintf("%f", $BOQ_BUDG1);
										if($BOQ_BUDG == '')
											$BOQ_BUDG	= 0;
									}
									elseif($hdName == 'BOQ_BOBOT')
									{
										$BOQ_BOBOT1		= $RowData;
										$BOQ_BOBOT2		= preg_replace('/[^A-Za-z0-9\.]/', '', $BOQ_BOBOT1);
										$BOQ_BOBOT 		= sprintf("%f", $BOQ_BOBOT1);
										if($BOQ_BOBOT == '')
											$BOQ_BOBOT	= 0;
									}
									elseif($hdName == 'ISBOBOT')
									{
										$ISBOBOT1 		= $RowData;
										$ISBOBOT2 		= preg_replace('/[^A-Za-z0-9\.]/', '', $ISBOBOT1);
										$ISBOBOT 		= sprintf("%f", $ISBOBOT1);
										if($ISBOBOT == '')
											$ISBOBOT	= 0;
									}
									elseif($hdName == 'IS_LEVEL')
										$IS_LEVEL 		= $RowData;
									elseif($hdName == 'ISLAST')
										$ISLAST 		= $RowData;
									elseif($hdName == 'Patt_Number')
										$Patt_Number 	= $RowData;
								}
								//$BOQ_BUDG 		= $BOQ_BUDG ?: 0;
								$ISHEADER		= 0;
								if($ISLAST == 0)
									$ISHEADER	= 1;
								
								// INSERT INTO BOQ
									$sqlInsBoQ		= "INSERT INTO tbl_boqlist (JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, PRJCODE_HO, ITM_CODE, 
															JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, 
															BOQ_PRICE, BOQ_JOBCOST, PRJPERIOD, PRJPERIOD_P, BOQ_BOBOT, ISBOBOT, ISHEADER, 
															ISLAST, Patt_Number)
														VALUES ('$JOBCODEID', '$JOBCODEID', '$JOBPARENT', '$JOBCODEID_P', '$PRJCODE', '$PRJCODE_HO', '$ITM_CODE', 
															'$JOBDESC','$ITM_GROUP', '$ITM_UNIT', '$IS_LEVEL', '$ITM_VOLM', '$ITM_PRICE', '$ITM_BUDG', '$BOQ_VOLM', 
															'$BOQ_PRICE', '$BOQ_BUDG', '$PRJPERIOD', '$PRJPERIOD_P', '$BOQ_BOBOT', '$ISBOBOT', '$ISHEADER', 
															'$ISLAST', '$Patt_Number')";
									$this->db->query($sqlInsBoQ);
								
								// INSERT INTO Joblist. SEHARUSNYA UNTUK JOBLIST HANYA MENYIMPAN HEADER
									/*if($ISHEADER == 1)
									{*/
										$sqlInsJL		= "INSERT INTO tbl_joblist (ORD_ID, JOBCODEID, JOBCODEIDV, JOBPARENT, JOBCODEID_P, PRJCODE, 
																PRJCODE_HO, ITM_CODE, JOBDESC, JOBGRP, JOBUNIT, JOBLEV, JOBVOLM,
																PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, PRJPERIOD, PRJPERIOD_P,
																BOQ_JOBCOST, BOQ_BOBOT, ISBOBOT, ISHEADER, ISLAST, Patt_Number)
															VALUES ($rowCellD, '$JOBCODEID', '$JOBCODEID', '$JOBPARENT', '$JOBCODEID_P', '$PRJCODE',
																'$PRJCODE_HO', '$ITM_CODE', '$JOBDESC', '$ITM_GROUP', '$ITM_UNIT', '$IS_LEVEL', '$ITM_VOLM', 
																'$ITM_PRICE', '$ITM_BUDG', '$BOQ_VOLM', '$BOQ_PRICE', '$PRJPERIOD', '$PRJPERIOD_P',
																'$BOQ_BUDG', '$BOQ_BOBOT', '$ISBOBOT', '$ISHEADER', '$ISLAST', '$Patt_Number')";
										$this->db->query($sqlInsJL);
									/*}*/
								
								// INSERT INTO JoblistDetail
									$sqlInsJLD		= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
															JOBCODE, PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC,
															ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM, ITM_PRICE, ITM_LASTP, 
															PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
															ISLAST, Patt_Number)
														VALUES ($rowCellD, '$JOBCODEDET', '$JOBCODEID', '$JOBPARENT', '$JOBCODEID_P',
															'$JOBCODE', '$PRJCODE', '$PRJCODE_HO', '$ITM_CODE', '$JOBDESC',
															'$ITM_GROUP','$GROUP_CATEG', '$ITM_UNIT', '$ITM_VOLM', '$ITM_PRICE', '$ITM_LASTP', 
															'$PRJPERIOD', '$PRJPERIOD_P', '$ITM_BUDG', '$BOQ_VOLM', '$BOQ_PRICE', '$BOQ_BUDG', '$IS_LEVEL', 
															'$ISLAST', '$Patt_Number')";
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
					$sqlUpdBoQ		= "UPDATE tbl_boq_hist SET BOQH_STAT = 2 WHERE BOQH_CODE = '$BOQH_CODEX'";
					$this->db->query($sqlUpdBoQ);

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
			// PROSEDURE LAMA
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
										$PRJPERIOD 		= $RowData;
									elseif($hdName == 'ITM_CODE')
										$ITM_CODE 	= $RowData;
									elseif($hdName == 'ITM_GROUP')
										$ITM_GROUP 		= $RowData;
									elseif($hdName == 'ITM_CATEG')
										$ITM_CATEG 		= $RowData;
									/*elseif($hdName == 'ITM_CLASS')
										$ITM_CLASS 		= $RowData;*/
									elseif($hdName == 'ITM_NAME')
										$ITM_NAME 		= addslashes($RowData);
									elseif($hdName == 'ITM_DESC')
										$ITM_DESC 		= addslashes($RowData);
									elseif($hdName == 'ITM_TYPE')
										$ITM_TYPE 		= addslashes($RowData);
									elseif($hdName == 'ITM_UNIT')
										$ITM_UNIT 		= addslashes($RowData);
									elseif($hdName == 'ITM_VOLMBG')
									{
										$ITM_VOLMBG1 	= preg_replace('/[^A-Za-z0-9\.]/', '', $RowData);
										$ITM_VOLMBG 	= sprintf("%f", $ITM_VOLMBG1);
										if($ITM_VOLMBG == '')
											$ITM_VOLMBG	= 0;
									}
									elseif($hdName == 'ITM_VOLMBGR')
									{
										$ITM_VOLMBGR1 	= preg_replace('/[^A-Za-z0-9\.]/', '', $RowData);
										$ITM_VOLMBGR	= sprintf("%f", $ITM_VOLMBGR1);
										if($ITM_VOLMBGR == '')
											$ITM_VOLMBGR= 0;
									}
									elseif($hdName == 'ITM_VOLM')
									{
										$ITM_VOLM1 		= preg_replace('/[^A-Za-z0-9\.]/', '', $RowData);
										$ITM_VOLM		= sprintf("%f", $ITM_VOLM1);
										if($ITM_VOLM == '')
											$ITM_VOLM 	= 0;
									}
									elseif($hdName == 'ITM_PRICE')
									{
										$ITM_PRICE1 	= preg_replace('/[^A-Za-z0-9\.]/', '', $RowData);
										$ITM_PRICE 		= sprintf("%f", $ITM_PRICE1);
										if($ITM_PRICE == '')
											$ITM_PRICE 	= 0;
									}
									elseif($hdName == 'ITM_LASTP')
									{
										$ITM_LASTP1 	= preg_replace('/[^A-Za-z0-9\.]/', '', $RowData);
										$ITM_LASTP 		= sprintf("%f", $ITM_LASTP1);
										if($ITM_LASTP == '')
											$ITM_LASTP 	= 0;
									}
									elseif($hdName == 'ACC_ID')
										$ACC_ID 		= $RowData;
									elseif($hdName == 'ACC_ID_UM')
										$ACC_ID_UM 	= $RowData;
									elseif($hdName == 'ACC_ID_SAL')
										$ACC_ID_SAL 	= $RowData;
									elseif($hdName == 'STATUS')
										$STATUS 	= $RowData;
									elseif($hdName == 'ISMTRL')
										$ISMTRL 	= $RowData;
									elseif($hdName == 'ISRM')
										$ISRM 	= $RowData;
									elseif($hdName == 'ISWIP')
										$ISWIP 	= $RowData;
									elseif($hdName == 'ISFG')
										$ISFG 	= $RowData;
									elseif($hdName == 'ISRIB')
										$ISRIB 	= $RowData;
									elseif($hdName == 'NEEDQRC')
										$NEEDQRC 	= $RowData;
									elseif($hdName == 'ITM_LR')
										$ITM_LR 	= $RowData;
								}

								if($PRJPERIOD == '')
									$PRJPERIOD 	= $PRJCODE;
								
								$ITM_CURRENCY 	= 'IDR';
								$UMCODE			= $ITM_UNIT;
								$ITM_REMQTY		= $ITM_VOLM;
								$ITM_TOTALP		= $ITM_VOLMBG * $ITM_PRICE;
								if($ITM_TOTALP == '')
									$ITM_TOTALP	= 0;

								//$ITM_LASTP	= $ITM_PRICE;
								$ITM_AVGP		= $ITM_PRICE;
								$BOQ_ITM_VOLM	= $ITM_VOLMBG;
								$BOQ_ITM_PRICE	= $ITM_PRICE;
								$BOQ_ITM_TOTALP	= $ITM_TOTALP;

								if($resJLC > 0)
								{
									$sqlBudg	= "SELECT sum(B.ITM_VOLM) AS TOTBUDG FROM tbl_joblist_detail B
													WHERE B.ITM_CODE = '$ITM_CODE' AND B.PRJCODE = '$PRJCODE'";
									$resBudg 	= $this->db->query($sqlBudg)->result();
									foreach($resBudg as $rowBudg) :
										$TOTBUDG = $rowBudg->TOTBUDG;
									endforeach;
									$ITM_VOLMBG = $TOTBUDG ?: 0;
								}
			
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

	        	$sql001	= "DELETE FROM tbl_doc_concl WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql001);

	        	$sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AMD_NUM 
	                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_amd_detail WHERE AMD_NUM IN (SELECT AMD_NUM 
	                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_apartement			-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASEXP_NUM 
	                            FROM tbl_asset_exph WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AM_CODE 
	                            FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASTSF_NUM 
	                            FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE')";
	           //__ $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_asset_tsfd WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            // tbl_asset_type			-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AU_CODE 
	                            FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_asset_usagedet WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(7)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AUR_CODE 
	                            FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_assetexp_concl WHERE RASTXP_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RASSET_CODE 
	                            FROM tbl_assetexp_header WHERE RASSET_PROJECT = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT BOM_NUM 
	                            FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
	            
	            $sql002	= "DELETE FROM tbl_bom_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	            $sql004	= "DELETE FROM tbl_bom_stfdetail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql004);
	            $sql004	= "DELETE FROM tbl_bom_stfdetail_qrc WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql004);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(9)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql003	= "DELETE FROM tbl_boq_hist WHERE BOQH_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlistm WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
	                            FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
	                            FROM tbl_br_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT CCAL_NUM 
	                            FROM tbl_ccal_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FPA_NUM 
	                        FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FU_CODE 
	                            FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            
	            $sql002	= "DELETE FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            // tbl_genfileupload, tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,					-- No Reset
	            // tbl_inv_detail, tbl_inv_header,  														-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT IR_NUM 
	                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);

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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ADJ_NUM 
	                            FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            /*$sql002	= "DELETE FROM tbl_item_adjd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);*/
	            $sql003	= "DELETE FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_colld WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_collh WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(27)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	            
	            $sql003	= "DELETE FROM tbl_item_cutd WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_cuth WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);
	        
	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ITMTSF_NUM 
	                            FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_item_tsfd WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_item_uphist WHERE ITMH_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql001);

	            $sql001	= "DELETE FROM tbl_item_whqty WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql001);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(30)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_itemcategory, tbl_itemgroup				-- No Reset

	            $sql003	= "DELETE FROM tbl_itemhistory WHERE proj_Code = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOC_CODE 
	                            FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
	            
	            $sql002	= "DELETE FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	        
	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JO_NUM 
	                            FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
	                            FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
	            
	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
	                            FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
	            
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
	                $sql001	= "DELETE FROM tbl_journalheader WHERE proj_Code = '$PRJCODE'";
	                $this->db->query($sql001);
	                
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MCH_CODE 
	                            FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MR_NUM 
	                            FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_mr_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_news_detail, tbl_news_header		-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OFF_NUM 
	                            FROM tbl_offering_h WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OPNH_NUM 
	                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_opn_detail WHERE OPNH_NUM IN (SELECT OPNH_NUM 
	                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_opn_inv, tbl_opn_invdet, 		-- Not Used
	            // tbl_outpay_report, tbl_overhead, 	-- Not Used
	            // tbl_owner, tbl_owner_img,			-- No Reset
	            // tbl_payterm							-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT INV_NUM 
	                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_pinv_detail WHERE INV_NUM IN (SELECT INV_NUM 
	                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PO_NUM 
	                            FROM tbl_po_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PR_NUM 
	                            FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PRJP_NUM 
	                            FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_project_progress_det WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_project_recom, tbl_project_recom_hist,								-- Not Used
	            // tbl_projhistory, tbl_projinv_detail										-- Not Used

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PINV_CODE 
	                            FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT QRC_NUM 
	                            FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_qty_coll WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            // tbl_reservation,															-- Not Used
	        
	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RET_NUM 
	                            FROM tbl_ret_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SIC_CODE 
	                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_sicertificatedet WHERE SIC_CODE IN (SELECT SIC_CODE 
	                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_sinv_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SINV_NUM 
	                            FROM tbl_sinv_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SN_NUM 
	                            FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_sn_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SO_NUM 
	                            FROM tbl_so_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SR_NUM 
	                            FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_sr_detail WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_sr_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            //__$this->db->query($sql002);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT STF_NUM 
	                            FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE')";
	            //__$this->db->query($sql001);
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

				$percent = intval(57)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_translate, tbl_trashsys,

	            $sql003	= "DELETE FROM tbl_ttk WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT TTK_NUM 
	                            FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT UM_NUM 
	                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_um_detail WHERE UM_NUM IN (SELECT UM_NUM 
	                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_um_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_unittype,															-- No Reset
	            // tbl_uploadbca, tbl_uploadbca_data, tbl_uploadreceipt, tbl_uploadttkest,	-- Not Used
	            // tbl_userdoctype,															-- Not Used
	            // tbl_vehicle, tbl_vendcat, tbl_warehouse, tbl_wip, 						-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
	                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_wo_detail WHERE WO_NUM IN (SELECT WO_NUM 
	                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_wo_print WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
	                            FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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
							$percent = intval(70)."%";
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
                        elseif($rata2 == 75)
                        {
							$percent = intval(90)."%";
							echo '<script>
						    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

						    ob_flush();
						    flush();
                        }
                        elseif($rata2 > 98)
                        {
							$percent = intval(98)."%";
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
		            $sql001	= "TRUNCATE tbl_doc_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_alert_list"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_approve_hist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_amd_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_amd_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_apartement"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_exph"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_expd"; $this->db->query($sql001);
		        	// tbl_asset_group 			-- No Reset
		            $sql001	= "TRUNCATE tbl_asset_joblist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_list"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_maintendet"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(1)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_asset_mainten"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_prod"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_rcost"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_rjob"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_tsfh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_tsfd"; $this->db->query($sql001);
		            // tbl_asset_type			-- No Reset
		            $sql001	= "TRUNCATE tbl_asset_usagedet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_usage"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_usagereq"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_assetexp_concl"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(10)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_assetexp_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_assetexp_detail"; $this->db->query($sql001);
		            // tbl_auth 				-- Not Used
		            $sql001	= "TRUNCATE tbl_balances"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bgheader"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bobot"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bom_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bom_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bom_stfdetail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bom_stfdetail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_boq_hist"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(15)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_boqlist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_boqlistm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bp_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bp_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_br_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_br_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cashbank"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cb_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cb_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ccal_detail"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(20)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_ccal_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ccoa"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cf_report_in"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cf_report_out"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_chartaccount"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_chartaccountm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_chat"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_chat_detail"; $this->db->query($sql001);
		            // tbl_chartcategory,			-- No Reset
		            $sql001	= "TRUNCATE tbl_coa_uphist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_coadetail"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(25)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            // tbl_cssjs, tbl_currate, tbl_currconv, tbl_currency, 			-- No Reset
		            // tbl_custcat,													-- No Reset
		            $sql001	= "TRUNCATE tbl_customer"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_customer_img"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dash_data"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dash_hr"; $this->db->query($sql001);
		            // , tbl_dash_sett, , tbl_dash_sett_hr, tbl_dash_sett_hr_emp,	-- No Reset
		            //$sql001	= "TRUNCATE tbl_dash_sett_emp"; $this->db->query($sql001);
		            $sql003	= "DELETE FROM tbl_dash_sett_emp WHERE EMP_ID != '$EMPID'";
	            	$this->db->query($sql003);
		            $sql001	= "TRUNCATE tbl_dash_transac"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dash_transac_all"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_decreaseinvoice"; $this->db->query($sql001);
		            // tbl_department, tbl_doc_cc, tbl_docpattern,					-- No Reset
		            $sql001	= "TRUNCATE tbl_docstepapp"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_docstepapp_det"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(27)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_document"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dp_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dp_report"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dp_report_in"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dp_report_out"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dpr_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_driver"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dwlhist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_emp_vers"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_employee_acc"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(35)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            // tbl_employee, tbl_employee_age, 								-- No Reset
		            $sql001	= "TRUNCATE tbl_employee_appauth"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_employee_docauth"; $this->db->query($sql001);
		            // tbl_employee_circle, tbl_employee_gol,						-- No Reset
		            // tbl_employee_img,											-- No Reset
					$sql002	= "DELETE FROM tbl_employee_proj WHERE proj_Code NOT IN (SELECT PRJCODE FROM tbl_project WHERE PRJTYPE = 1)";
		            $this->db->query($sql002);
		            $sql001	= "TRUNCATE tbl_financial_monitor"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_financial_track"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_fpa_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_fpa_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_fuel_usage"; $this->db->query($sql001);
		            // tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,		-- No Reset
		            $sql001	= "TRUNCATE tbl_genfileupload"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_inv_detail"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(40)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_inv_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ir_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ir_detail_trash"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ir_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item"; $this->db->query($sql001);
		            //$sql001	= "TRUNCATE tbl_item_adjd"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_adjh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_colld"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_collh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_cutd"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(46)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_item_cuth"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_tsfd"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_tsfh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_uphist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_whqty"; $this->db->query($sql001);
		            // tbl_itemcategory, tbl_itemgroup								-- No Reset
		            $sql001	= "TRUNCATE tbl_itemhistory"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_detail_tmp3"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_header"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(50)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_jo_stfdetail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_stfdetail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_joblist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_joblist_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_joblist_detailm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_joblistm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jobopname"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_journaldetail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_journalheader"; $this->db->query($sql001);
		            // tbl_language, , tbl_link_account,							-- No Reset
		            $sql001	= "TRUNCATE tbl_lastsync"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(55)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_login_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_login_hist"; $this->db->query($sql001);
		            // tbl_machine, tbl_mail_dept,									-- No Reset
		            $sql001	= "TRUNCATE tbl_machine_itm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mail_dept_emp"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mail_detail"; $this->db->query($sql001);
		            // tbl_mail_type,												-- No Reset
		            $sql001	= "TRUNCATE tbl_mail_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailbox"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailbox_reply"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailbox_send"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailbox_trash"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(57)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_mailbox_trash_ext"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailgroup_detail"; $this->db->query($sql001);
		            // tbl_mailgroup_header,										-- No Reset
		            $sql001	= "TRUNCATE tbl_major_app"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_master_item"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mc_balance"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mc_conc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mc_plan"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mcg_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mcg_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mcheader"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(60)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_meeting_room"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mr_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mr_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_news_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_news_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_offering_d"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_offering_h"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_opn_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_opn_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_opn_inv"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(65)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_opn_invdet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_outpay_report"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_overhead"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_owner"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_owner_img"; $this->db->query($sql001);
		            // tbl_payterm													-- No Reset
		            $sql001	= "TRUNCATE tbl_pinv_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_pinv_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_po_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_po_header"; $this->db->query($sql001);
		            // tbl_position, tbl_position_func, tbl_position_str, 			-- No Reset
		            $sql001	= "TRUNCATE tbl_pr_detail"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(68)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_pr_detail_trash"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_pr_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_pr_header_trash"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_printdoc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_printdoc_wo"; $this->db->query($sql001);
		            // tbl_prodstep, 												-- No Reset
		            $sql001	= "TRUNCATE tbl_profitloss"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_profloss_man"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_progg_uphist"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(70)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql002	= "DELETE FROM tbl_project WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $sql001	= "TRUNCATE tbl_project_active"; $this->db->query($sql001);
		            $sql002	= "DELETE FROM tbl_project_budg WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $sql002	= "DELETE FROM tbl_project_budgm WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $sql001	= "TRUNCATE tbl_project_progress"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_project_progress_det"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_project_recom"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_project_recom_hist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projhistory"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projinv_detail"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(75)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_projinv_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projinv_realh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projplan_material"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projprogres"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_purch_report"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_qhsedoc_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_qrc_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_qty_coll"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_reservation"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ret_detail"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(80)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_ret_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskcategory"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskdescdet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskidentif"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskimpactdet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskpolicydet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_rtflista"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_rtflistb"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_schedule"; $this->db->query($sql001);

	            	// tbl_section,													-- Not Used

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(84)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            //$sql001	= "TRUNCATE tbl_sementara"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sicertificate"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sicertificatedet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_siheader"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sinv_detail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sinv_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sinv_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sn_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sn_detail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sn_header"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(90)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_so_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_so_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_so_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sopn_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sopn_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sopn_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_spkprint"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sr_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sr_detail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sr_header"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(92)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_stf_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_stf_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_stf_mtrused"; $this->db->query($sql001);
		            // tbl_supplier,												-- No Reset
		            $sql001	= "TRUNCATE tbl_task_request"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_task_request_detail"; $this->db->query($sql001);
		            // tbl_tax, tbl_tax_la, tbl_tax_ppn, tbl_tax_ppn_copy,			-- No Reset
		            $sql001	= "TRUNCATE tbl_trackcreater"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_trail_tracker"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_trans_count"; $this->db->query($sql001);
		            // tbl_translate, tbl_trashsys,
		            $sql001	= "TRUNCATE tbl_ttk"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ttk_detail"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(96)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_ttk_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ttk_print"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ttkestinvoice"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_um_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_um_header"; $this->db->query($sql001);
		            // tbl_unittype,												-- No Reset
		            $sql001	= "TRUNCATE tbl_uploadbca"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_uploadbca_data"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_uploadreceipt"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_uploadttkest"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_userdoctype"; $this->db->query($sql001);

		            // tbl_vehicle, tbl_vendcat,		 							-- No Reset
		            $sql001	= "TRUNCATE tbl_warehouse"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_wip"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_wo_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_wo_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_wo_print"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_woreq_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_woreq_header"; $this->db->query($sql001);

		        	// tglobalsetting, tusermenu

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(100)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

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

	        	$sql001	= "DELETE FROM tbl_doc_concl WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql001);

	        	$sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AMD_NUM 
	                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_amd_detail WHERE AMD_NUM IN (SELECT AMD_NUM 
	                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_apartement			-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASEXP_NUM 
	                            FROM tbl_asset_exph WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AM_CODE 
	                            FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASTSF_NUM 
	                            FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_asset_tsfd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_asset_type			-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AU_CODE 
	                            FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_asset_usagedet WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(7)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AUR_CODE 
	                            FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_assetexp_concl WHERE RASTXP_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RASSET_CODE 
	                            FROM tbl_assetexp_header WHERE RASSET_PROJECT = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT BOM_NUM 
	                            FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            
	            $sql002	= "DELETE FROM tbl_bom_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql004	= "DELETE FROM tbl_bom_stfdetail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql004);
	            $sql004	= "DELETE FROM tbl_bom_stfdetail_qrc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql004);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(9)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            $sql003	= "DELETE FROM tbl_boq_hist WHERE BOQH_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_boqlistm WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
	                            FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
	                            FROM tbl_br_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT CCAL_NUM 
	                            FROM tbl_ccal_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FPA_NUM 
	                        FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FU_CODE 
	                            FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            
	            $sql002	= "DELETE FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            // tbl_genfileupload, tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,					-- No Reset
	            // tbl_inv_detail, tbl_inv_header,  														-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT IR_NUM 
	                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);

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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ADJ_NUM 
	                            FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            /*$sql002	= "DELETE FROM tbl_item_adjd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);*/
	            $sql003	= "DELETE FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_colld WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_collh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(27)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();
	            
	            $sql003	= "DELETE FROM tbl_item_cutd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            
	            $sql003	= "DELETE FROM tbl_item_cuth WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	        
	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ITMTSF_NUM 
	                            FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_item_tsfd WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_item_uphist WHERE ITMH_PRJCODE = '$PRJCODE'";
	            $this->db->query($sql001);

	            $sql001	= "DELETE FROM tbl_item_whqty WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql001);

	             //sleep(1); // Here call your time taking function like sending bulk sms etc.
				$percent = intval(30)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_itemcategory, tbl_itemgroup				-- No Reset

	            $sql003	= "DELETE FROM tbl_itemhistory WHERE proj_Code = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOC_CODE 
	                            FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            
	            $sql002	= "DELETE FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	        
	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JO_NUM 
	                            FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
	                            FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            
	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
	                            FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            
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
	                $sql001	= "DELETE FROM tbl_journalheader WHERE proj_Code = '$PRJCODE'";
	                $this->db->query($sql001);
	                
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MCH_CODE 
	                            FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MR_NUM 
	                            FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_mr_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_news_detail, tbl_news_header		-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OFF_NUM 
	                            FROM tbl_offering_h WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OPNH_NUM 
	                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_opn_detail WHERE OPNH_NUM IN (SELECT OPNH_NUM 
	                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_opn_inv, tbl_opn_invdet, 		-- Not Used
	            // tbl_outpay_report, tbl_overhead, 	-- Not Used
	            // tbl_owner, tbl_owner_img,			-- No Reset
	            // tbl_payterm							-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT INV_NUM 
	                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_pinv_detail WHERE INV_NUM IN (SELECT INV_NUM 
	                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PO_NUM 
	                            FROM tbl_po_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PR_NUM 
	                            FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PRJP_NUM 
	                            FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_project_progress_det WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_project_recom, tbl_project_recom_hist,								-- Not Used
	            // tbl_projhistory, tbl_projinv_detail										-- Not Used

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PINV_CODE 
	                            FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT QRC_NUM 
	                            FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql003	= "DELETE FROM tbl_qty_coll WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_reservation,															-- Not Used
	        
	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RET_NUM 
	                            FROM tbl_ret_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SIC_CODE 
	                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_sicertificatedet WHERE SIC_CODE IN (SELECT SIC_CODE 
	                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql003	= "DELETE FROM tbl_sinv_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SINV_NUM 
	                            FROM tbl_sinv_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SN_NUM 
	                            FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_sn_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SO_NUM 
	                            FROM tbl_so_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SR_NUM 
	                            FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_sr_detail WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_sr_detail_qrc WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql002);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT STF_NUM 
	                            FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

				$percent = intval(57)."%";
				echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

			    ob_flush();
			    flush();

	            // tbl_translate, tbl_trashsys,

	            $sql003	= "DELETE FROM tbl_ttk WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT TTK_NUM 
	                            FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT UM_NUM 
	                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_um_detail WHERE UM_NUM IN (SELECT UM_NUM 
	                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_um_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            // tbl_unittype,															-- No Reset
	            // tbl_uploadbca, tbl_uploadbca_data, tbl_uploadreceipt, tbl_uploadttkest,	-- Not Used
	            // tbl_userdoctype,															-- Not Used
	            // tbl_vehicle, tbl_vendcat, tbl_warehouse, tbl_wip, 						-- No Reset

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
	                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_wo_detail WHERE WO_NUM IN (SELECT WO_NUM 
	                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql002);
	            $sql003	= "DELETE FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);
	            $sql003	= "DELETE FROM tbl_wo_print WHERE PRJCODE = '$PRJCODE'";
	            $this->db->query($sql003);

	            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
	                            FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE')";
	            $this->db->query($sql001);
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
								$percent = intval(70)."%";
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
	                        elseif($rata2 == 75)
	                        {
								$percent = intval(90)."%";
								echo '<script>
							    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' Process completed</span></div>";</script>';

							    ob_flush();
							    flush();
	                        }
	                        elseif($rata2 > 98)
	                        {
								$percent = intval(98)."%";
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
		            $sql001	= "TRUNCATE tbl_doc_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_alert_list"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_approve_hist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_amd_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_amd_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_apartement"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_exph"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_expd"; $this->db->query($sql001);
		        	// tbl_asset_group 			-- No Reset
		            $sql001	= "TRUNCATE tbl_asset_joblist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_list"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_maintendet"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(1)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_asset_mainten"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_prod"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_rcost"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_rjob"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_tsfh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_tsfd"; $this->db->query($sql001);
		            // tbl_asset_type			-- No Reset
		            $sql001	= "TRUNCATE tbl_asset_usagedet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_usage"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_asset_usagereq"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_assetexp_concl"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(10)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_assetexp_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_assetexp_detail"; $this->db->query($sql001);
		            // tbl_auth 				-- Not Used
		            $sql001	= "TRUNCATE tbl_balances"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bgheader"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bobot"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bom_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bom_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bom_stfdetail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bom_stfdetail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_boq_hist"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(15)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_boqlist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_boqlistm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bp_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_bp_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_br_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_br_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cashbank"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cb_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cb_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ccal_detail"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(20)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_ccal_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ccoa"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cf_report_in"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_cf_report_out"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_chartaccount"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_chartaccountm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_chat"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_chat_detail"; $this->db->query($sql001);
		            // tbl_chartcategory,			-- No Reset
		            $sql001	= "TRUNCATE tbl_coa_uphist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_coadetail"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				    $percent = intval(25)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            // tbl_cssjs, tbl_currate, tbl_currconv, tbl_currency, 			-- No Reset
		            // tbl_custcat,													-- No Reset
		            $sql001	= "TRUNCATE tbl_customer"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_customer_img"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dash_data"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dash_hr"; $this->db->query($sql001);
		            // , tbl_dash_sett, , tbl_dash_sett_hr, tbl_dash_sett_hr_emp,	-- No Reset
		            //$sql001	= "TRUNCATE tbl_dash_sett_emp"; $this->db->query($sql001);
		            $sql003	= "DELETE FROM tbl_dash_sett_emp WHERE EMP_ID != '$EMPID'";
	            	$this->db->query($sql003);
		            $sql001	= "TRUNCATE tbl_dash_transac"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dash_transac_all"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_decreaseinvoice"; $this->db->query($sql001);
		            // tbl_department, tbl_doc_cc, tbl_docpattern,					-- No Reset
		            $sql001	= "TRUNCATE tbl_docstepapp"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_docstepapp_det"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(27)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_document"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_doc_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dp_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dp_report"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dp_report_in"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dp_report_out"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dpr_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_driver"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_dwlhist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_emp_vers"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_employee_acc"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(30)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            // tbl_employee, tbl_employee_age, 								-- No Reset
		            $sql001	= "TRUNCATE tbl_employee_appauth"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_employee_docauth"; $this->db->query($sql001);
		            // tbl_employee_circle, tbl_employee_gol,						-- No Reset
		            // tbl_employee_img,											-- No Reset
					$sql002	= "DELETE FROM tbl_employee_proj WHERE proj_Code NOT IN (SELECT PRJCODE FROM tbl_project WHERE PRJTYPE = 1)";
		            $this->db->query($sql002);
		            $sql001	= "TRUNCATE tbl_financial_monitor"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_financial_track"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_fpa_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_fpa_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_fuel_usage"; $this->db->query($sql001);
		            // tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,		-- No Reset
		            $sql001	= "TRUNCATE tbl_genfileupload"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_inv_detail"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				    $percent = intval(40)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_inv_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ir_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ir_detail_trash"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ir_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item"; $this->db->query($sql001);
		            //$sql001	= "TRUNCATE tbl_item_adjd"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_adjh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_colld"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_collh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_cutd"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(46)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_item_cuth"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_tsfd"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_tsfh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_uphist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_item_whqty"; $this->db->query($sql001);
		            // tbl_itemcategory, tbl_itemgroup								-- No Reset
		            $sql001	= "TRUNCATE tbl_itemhistory"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_detail_tmp3"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_header"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(50)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_jo_stfdetail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jo_stfdetail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_joblist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_joblist_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_joblist_detailm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_joblistm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_jobopname"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_journaldetail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_journalheader"; $this->db->query($sql001);
		            // tbl_language, , tbl_link_account,							-- No Reset
		            $sql001	= "TRUNCATE tbl_lastsync"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(55)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_login_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_login_hist"; $this->db->query($sql001);
		            // tbl_machine, tbl_mail_dept,									-- No Reset
		            $sql001	= "TRUNCATE tbl_machine_itm"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mail_dept_emp"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mail_detail"; $this->db->query($sql001);
		            // tbl_mail_type,												-- No Reset
		            $sql001	= "TRUNCATE tbl_mail_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailbox"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailbox_reply"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailbox_send"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailbox_trash"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(55)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_mailbox_trash_ext"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mailgroup_detail"; $this->db->query($sql001);
		            // tbl_mailgroup_header,										-- No Reset
		            $sql001	= "TRUNCATE tbl_major_app"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_master_item"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mc_balance"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mc_conc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mc_plan"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mcg_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mcg_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mcheader"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(60)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_meeting_room"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mr_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_mr_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_news_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_news_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_offering_d"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_offering_h"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_opn_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_opn_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_opn_inv"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(65)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_opn_invdet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_outpay_report"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_overhead"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_owner"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_owner_img"; $this->db->query($sql001);
		            // tbl_payterm													-- No Reset
		            $sql001	= "TRUNCATE tbl_pinv_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_pinv_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_po_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_po_header"; $this->db->query($sql001);
		            // tbl_position, tbl_position_func, tbl_position_str, 			-- No Reset
		            $sql001	= "TRUNCATE tbl_pr_detail"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
				    $percent = intval(70)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_pr_detail_trash"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_pr_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_pr_header_trash"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_printdoc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_printdoc_wo"; $this->db->query($sql001);
		            // tbl_prodstep, 												-- No Reset
		            $sql001	= "TRUNCATE tbl_profitloss"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_profloss_man"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_progg_uphist"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(70)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql002	= "DELETE FROM tbl_project WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $sql001	= "TRUNCATE tbl_project_active"; $this->db->query($sql001);
		            $sql002	= "DELETE FROM tbl_project_budg WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $sql002	= "DELETE FROM tbl_project_budgm WHERE PRJTYPE != 1";
		            $this->db->query($sql002);
		            $sql001	= "TRUNCATE tbl_project_progress"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_project_progress_det"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_project_recom"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_project_recom_hist"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projhistory"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projinv_detail"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(75)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_projinv_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projinv_realh"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projplan_material"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_projprogres"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_purch_report"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_qhsedoc_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_qrc_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_qty_coll"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_reservation"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ret_detail"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					/*$percent = intval(80)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();*/

		            $sql001	= "TRUNCATE tbl_ret_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskcategory"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskdescdet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskidentif"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskimpactdet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_riskpolicydet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_rtflista"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_rtflistb"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_schedule"; $this->db->query($sql001);

	            	// tbl_section,													-- Not Used

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(85)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            //$sql001	= "TRUNCATE tbl_sementara"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sicertificate"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sicertificatedet"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_siheader"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sinv_detail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sinv_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sinv_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sn_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sn_detail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sn_header"; $this->db->query($sql001);

		            //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(89)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_so_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_so_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_so_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sopn_concl"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sopn_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sopn_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_spkprint"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sr_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sr_detail_qrc"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_sr_header"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(92)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_stf_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_stf_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_stf_mtrused"; $this->db->query($sql001);
		            // tbl_supplier,												-- No Reset
		            $sql001	= "TRUNCATE tbl_task_request"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_task_request_detail"; $this->db->query($sql001);
		            // tbl_tax, tbl_tax_la, tbl_tax_ppn, tbl_tax_ppn_copy,			-- No Reset
		            $sql001	= "TRUNCATE tbl_trackcreater"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_trail_tracker"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_trans_count"; $this->db->query($sql001);
		            // tbl_translate, tbl_trashsys,
		            $sql001	= "TRUNCATE tbl_ttk"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ttk_detail"; $this->db->query($sql001);

		             //sleep(1); // Here call your time taking function like sending bulk sms etc.
					$percent = intval(96)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' is processed</span></div>";</script>';

				    ob_flush();
				    flush();

		            $sql001	= "TRUNCATE tbl_ttk_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ttk_print"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_ttkestinvoice"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_um_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_um_header"; $this->db->query($sql001);
		            // tbl_unittype,												-- No Reset
		            $sql001	= "TRUNCATE tbl_uploadbca"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_uploadbca_data"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_uploadreceipt"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_uploadttkest"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_userdoctype"; $this->db->query($sql001);

		            // tbl_vehicle, tbl_vendcat,		 							-- No Reset
		            $sql001	= "TRUNCATE tbl_warehouse"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_wip"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_wo_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_wo_header"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_wo_print"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_woreq_detail"; $this->db->query($sql001);
		            $sql001	= "TRUNCATE tbl_woreq_header"; $this->db->query($sql001);

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
		
		// START : PROCEDUR - RESET ORDER
			// 1.	CEK POSISI LEVEL 0, DARI KODE INDUK YANG TIDAK ADA DI DALAM DAFTAR COA
					$sql_01	= "SELECT A.Account_Number AS AccNumb FROM tbl_chartaccount A
								WHERE A.Acc_DirParent NOT IN (SELECT B.Account_Number FROM tbl_chartaccount B WHERE B.PRJCODE = '$SYNC_PRJ')
									AND A.PRJCODE = '$SYNC_PRJ' AND A.Account_Level > 1";
					$res_01 = $this->db->query($sql_01)->result();
					foreach($res_01 as $row_01) :
						$AccNumb	= $row_01->AccNumb;

						$sql_01A	= "UPDATE tbl_chartaccount SET ORD_ID = 9999999, Account_Level = 0
										WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01A);
						// 9999999 = damage account
					endforeach;

					$sql_01B	= "UPDATE tbl_chartaccount SET ORD_ID = 9999999, Account_Level = 0
									WHERE Account_Number = Acc_DirParent AND PRJCODE = '$SYNC_PRJ'";
					$this->db->query($sql_01B);
					// 9999999 = damage account

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
					$sql_03 	= "UPDATE tbl_chartaccount SET ORD_ID = 0 WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != '9999999'";
					$this->db->query($sql_03);

			// 4.	TAMBAHAN
					$sql_01X= "SELECT A.Account_Number AS AccNumb FROM tbl_chartaccount A
								WHERE A.Acc_DirParent NOT IN (SELECT B.Account_Number FROM tbl_chartaccount B WHERE B.PRJCODE = '$SYNC_PRJ')
									AND A.PRJCODE = '$SYNC_PRJ' AND A.Acc_DirParent != '' AND ORD_ID != '9999999'";
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

						$sql_01AY	= "UPDATE tbl_chartaccount SET ORD_ID = 9999999 WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01AY);
					endforeach;

					$percent = intval(0)."%";
					echo '<script>
				    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';
					echo '<script>
				    parent.document.getElementById("progressbarXX2").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

				    ob_flush();
				    flush();
		// END : PROCEDUR - RESET ORDER

		// START : PROSES PROCEDUR - RESET ORDER
			$percentN 	= 8;
			$ORD_ID		= 0;
			$sql_04		= "SELECT Account_Number, isLast FROM tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Account_Level = 0
								AND ORD_ID != '9999999'
							ORDER BY ORD_ID, Account_Number, Acc_ID";
			$res_04 	= $this->db->query($sql_04)->result();
			foreach($res_04 as $row_04) :
				$ORD_ID			= $ORD_ID+1;
				$Account_N03	= $row_04->Account_Number; // 1
				$isLast03		= $row_04->isLast;
				echo "$ORD_ID = $Account_N03<br>";

				$sql_04RO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID WHERE Account_Number = '$Account_N03'
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
						echo "$ORD_ID = $Account_N3A<br>";
						
						$sql_04ARO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 1
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
								
								$sql_04BRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 2
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
										
										$sql_04CRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 3
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
												
												$sql_04DRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 4
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
														
														$sql_04ERO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 5
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
																
																$sql_04FRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 6
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
																		echo "$ORD_ID = $Account_N3G<br>";
																		
																		$sql_04GRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 7
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

			$sql_05	= "SELECT Account_Number, isLast FROM tbl_chartaccount WHERE ORD_ID = '9999999' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";
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
			
			$sql1	= "DELETE FROM tbl_joblist WHERE JOBCODEID = ''";
			$this->db->query($sql1);
			
			$sql1	= "DELETE FROM tbl_joblist_detail WHERE JOBCODEID = ''";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_boqlist SET ORD_ID = 9999999, BOQ_STAT = 0 WHERE JOBPARENT = '' AND JOBLEV != 1";
			$this->db->query($sql2);
			
			$sql2	= "UPDATE tbl_joblist SET ORD_ID = 9999999, WBS_STAT = 0 WHERE JOBPARENT = '' AND JOBLEV != 1";
			$this->db->query($sql2);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ORD_ID = 9999999, WBSD_STAT = 0 WHERE JOBPARENT = '' AND IS_LEVEL != 1";
			$this->db->query($sql2);

			$percent = intval(0)."%";
			echo '<script>
		    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

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

				    ob_flush();
				    flush();

				    // LEVEL _02 PER HEADER
				    $sql_02	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist WHERE JOBPARENT = '$JOBC_01' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
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

									    ob_flush();
									    flush();

									    // LEVEL _07 PER HEADER
									    $sql_07	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_06' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
										$res_07 = $this->db->query($sql_07)->result();
										foreach($res_07 as $row_07) :
											$ORR_ID		= $ORR_ID + 1;
											$JOBC_07	= $row_07->JOBCODEID;
											$ORD_07		= $row_07->ORD_ID;

											if($ORD_07 != $ORR_ID)
											{
												$sql_07A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 7 WHERE JOBCODEID = '$JOBC_07' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($sql_07A);

												$sql_07A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 7 WHERE JOBCODEID = '$JOBC_07' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($sql_07A);

												$sql_07A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 7 WHERE JOBCODEID = '$JOBC_07' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($sql_07A);
											}

											$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
											$resORD		= $this->db->count_all($sqlORD);
											$percent 	= intval($ORR_ID/$resJLT * 100)."%";
											echo '<script>
											parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

										    ob_flush();
										    flush();

										    // LEVEL _08 PER HEADER
										    $sql_08	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_07' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
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

											    ob_flush();
											    flush();

											    // LEVEL _09 PER HEADER
											    $sql_09	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_08' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
												$res_09 = $this->db->query($sql_09)->result();
												foreach($res_09 as $row_09) :
													$ORR_ID		= $ORR_ID + 1;
													$JOBC_09	= $row_09->JOBCODEID;
													$ORD_09		= $row_09->ORD_ID;

													if($ORD_09 != $ORR_ID)
													{
														$sql_09A	= "UPDATE tbl_boqlist SET ORD_ID = $ORR_ID, JOBLEV = 9 WHERE JOBCODEID = '$JOBC_09' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($sql_09A);

														$sql_09A	= "UPDATE tbl_joblist SET ORD_ID = $ORR_ID, JOBLEV = 9 WHERE JOBCODEID = '$JOBC_09' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($sql_09A);

														$sql_09A	= "UPDATE tbl_joblist_detail SET ORD_ID = $ORR_ID, IS_LEVEL = 9 WHERE JOBCODEID = '$JOBC_09' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($sql_09A);
													}

													$sqlORD		= "tbl_joblist_detail WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != 0";
													$resORD		= $this->db->count_all($sqlORD);
													$percent 	= intval($ORR_ID/$resJLT * 100)."%";
													echo '<script>
													parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$ORR_ID. ' / '.$resJLT.') processed</span></div>";</script>';

												    ob_flush();
												    flush();

												    // LEVEL _10 PER HEADER
												    $sql_10	= "SELECT JOBCODEID, ORD_ID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBC_09' AND PRJCODE = '$SYNC_PRJ' ORDER BY JOBCODEID";
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
				    parent.document.getElementById("loading_1").style.display ="none";
					    parent.updStat();</script>';
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
		echo '<script>
	    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

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
			$updJLD = "UPDATE tbl_joblist_detail SET ITM_USED=0, ITM_USED_AM=0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updJLD);

		$JournType	= '';
		$sqlJLType	= "SELECT DISTINCT JournalType FROM tbl_journaldetail WHERE GEJ_STAT = 3";
		$resJLType	= $this->db->query($sqlJLType)->result();
		foreach($resJLType as $rowJLType) :
			$JournType	= $rowJLType->JournalType;
			if($JournType == 'GEJ')
			{
				//
			}
			elseif($JournType == 'CPRJ')
			{
				$sqlJDetC	= "tbl_journaldetail A WHERE A.JournalType = 'CPRJ' AND Base_Debet > 0 AND A.GEJ_STAT = 3";
				$resJDetC	= $this->db->count_all($sqlJDetC);

				$rowRes 	= 0;
				$JournType	= '';
				$sqlJDet	= "SELECT A.JournalH_Code, A.Acc_Id, A.JOBCODEID, A.ITM_VOLM, A.ITM_PRICE, A.Base_Debet
								FROM tbl_journaldetail A
								WHERE A.JournalType = 'CPRJ' AND Base_Debet > 0 AND A.GEJ_STAT = 3";
				$resJDet	= $this->db->query($sqlJDet)->result();
				foreach($resJDet as $rowJDet) :
					$rowRes 	= $rowRes+1;
					$JOBCODEID	= $rowJDet->JOBCODEID;
					$ITM_VOLM	= $rowJDet->ITM_VOLM;
					$ITM_PRICE	= $rowJDet->ITM_PRICE;
					$Base_Debet	= $rowJDet->Base_Debet;

					// UPDATE JOBLIST DETAIL
						$updJLD = "UPDATE tbl_joblist_detail SET ITM_USED=ITM_USED+$ITM_VOLM, ITM_USED_AM=ITM_USED_AM+$Base_Debet
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
						$this->db->query($updJLD);					

					// SENT PROGRESS BAR
						$percVal 	= intval($rowRes/$resJDetC * 100);
						$percent 	= intval($rowRes/$resJDetC * 100)."%";
						$modPerc 	= $percVal % 5;
						if($modPerc == 0)
						{
							echo '<script>
							parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$rowRes. ' / '.$resJDetC.') processed</span></div>";</script>';

						    ob_flush();
						    flush();
						    sleep(1000);
						}
				endforeach;
			}
			elseif($JournType == 'OPN')
			{
				//
			}
			elseif($JournType == 'UM')
			{}
		endforeach;

		// 3.	COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
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
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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