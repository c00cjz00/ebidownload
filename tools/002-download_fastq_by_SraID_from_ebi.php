<?php
# Example: php 001-download_fastq_by_bioprojectID_from_ebi.php PRJNA472785 

# input sraID id
$sraID=$argv[1];

# configure #
$outputDir="\$HOME/ebi/public/sraID/".$sraID;

# download project id content  form ebi
$downloadUrl="curl -o ".$sraID.".acc 'https://www.ebi.ac.uk/ena/data/warehouse/filereport?accession=".$sraID."&result=read_run&fields=fastq_ftp'";
exec($downloadUrl);
$tmpArr=file($sraID.".acc");

unlink($sraID.".acc");

# release fastq download url 
for($i=0;$i<count($tmpArr);$i++){
 $tmp=trim($tmpArr[$i]);
 $smpArr=explode(";",$tmp);
 $cmdAll="";
 for($j=0;$j<count($smpArr);$j++){
  $smp=$smpArr[$j];
  $SRR=basename(dirname($smp));
  $outputDirTmp=$outputDir."/".$SRR;
  if (substr($smp,0,17)=="ftp.sra.ebi.ac.uk"){
   $url="era-fasp@fasp.sra.ebi.ac.uk:".substr($smp,17);
   $cmd="ascp -QT -l 300m -P33001 -i \$HOME/tools/sra-tools/aspera/cli/etc/asperaweb_id_dsa.openssh ".$url." ".$outputDirTmp;
   //$cmdAll.="mkdir -p ".$outputDirTmp." ; ".$cmd." ; ";
   $cmdAll="mkdir -p ".$outputDirTmp." ; ".$cmd." ; ";
   echo $cmdAll."\n";
   //passthru($cmdAll);
//   runcmd($cmd);
$proc = popen($cmd, 'r');
while (!feof($proc)) {
$tmp=fread($proc, 4096);
//if (!ereg("remaining",$tmp)) {
echo $tmp;
//}
@ flush();
}


  }
 }
 ## print command
 if ($cmdAll!=""){
  //echo $cmdAll."\n";
 } 
}

function runcmd($cmd){
$proc = popen($cmd, 'r');
while (!feof($proc)) {
$tmp=fread($proc, 4096); 
//if (!ereg("remaining",$tmp)) {
echo $tmp;
//}
@ flush();
}
}



?>