<!DOCTYPE html>
<html lang="en">
<head>
<title>music</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="styles.css">


    <meta charset="UTF-8">
    <title>HTML5 Audio Player</title>
   
</head>

<body>
	<p>Deepspace One</p>
<audio id="audio1" controls>
  <source src="http://ice1.somafm.com/deepspaceone-128-mp3">
Your browser doesn't support HTML5. Maybe you should upgrade.
</audio>
<p>DroneZone</p>
<audio id="audio1" controls>
  <source src="http://ice3.somafm.com/dronezone-128-mp3">
Your browser doesn't support HTML5. Maybe you should upgrade.
</audio>
<p>Space Station</p>
<audio id="audio1" controls>
  <source src="http://ice1.somafm.com/spacestation-128-mp3">
Your browser doesn't support HTML5. Maybe you should upgrade.
</audio>

<p>Library Player</p>
 <audio src="" controls id="audioPlayer">
        Sorry, your browser doesn't support html5!
    </audio>
    
    
     <p id="now_playing"></p>
 

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class SortedIterator extends SplHeap
{
    public function __construct(Iterator $iterator)
    {
        foreach ($iterator as $item) {
            $this->insert($item);
        }
    }
    public function compare($b,$a)
    {
        return strcmp($a->getRealpath(), $b->getRealpath());
    }
}

class abrowser
{
 var $startpath;
 var $adirs = array();
 var $backPath;
 var $subdir;
 var $roots;
 var $root;
 function abrowser(){
  $this->subdir = "";
  $this->startpath = "";
 }
 
// The folder names are returned in an array, adirs[]
 function getDirs() {
  $dir = dir($this->startpath);
  while (false !== ($file = $dir->read())) {
   if($file != "." && $file != "..") {
    if (is_dir($this->startpath.$file)) {
     $this->adirs[] = $file;
    }
   }
  }
  $dir->close();
  sort($this->adirs);
  return $this->adirs;
 }

 function setRoot($rt){
  $this->root = $rt;
  return $this->root;
 }

 function getRoot(){
  return $this->root;
 }

 function setPath($path,$sb=""){
  $this->startpath = $path . $sb;
  $this->subdir = $sb;
  $this->rel = str_replace($this->root,"",$this->startpath);
  return TRUE;
 }

 function getRel(){
  return $this->rel;
 }

 function getPath(){
  return $this->startpath;
 }

 function showDirs($links=TRUE){
  foreach ($this->adirs as $dr){
   if($links){
    print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->startpath&sub=$dr'>$dr</href></a><br>\n";
   }else{
    print "$dr<br>";
   }
  }
 }

// Displays the navigation links 
 function showCrumbs(){
  $this->crumbs=explode("/",$this->rel);
  $n=count($this->crumbs)-1;
  unset($this->crumbs[$n]);
  print "<br>";
  //print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->root'> " .$this->roots . "</a><br>";
  if($n>0){
   for($i=0;$i<$n;$i++){
    print " <a href='".$_SERVER['PHP_SELF']."?sp=$this->root";
    for($j=0;$j<$i;$j++){
     print $this->crumbs[$j] . "/";
    }
    print "&sub=" . $this->crumbs[$i];
    print "'>  " . $this->crumbs[$i] . "&nbsp || &nbsp </a>";
   }
  }
  print "<br><br><br>";
  return;
 }


// Sets $this->roots with $rts, used for the navigation links
 function setRoots($rts){
  $this->roots=$rts;
  return;
 }

// Check to see if the current subfolder is empty
// Returns true if empty, false if not empty
 function isEmpty(){
 $os=$_ENV['OS'];
 $sep=(strpos($os,"Windows")!==false)?"\\":"/";
  $rp=realpath($this->startpath).$sep;
  $dir = dir($rp);
  while ($file = $dir->read()) {
   if($file !== "." && $file !== ".."){
    return FALSE;
   }
  }
  return TRUE;
 }

} //end of class
 session_start();


 (isset($_GET['sp'])) ? $spath=$_GET['sp'] : $spath="./";
 (isset($_GET['sub'])) ? $sub=$_GET['sub']."/" : $sub="";
 $dr = new abrowser();
 $rt=$dr->setRoot("./");
 $rt=$_SERVER['HTTP_HOST'] . str_replace("\\","/",dirname($_SERVER['PHP_SELF']));
 $dr->setPath($spath, $sub);
 $spath=$dr->getPath();
 
//  This is used for the navigation links at the top of each screen
 $roots = "http://" . $_SERVER['HTTP_HOST'] . str_replace("\\","/",dirname($_SERVER['PHP_SELF']));
 $roots=$roots."/";

 $dr->setRoots($roots);
 $rel = str_replace($rt,"",$dr->getPath());
 
// Show navigation links at the top
 $dr->showCrumbs();

// Get the directory list of folders
 $dirs=$dr->getDirs();

//  Show the directories 
 $dr->showDirs(TRUE);
 
 print "<br>\n";
 print '<ul id="playlist">';

 /*
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dr->getPath()));
$sit = new SortedIterator($iterator);

foreach ($sit as $file) {
    if ($file->isDir()) continue;
    if (pathinfo($file, PATHINFO_EXTENSION) == 'mp3') {
    echo '<li class="current-song"><a href="'.$file.'">'.pathinfo($file, PATHINFO_FILENAME).'</a></li>'; 
}
}
  print ' </ul>';
  * */
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dr->getPath()));
$data = iterator_to_array($rii);
$files = array(); 

foreach ($data as $file) {
    if ($file->getExtension() != "mp3" ){ 
        continue;
    }
$files[$file->getPathname()] = $file->getfilename() ;
}

/* sort */ 
//asort($files);

/* shuffle */
uksort($files, function() { return rand() > rand(); });


foreach ($files as $path_file => $filename) {
	
	echo '<li class="current-song"><a href="'.$path_file.'">'.trim($filename, ".mp3").'</a></li>'; 
}

print ' </ul>'; 

  ?>
    
    <script src="https://code.jquery.com/jquery-2.2.0.js"></script>
    <script src="audioPlayer.js"></script>
    <script>
        // loads the audio player
        audioPlayer();
        
    </script>
</body>
</html>   

