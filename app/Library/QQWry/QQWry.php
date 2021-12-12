<?php
namespace App\Library\QQWry; class QQWry { private $fp; private $firstIP; private $lastIP; private $totalIP; public function __construct($sp9bdc80 = false) { if ($sp9bdc80 === false) { $sp9bdc80 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'qqwry.dat'; } $this->fp = 0; if (($this->fp = @fopen($sp9bdc80, 'rb')) !== false) { $this->firstIP = $this->getLong(); $this->lastIP = $this->getLong(); $this->totalIP = ($this->lastIP - $this->firstIP) / 7; register_shutdown_function(array(&$this, '__destruct')); } } public function __destruct() { if ($this->fp) { fclose($this->fp); } $this->fp = 0; } private function getLong() { $sp1b1403 = unpack('Vlong', fread($this->fp, 4)); return $sp1b1403['long']; } private function _getLong3() { $sp1b1403 = unpack('Vlong', fread($this->fp, 3) . chr(0)); return $sp1b1403['long']; } private function _packIP($spdea256) { return pack('N', intval(ip2long($spdea256))); } private function _getString($sp5b8b32 = '') { $spfeaf16 = fread($this->fp, 1); while (ord($spfeaf16) > 0) { $sp5b8b32 .= $spfeaf16; $spfeaf16 = fread($this->fp, 1); } return $sp5b8b32; } private function _getArea() { $sp5f6d0c = fread($this->fp, 1); switch (ord($sp5f6d0c)) { case 0: $sp03c726 = ''; break; case 1: case 2: fseek($this->fp, $this->_getLong3()); $sp03c726 = $this->_getString(); break; default: $sp03c726 = $this->_getString($sp5f6d0c); break; } return $sp03c726; } public function getLocation($spdea256) { if (!$this->fp) { return '请下载qqwry.dat放在app/Library/QQWry目录下'; } $spe1d1dd['ip'] = gethostbyname($spdea256); $spdea256 = $this->_packIP($spe1d1dd['ip']); $spfe79dc = 0; $sp25f828 = $this->totalIP; $spa0fe04 = $this->lastIP; while ($spfe79dc <= $sp25f828) { $sp1148f5 = floor(($spfe79dc + $sp25f828) / 2); fseek($this->fp, $this->firstIP + $sp1148f5 * 7); $spe66536 = strrev(fread($this->fp, 4)); if ($spdea256 < $spe66536) { $sp25f828 = $sp1148f5 - 1; } else { fseek($this->fp, $this->_getLong3()); $spad0a32 = strrev(fread($this->fp, 4)); if ($spdea256 > $spad0a32) { $spfe79dc = $sp1148f5 + 1; } else { $spa0fe04 = $this->firstIP + $sp1148f5 * 7; break; } } } fseek($this->fp, $spa0fe04); $spe1d1dd['beginip'] = long2ip($this->getLong()); $sp150a27 = $this->_getLong3(); fseek($this->fp, $sp150a27); $spe1d1dd['endip'] = long2ip($this->getLong()); $sp5f6d0c = fread($this->fp, 1); switch (ord($sp5f6d0c)) { case 1: $sp3325c8 = $this->_getLong3(); fseek($this->fp, $sp3325c8); $sp5f6d0c = fread($this->fp, 1); switch (ord($sp5f6d0c)) { case 2: fseek($this->fp, $this->_getLong3()); $spe1d1dd['country'] = $this->_getString(); fseek($this->fp, $sp3325c8 + 4); $spe1d1dd['area'] = $this->_getArea(); break; default: $spe1d1dd['country'] = $this->_getString($sp5f6d0c); $spe1d1dd['area'] = $this->_getArea(); break; } break; case 2: fseek($this->fp, $this->_getLong3()); $spe1d1dd['country'] = $this->_getString(); fseek($this->fp, $sp150a27 + 8); $spe1d1dd['area'] = $this->_getArea(); break; default: $spe1d1dd['country'] = $this->_getString($sp5f6d0c); $spe1d1dd['area'] = $this->_getArea(); break; } if ($spe1d1dd['country'] == ' CZ88.NET') { $spe1d1dd['country'] = '未知'; } if ($spe1d1dd['area'] == ' CZ88.NET') { $spe1d1dd['area'] = ''; } return iconv('gbk', 'utf-8//IGNORE', $spe1d1dd['country'] . $spe1d1dd['area']); } }