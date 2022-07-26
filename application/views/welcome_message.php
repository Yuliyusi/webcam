<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	p {
		margin: 0 0 10px;
		padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}

	    button{

    width:200px;
    margin:20px;
    font-weight:bold;
    height:100px;

    }
	</style>
</head>
<body>

<div id="container">
	<h1>PAGE DE VIDEO SURVEILLANCE</h1>

    <div style='display:inline-block'>

     <video id="sourcevid" width='400' autoplay="true"></video>

     <div id="message" style='height:20px;width:350px;margin:5px;'>message:</div>
    </div>

    <canvas id="cvs" style='display:inline-block'></canvas>

    <div>
     <button onclick='ouvrir_camera()'>ouvrir camera</button> 
     <button onclick='fermer()' >fermer camera</button>
     <br>
     <button onclick='photo()' >prise de photo</button>
     <button onclick='sauver()' >sauvegarder</button>
     <button onclick='submit()' >envoyer</button>
    </div>

    <div id="jaxa" style='width:80%;margin:5px;'>Mes Images:</div>
    <div>
     <?php
      foreach ($mesimage as $key) {
     	// code...
     	echo '<img src="data:image/png;base64,' . base64_encode($key['imageblob']) . '" height="600" width="200" alt="mon image" title="image"/> ';

     } ?>
    </div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
   <script>

    function ouvrir_camera(){

     navigator.mediaDevices.getUserMedia({ audio: false, video: { width: 400 } }).then(function(mediaStream) {

      var video = document.getElementById('sourcevid');
      video.srcObject = mediaStream;

      var tracks = mediaStream.getTracks();

     // document.getElementById("message").innerHTML="message: "+tracks[0].label+" connecté"

      console.log(tracks[0].label)
      console.log(mediaStream)

      video.onloadedmetadata = function(e) {
       video.play();
      };
       
     }).catch(function(err) { console.log(err.name + ": " + err.message);

     document.getElementById("message").innerHTML="message: connection refusé"});
    }

    function photo(){

     var vivi = document.getElementById('sourcevid');
     //var canvas1 = document.createElement('canvas');
     var canvas1 = document.getElementById('cvs')
     var ctx =canvas1.getContext('2d');
     canvas1.height=vivi.videoHeight
     canvas1.width=vivi.videoWidth
     console.log(vivi.videoWidth)
     ctx.drawImage(vivi, 0,0, vivi.videoWidth, vivi.videoHeight);

     //var base64=canvas1.toDataURL("image/png"); //l'image au format base 64
     //document.getElementById('tar').value='';
     //document.getElementById('tar').value=base64;
    }

    function sauver(){

     if(navigator.msSaveOrOpenBlob){

      var blobObject=document.getElementById("cvs").msToBlob()
alert (blobObject)
      window.navigator.msSaveOrOpenBlob(blobObject, "image.png");
     }
     else{
      var canvas = document.getElementById("cvs");
      var elem = document.createElement('a');
      elem.href = canvas.toDataURL("image/png");
      elem.download = "nom.png";
      var evt = new MouseEvent("click", { bubbles: true,cancelable: true,view: window,});
      elem.dispatchEvent(evt);
     }
    }


    
    function fermer(){

     var video = document.getElementById('sourcevid');
     var mediaStream=video.srcObject;
     console.log(mediaStream)
     var tracks = mediaStream.getTracks();
     console.log(tracks[0])
     tracks.forEach(function(track) {
      track.stop();
      //document.getElementById("message").innerHTML="message: "+tracks[0].label+" déconnecté"
     });

     video.srcObject = null;
    }


   </script>
   <script>


    async function submit() {
     var canvasElem = document.getElementById('cvs');
      let imageBlob = await new Promise(resolve => canvasElem.toBlob(resolve, 'image/png'));

      let formData = new FormData();
      formData.append("image", imageBlob, "image.png");

      let response = await fetch('<?= base_url('index.php/Welcome/saveMyimages') ?>', {
        method: 'POST',
        body: formData
      });
      let result = await response.json();
      alert(result.message);
    }

  </script>
</body>
</html>
