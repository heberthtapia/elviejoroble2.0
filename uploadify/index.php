<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link rel="stylesheet" type="text/css" href="uploadify.css"/>
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="jquery.uploadify-3.1.js"></script>
<script>
	$(function() {
		$('#file_upload').uploadify({
			'swf'      : 'uploadify.swf',
			'uploader' : 'uploadify.php',
			'method'   : 'post',	
			'fileType' : 'image/png',		
    		'formData' : { 'someKey' : 'someValue' },
			'onUploadSuccess' : function(file, data, response) {
      			alert('The file was saved to: ' + data);
    		},		
			//'folder'    	: 'img/revista', 
			// Put your options here
		});		
		
	});
	
	
</script>

</head>

<body>

	<input type="file" name="file_upload" id="file_upload" />
</body>
</html>