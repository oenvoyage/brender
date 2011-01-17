//jQuery and jQuery UI fununctions START

$(function() {
	// general shortcuts on all pages
         $(document).keyup(function(e) {
                        
                       if (e.keyCode == 79) {                  // Esc
                               window.location.href = 'index.php';
                       }    
                });

	//Spinner next to brender logo (visible during AJAX calls)
	$('#loadingSpinner')
	    .hide()  // hide it initially
	    .ajaxStart(function() {
	        $(this).show();
	    })
	    .ajaxStop(function() {
	        $(this).hide();
	    });
	
	// Make buttons from the following element
	$( "button, input:submit, a.btn").button();
	//$( "a", ".btn" ).click(function() { return false; });
	
	
	// NEW JOB dialog START
	var project = $('select#project'),
		scene = $('select#scene'),
		shot = $('select#shot'),
		fileformat = $('select#fileformat'),
		config = $('select#config'),
		start = $('input#start'),
		end = $('input#end'),
		chunks = $('input#chunks'),
		priority = $('input#priority'),
		rem = $('input#rem')

	
	$("#new_job").dialog({
		autoOpen: false,
		height: 400,
		width: 450,
		modal: true,
		resizable: false,
		buttons: {
			Cancel: function() {
				$(this).dialog("close");
			},
			"Start job": function() { 											
					$.post("ajax/new_job.php", {
						project: project.val(), 
						scene: scene.val(), shot: shot.val(), 
						fileformat: fileformat.val(), 
						config: config.val(), 
						start: start.val(), 
						end: end.val(), 
						chunks: chunks.val(), 
						priority: priority.val(), 
						rem: rem.val(), 
						directstart: $('#directstart').is(':checked') 
					}, function(data) {
						var obj = jQuery.parseJSON(data);
						//alert(data);
						if(obj.status == true) {
							$("#dialog-form").dialog("close" );
							//alert(obj.query);
							window.location= 'index.php';
						} else {
							alert(obj.msg);
						}
					}, "Json");				
					return false;					
			}
		},
		close: function() {
			//allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
	
	$("#new_job_button, #new_job_button2, #new_job_button3").click(function() {
	$("#new_job").dialog("open");
	});
	// NEW JOB dialog END
	
	
});

//jQuery and jQuery UI fununctions END

// Applies cascading behavior for the specified dropdowns 
// javascript source : http://www.weberdev.com/get_example-4505.html
function applyCascadingDropdown(sourceId, targetId) { 
    var source = document.getElementById(sourceId); 
    var target = document.getElementById(targetId); 
    if (source && target) { 
        source.onchange = function() { 
            displayOptionItemsByClass(target, source.value); 
        } 
        displayOptionItemsByClass(target, source.value); 
    } 
} 

// Displays a subset of a dropdown's options 
function displayOptionItemsByClass(selectElement, className) { 
    if (!selectElement.backup) { 
        selectElement.backup = selectElement.cloneNode(true); 
    } 
    var options = selectElement.getElementsByTagName("option"); 
    for(var i=0, length=options.length; i<length; i++) { 
        selectElement.removeChild(options[0]); 
    } 
    var options = selectElement.backup.getElementsByTagName("option"); 
    for(var i=0, length=options.length; i<length; i++) { 
        if (options[i].className==className) 
            selectElement.appendChild(options[i].cloneNode(true)); 
    } 
} 

// Binds dropdowns 
function applyCascadingDropdowns() { 
    applyCascadingDropdown("project", "scene"); 
    applyCascadingDropdown("scene", "shot"); 
    //We could even bind items to another dropdown 
    //applyCascadingDropdown("items", "foo"); 
} 

//    applyCascadingDropdown("categories", "items"); 
//// execute when the page is ready 
// window.onload=applyCascadingDropdowns;
window.onload=applyCascadingDropdowns;

