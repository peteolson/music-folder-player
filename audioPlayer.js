var vid = document.getElementById("audioPlayer");

vid.onplaying = function() {
	filename = vid.currentSrc.split('\\').pop().split('/').pop();
    filename = filename.substring(0, filename.lastIndexOf('.'));
	document.getElementById("now_playing").innerHTML = unescape(filename);
};


function audioPlayer(){
            var currentSong = 0;
                       
            $("#audioPlayer")[0].src = $("#playlist li a")[0];
            
            $("#audioPlayer")[0].play();
            $("#playlist li a").click(function(e){
				 
               e.preventDefault(); 
               $("#audioPlayer")[0].src = this;
               $("#audioPlayer")[0].play();
               $("#playlist li").removeClass("current-song");
                
                $(this).parent().addClass("current-song");
                currentSong = $(this).parent().index();
                
               
            });
            
            $("#audioPlayer")[0].addEventListener("ended", function(){
               currentSong++;
                if(currentSong == $("#playlist li a").length)
                    currentSong = 0;
                $("#playlist li").removeClass("current-song");
                $("#playlist li:eq("+currentSong+")").addClass("current-song");
                $("#audioPlayer")[0].src = $("#playlist li a")[currentSong].href;
                $("#audioPlayer")[0].play();
                
            });
            
            
        }
