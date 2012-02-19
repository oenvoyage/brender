/*!
 * stopmotion-lite.js
 * by Ben Byford
 *
 * Examples and documentation at: http://www.benbyford.com/
 * Version: 1.1 (29-MAY-2011)
 * Licensed under Creative Commons - Attribution 3.0 Unported (CC BY 3.0).
 * http://creativecommons.org/licenses/by/3.0/
 * Requires: jQuery v1.2 or later
 */


function stopmotion(divID, animationSpeed, pauseButton) {

// defaults
	divID = typeof(divID) != 'undefined' ? divID : slideImages;
	animationSpeed = typeof(animationSpeed) != 'undefined' ? animationSpeed : '100';
	pauseButton = typeof(pauseButton) != 'undefined' ? pauseButton : '1';

// setup play_pause
	if(pauseButton == 1){
		$(divID).parent().prepend('<div id="playPause"></div>');
	}

// setup classes
	$(divID).children().css('position','absolute');
	$(divID).find(':first-child').addClass('slideOn first');

// get number of imgs	
	$imgNum = $(divID).children().length;
	$i = '0';


// trigger on window loaded	
	$(window).load(	
		function(){
			
			$a = '0';
			$pause = '0';
			
	// play_pause button
			$('#playPause').css('display', 'block').bind('click', function(){
				if($a == '0'){
					$(this).css('background-position','0px -20px');
					
					$a = '1';
					$pause = '1';
				}else{
					$(this).css('background-position','0px 0px');
					
					$a = '0';
					$pause = '0'
					timer1();
				}
			})
			
			$(divID).animate({ opacity: 1 }, 500, timerLoading)
		
	// animate div
			function fadeSlides(){
				$('.slideOn').css('display','none').css('z-index','0').removeClass('slideOn').next().addClass('slideOn').css('z-index',$imgNum).css('display','block');
				if($i == $imgNum){
					$i = '1';
					$('.first').addClass('slideOn');
				}else{
					$i++;
				}
				timer1();
			}

	// timer
			function timer1(){
				if($pause == '0'){
				 	var t = setTimeout(fadeSlides,animationSpeed);
				 }else{
				 	
				 }
			}
			
	// remove loading .gif and start animation
			function timerLoading(){ 
				$('#loading').fadeOut(500).remove();
				timer1();
			}
	    }
	);
}