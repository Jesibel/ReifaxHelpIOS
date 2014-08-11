<?php
header('Access-Control-Allow-Origin: *');
include("config.inc.php");

$results = mysqli_query($connecDB,"SELECT COUNT(*) FROM videos_training where type='Contracts' or type='My Signature'");
$get_total_rows = mysqli_fetch_array($results); //total records

//break total records into pages
$total_pages = ceil($get_total_rows[0]/$item_per_page);	
$contador=$get_total_rows[0];
$search=$_POST['search'];




?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AOREIA MOBILE</title>
<script type="text/javascript" src="resources/js/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">
  <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="//code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
  
  <script type="text/javascript" charset="utf-8">
    
     function init() {
         window.addEventListener("orientationchange", orientationChange, true);
     }

     function orientationChange(e) {
         var orientation="portrait";
         if(window.orientation == -90 || window.orientation == 90) orientation = "landscape";
             document.getElementById("video").innerHTML+=orientation+"<br>";
			 document.getElementById("embed").innerHTML+=orientation+"<br>";
     }
     </script>
  
<script type="text/javascript">


function videoFS(){
	if (video.requestFullscreen) {
		video.requestFullscreen();
	}else if (video.msRequestFullscreen) {
		video.msRequestFullscreen();
	}else if (video.mozRequestFullScreen) {
		video.mozRequestFullScreen();
	}else if (video.webkitRequestFullscreen) {
		video.webkitRequestFullscreen();
	}else if (video.webkitEnterFullscreen) {
		video.webkitEnterFullscreen();
	}
}

	
$(document).ready(function() {
	var video=null,
	time=null;
	
	$(document).on("pagecontainerhide", function (e, ui) {
	
		video = $('#video', ui.nextPage[0])[0];
		if (video){
		
			window.parent.postMessage("video", "file://");
		
			$("#video_full").click(function(){
				time = window.setInterval(function() {
					try {
						videoFS();
						console.log('FS: activando...');
					}
					catch(e) {
					 console.log('FS: error');
					}
				}, 250);
				
				video.play();
			});
			
			/*video.addEventListener('play', function() {
				//window.parent.plugins.orientationchanger.lockOrientation('landscape');
				$("#video_full").trigger('click');
				window.parent.postMessage("video", "file://");
			}, false);*/
			 
			
		}else{
		   
			window.parent.postMessage("reset", "file://");
		}
    });
	
	
       
   jQuery(document).on('webkitfullscreenchange', function(e) {   
		//alert('dentro webkit');
		window.clearInterval(time);
		console.log('FS: activado');
		if(!e.currentTarget.webkitIsFullScreen) {
			video.webkitExitFullscreen();
			video.pause();
			//window.parent.plugins.orientationchanger.resetOrientation();
			//window.parent.postMessage("reset", "file://");
		}
   });
   
   
		

	var track_click = 0; //track user click on "load more" button, righ now it is 0 click
	
	var total_pages = <?php echo $total_pages; ?>;
	var contador =<?php echo $contador; ?>;
	var num=20;
	
	$('#results').load("fetch_pages_ContractsIOS.php", {'page':track_click}, function() {track_click++;}); //initial data to load
	
	if(contador<num){
		$(".load_more").hide();
		}else{
		$(".load_more").show();
		}
		
	if(contador>num){
	$(".load_more").click(function (e) { //user clicks on button
	
		$(this).hide(); //hide load more button on click
		$('.animation_image').show(); //show loading image

		if(track_click < total_pages) //make sure user clicks are still less than total pages
		{
			//post page number and load returned data into result element
			$.post('fetch_pages_ContractsIOS.php',{'page': track_click}, function(data) {
			
				$(".load_more").show(); //bring back load more button
				
				$("#results").append(data); //append data received from server
				
				//scroll page to button element
				$("html, body").animate({scrollTop: $("#load_more_button").offset().top}, 500);
				
				//hide loading image
				$('.animation_image').hide(); //hide loading image once data is received
	
				track_click++; //user click increment on load button
			
			}).fail(function(xhr, ajaxOptions, thrownError) { 
				alert(thrownError); //alert any HTTP error
				$(".load_more").show(); //bring back load more button
				$('.animation_image').hide(); //hide loading image once data is received
			});
			
			
			if(track_click > total_pages)
			{
				//reached end of the page yet? disable load button
				//$(".load_more").attr("disabled", "disabled");
				$(".load_more").hide();
				$('.animation_image').hide();
			}
		 }
		  
		});
		}
	
    });
	
	
</script>
 
			
<link href="resources/css/styleload.css" rel="stylesheet" type="text/css">

<style>
  #video_full{
	position: fixed;
	width: 100%;
	height: 100%;
	z-index: 1000000;
	background-color: transparent;
	font-size: 2em;
	margin: 0;	
	padding: 0;
	border-radius: 0;
 }
</style>
<!--style>
 
	@media screen and (max-width: 480px) {
	#box
	{
    transform: rotate(90deg);
    -webkit-transform:rotate(90deg); /* Safari and Chrome */
    -moz-transform:rotate(90deg); /* Firefox */
	/*width:400px;
	height:300px;*/
	//margin-top:100px;
	}
	
	#videoo{
    right: 0;
    bottom: 0;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: -100;
	//z-index: 2147483647;
    overflow: visible;
	margin-top:-10px;
	
    transform: rotate(45deg);
    -webkit-transform:rotate(90deg); /* Safari and Chrome */
    -moz-transform:rotate(90deg); /* Firefox */
	}
	#video{
	transform: rotate(45deg);
	}
	
	}
</style-->
<!--style>
	@media screen and (max-width: 480px) {
		div{
			width:100%;
		}
	
	}
</style-->
</head>
<body onload="init();">


<div id="results" style="width:100%;"></div>

<div align="left">
<a data-role="button" class="load_more" id="load_more_button" href="#" >load More</a>
 <!--input type="submit"  class="load_more" id="load_more_button" value="load More"></input>
<!--button class="load_more" id="load_more_button">load More</button-->
<!--div class="animation_image" style="display:none;"><img src="resources/img/ajax-loader.gif"> Loading...</div-->
</div>

</body>
</html>