<?php
$data = $_REQUEST['data'];
if($data == null || $data == '') {
	die("You need to specify a request parameter by the name of 'data'. ".
		"Remember, add the 'd' request paramater to your request for the ".
		"Content-Disposition header to be set, making the user download the file.");
}
if(array_key_exists('d', $_REQUEST)) {
	$download_filename = $_REQUEST['d'];
	$download_filename = preg_replace("/[^A-Za-z0-9\-]/", '', $download_filename);
	$download_filename = substr($download_filename, 0, 255);
	$download_filename .= ".png";
} else {
	$download_filename = null;
}

$svg_filename = tempnam(sys_get_temp_dir(), 'svg2png-');
$png_filename = tempnam(sys_get_temp_dir(), 'svg2png-');

$svg_handle = fopen($svg_filename, "w");
fwrite($svg_handle, $data);
fclose($svg_handle);

$output = array();
$result = null;
exec("inkscape -f $svg_filename -e $png_filename", $output, $result);

if($result === 0) {
	header('Content-type: image/png');
	if($download_filename != null) {
		header('Content-Disposition: attachment; filename="'.$download_filename.'"');
	}
	echo file_get_contents($png_filename);
} else {
	error_log("Coundn't convert svg to png: ".implode("; ", $output));
	die("ERROR: Something went wrong when running the inkscape command, ".
		"please make sure that the webserver has permissions to write to ".
		"temporary files and that the inkscape executable is in the ".
		"path of the webservers process. The error has been written to the log.");
}