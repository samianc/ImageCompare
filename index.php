<?php
set_time_limit(800);
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

	exit(0);
}

$rustart = getrusage();
	
// require 'src/boundary.php';
// require 'src/color.php';
// require 'src/crawler.php';
// require 'src/crawleroutline.php';
// require 'src/crawleroutlinecollection.php';
// require 'src/image.php';
// require 'src/imagecollection.php';
// require 'src/imagepixelmatrix.php';
// require 'src/pixel.php';
// require 'src/point.php';
require 'autoload.php';

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	// $remove = imagecolorallocate($dst, 255, 255, 255);
	// imagecolortransparent($dst, $remove);

    return $dst;
}

function cleanImage($path) {
	$path = explode('.', $path)[0];
	// Load the mask
	$imageObject = resize_image($path . '.jpg', 30, 30);
	// $cropped = imagecropauto($imageObject, IMG_CROP_DEFAULT);
    // if ($cropped !== false) { // in case a new image resource was returned
    //     echo "=> Cropping needed\n";
    //     imagedestroy($imageObject);    // we destroy the original image
    //     $imageObject = $cropped;       // and assign the cropped image to $im
    // }
    // imagedestroy($imageObject);
	imagepng($imageObject, $path . '.png');
}
if(isset($_GET['image'])) {
	$encoded_image = explode(",", $_GET['image'])[1];
	$decoded_image = base64_decode($encoded_image);
	$source = imagecreatefromstring($decoded_image);
	$imageSave = imagejpeg($source,'demo_inputs/image.jpg',100);
	imagedestroy($source);
}
cleanImage('demo_inputs/image');
cleanImage('demo_inputs/1');
cleanImage('demo_inputs/2');
cleanImage('demo_inputs/3');
cleanImage('demo_inputs/4');
cleanImage('demo_inputs/5');
cleanImage('demo_inputs/6');
cleanImage('demo_inputs/7');
cleanImage('demo_inputs/8');
cleanImage('demo_inputs/9');
cleanImage('demo_inputs/10');
cleanImage('demo_inputs/11');
cleanImage('demo_inputs/12');

// Load images
$sketch = Image::fromFile('demo_inputs/image.png');
$image1 = Image::fromFile('demo_inputs/1.png');
$image2 = Image::fromFile('demo_inputs/2.png');
$image3 = Image::fromFile('demo_inputs/3.png');
$image4 = Image::fromFile('demo_inputs/4.png');
$image5 = Image::fromFile('demo_inputs/5.png');
$image6 = Image::fromFile('demo_inputs/6.png');
$image7 = Image::fromFile('demo_inputs/7.png');
$image8 = Image::fromFile('demo_inputs/8.png');
$image9 = Image::fromFile('demo_inputs/9.png');
$image10 = Image::fromFile('demo_inputs/10.png');
$image11 = Image::fromFile('demo_inputs/11.png');
$image12 = Image::fromFile('demo_inputs/12.png');

$Images =  array(
	'image1' => (object) [
		'diff' => $sketch->difference($image1),
		'src' => 'demo_inputs/1.jpg'
	],
	'image2' => (object) [
		'diff' => $sketch->difference($image2),
		'src' => 'demo_inputs/2.jpg'
	],
	'image3' => (object) [
		'diff' => $sketch->difference($image3),
		'src' => 'demo_inputs/3.jpg'
	],
	'image4' => (object) [
		'diff' => $sketch->difference($image4),
		'src' => 'demo_inputs/4.jpg'
	],
	'image5' => (object) [
		'diff' => $sketch->difference($image5),
		'src' => 'demo_inputs/5.jpg'
	],
	'image6' => (object) [
		'diff' => $sketch->difference($image6),
		'src' => 'demo_inputs/6.jpg'
	],
	'image7' => (object) [
		'diff' => $sketch->difference($image7),
		'src' => 'demo_inputs/7.jpg'
	],
	'image8' => (object) [
		'diff' => $sketch->difference($image8),
		'src' => 'demo_inputs/8.jpg'
	],
	'image9' => (object) [
		'diff' => $sketch->difference($image9),
		'src' => 'demo_inputs/9.jpg'
	],
	'image10' => (object) [
		'diff' => $sketch->difference($image10),
		'src' => 'demo_inputs/10.jpg'
	],
	'image11' => (object) [
		'diff' => $sketch->difference($image11),
		'src' => 'demo_inputs/11.jpg'
	],
	'image12' => (object) [
		'diff' => $sketch->difference($image12),
		'src' => 'demo_inputs/12.jpg'
	]
);
function cmp($a, $b)
{
	if($a->diff==$b->diff) return 0;
    return $a->diff < $b->diff ? 1 : -1;
}

usort($Images, "cmp");

echo "<section><img class='main' src='demo_inputs/image.jpg'>";
foreach($Images as $image) {
	echo "<div><img src ='" . $image->src . "'><span>" . $image->diff . "</span></div>";
}
echo "</section>";

// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "<b>This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations</b>";
echo "<b>It spent " . rutime($ru, $rustart, "stime") .
	" ms in system calls</b>";
?>
<style>
section {
	width: 100%;
	display: table;
}
div {
	display: table;
	float:left;
    width: 10%;
}
img:not(.main) {
	width: 100%;
	height: 130px;
}
img.main {
	max-width: 25%;
	display: table;
	margin: auto;
}
span {
    display: table;
    margin-top: 20px;
}
b {
	font-size: 20px;
}
</style>
