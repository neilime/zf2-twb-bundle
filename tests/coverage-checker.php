<?php
$sInputFile = $argv[1];
$iPercentage = min(100, max(0,(int)$argv[2]));
if(!is_readable($sInputFile))throw new \InvalidArgumentException('Input file "'.$sInputFile.'" does not exist');
if(!$iPercentage)throw new InvalidArgumentException('Checked percentage param expects an integer, "'.$iPercentage.' given');
$oXml = new SimpleXMLElement(file_get_contents($sInputFile));
$iTotalElements = 0;
$iCheckedElements = 0;
foreach($oXml->xpath('//metrics') as $aMetric){
$iTotalElements += (int) $aMetric['elements'];
$iCheckedElements += (int) $aMetric['coveredelements'];
}
if(($iCoverage = ($iCheckedElements / $iTotalElements) * 100) < $iPercentage){
echo 'Code coverage is '.$iCoverage.'%, which is below the accepted '.$iPercentage.'%'.PHP_EOL;
exit(1);
}
echo 'Code coverage is '.$iCoverage.'% - OK!'.PHP_EOL;
