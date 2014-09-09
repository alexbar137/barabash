<html> 
<head>
    <link href="style.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
    <script src="http://malsup.github.com/jquery.form.js"></script> 
 
    <script> 
        
    $(document).ready(function() 
    { 
        var options = 
        { 
            target:   '#output',   // target element(s) to be updated with server response 
            beforeSubmit:  beforeSubmit,  // pre-submit callback 
            uploadProgress: OnProgress, //upload progress callback 
            success:       afterSuccess,  // post-submit callback 
            resetForm: true        // reset the form after successful submit 
        }; 
                
        $('#MyUploadForm').submit(function() 
        {
            $(this).ajaxSubmit(options);            
            return false; 
        }); 
    });
    
    function beforeSubmit(){
        //check whether client browser fully supports all File API
        if (window.File && window.FileReader && window.FileList && window.Blob)
        {
            var fsize = $('#FileInput')[0].files[0].size; //get file size
            var ftype = $('#FileInput')[0].files[0].type; // get file type
            //allow file types 
            if(ftype != 'image/jpeg')
            {
                $('#output').html("<b>"+ftype+"</b> - формат файла не поддерживается")
                return false;
            }
        
           //Allowed file size is less than 5 MB (1048576 = 1 mb)
            if(fsize>5242880) 
            {
                alert("<b>"+fsize +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
                return false;
            }
        }
        else
        {
           //Error for older unsupported browsers that doesn't support HTML5 File API
            alert("Please upgrade your browser, because your current browser lacks some new features we need!");
            return false;
        }
    }
    
    function OnProgress(event, position, total, percentComplete)
    {
        //Progress bar
        $('#progressbox').show();
        $('#progressbar').width(percentComplete + '%') //update progressbar percent complete
        $('#statustxt').html(percentComplete + '%'); //update status text
        if(percentComplete>50)
            {
                $('#statustxt').css('color','#000'); //change status text to white after 50%
            }
    }
    
    function afterSuccess()
    {
    	$('#submit-btn').show(); //hide submit button
    	$('#loading-img').hide(); //hide submit button
    	$('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar

    }
    
    </script> 
</head> 
<body>
<form action="processupload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
<input name="FileInput" id="FileInput" type="file" />
<input type="submit"  id="submit-btn" value="Upload" />
<img src="ajax-loader.gif" id="loading-img" style="display: none;"  alt="Please Wait"/>
</form>
<div id="progressbox" ><div id="progressbar"></div ><div id="statustxt">0%</div></div>
<div id="output"></div>
</body>
</html>