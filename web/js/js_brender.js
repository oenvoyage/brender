//Applies cascading behavior for the specified dropdowns 
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

//Displays a subset of a dropdown's options 
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

//Binds dropdowns 
function applyCascadingDropdowns() { 
    applyCascadingDropdown("project", "scene"); 
    applyCascadingDropdown("scene", "shot"); 
    //We could even bind items to another dropdown 
    //applyCascadingDropdown("items", "foo"); 
} 

//    applyCascadingDropdown("categories", "items"); 
////execute when the page is ready 
//window.onload=applyCascadingDropdowns;
window.onload=applyCascadingDropdowns;

