function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded, max:e.total});
    }
}

function handleFiles(file)
{
	// get the FormData object
	var formData = new FormData($('form')[0]);
	// append the file to the form
	formData.append("files[]", file);
	// build the AJAX statement
	$.ajax({
		url: 'php/upload.php',
		data: formData,
		type: 'POST',
		// custom xhr
		xhr: function() {  
            myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){ // check if upload property exists
                myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // for handling the progress of the upload
            }
            return myXhr;
        },
        //AJAX events
        error: function(xhr, textStatus, errorThrown) {
			displayError(textStatus);
		},
		success: function(data, textStatus) {
			if (data.errno != null) {
				displayPHPError(data);
			} else {
				//alert(data);
			}
		},
		// tell JQuery not to process data or worry about content-type
        cache: false,
		processData: false,
		contentType: false,
	});		
}

