<!DOCTYPE html>
<html>
<head>
<title>GouravRR - My Facebook APP</title>
<meta charset="UTF-8">
</head>
<body>
<script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
<script>
var accessToken = '';
var FrstAlbmID = '';
var AlbmsIDs = [];
/*login status check function*/
  function statusChangeCallback(response) {
    //console.log('statusChangeCallback');
    //console.log(response);
    accessToken = response.authResponse.accessToken;
    //console.log(accessToken);
    if (response.status === 'connected') {
      testAPI();
      // getPhotos();
      document.getElementById('ng-logout').style.display = 'block';
      document.getElementById('login-fb').style.display = 'none';
    } else {
      document.getElementById('login-fb').style.display = 'block';
    }
  }

  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }
/*login status check function*/

/*connection with fb function*/
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '440274999688651',
      cookie     : true,  // enable cookies to allow the server to access 
                          // the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v2.10' // use graph api version 2.8
    });

    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  };
/*connection with fb function*/

/*load api sdk function*/
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
/*load api sdk function*/

/*login function*/
  var UsrID;
  function testAPI() {
    FB.api('/me', function(response1) {
      document.getElementById('ng-fbdata').innerHTML = '<p id="'+response1.id+'">Hello, <br><strong>'+response1.name+'</strong></p>';
      UsrID = response1.id;
    });

    var html= '';
    FB.api('/me/albums', function(resp) {
        html+="<h3>Your Albums</h3><div class='all-albums'>";
        for (var i=0, l=resp.data.length; i<l; i++){
          var album = resp.data[i];
          AlbmsIDs.push(album.id);
          html+=  '<div class="single-album" id="albm-'+album.id+'"><p><a href="#" data-aid="'+album.id+'"><strong>'+album.name+'</strong></a></p>';
          html+="</div>";
        }
        html+="</div>";
        document.getElementById("albums").innerHTML=html;
        FrstAlbmID = AlbmsIDs[0];
        FB.api(
            "/"+FrstAlbmID+"/photos",
            function (responseN) {
              if (responseN && !responseN.error) {
                console.log(responseN);
                var htmlN = '';
                var photosN = responseN["data"];
                for(var n=0; n<photosN.length; n++) {
                  var imageNID = photosN[n]["id"];
                  var ImgNurl = 'https://graph.facebook.com/'+imageNID+'/picture?access_token='+accessToken;
                  htmlN+= '<a href="'+ImgNurl+'" download="'+ImgNurl+'">';
                  htmlN+= '<img src="'+ImgNurl+'" style="height: 200px; width: 200px;padding: 10px;" alt="facebook-img" />';
                  htmlN+= '</a>';
                }
                document.getElementById("photos").innerHTML=htmlN;
              }
            }
        );
    });
  }
/*login function*/

jQuery(document).ready( function($) {
  /*append all images to one for an album*/
  $(document).on('click', '.single-album a', function(){
    var currAlbmId = $(this).data('aid');
    FB.api(
        "/"+currAlbmId+"/photos",
        function (responseNG) {
          if (responseNG && !responseNG.error) {
            console.log(responseNG);
            var htmlNG = '';
            var photosNG = responseNG["data"];
			var ImgssArr = '';
            for(var n=0; n<photosNG.length; n++) {
              var imageNIDG = photosNG[n]["id"];
              var ImgNurlG = 'https://graph.facebook.com/'+imageNIDG+'/picture?access_token='+accessToken;
              htmlNG+= '<a href="'+ImgNurlG+'" download="'+ImgNurlG+'">';
              htmlNG+= '<img src="'+ImgNurlG+'" style="height: 200px; width: 200px;padding: 10px;" />';
              htmlNG+= '</a>';
              ImgssArr+= ImgNurlG+', ';
            }
            document.getElementById("photos").innerHTML=htmlNG;

            $.ajax({
		        url: "createimg.php",
		        type : 'post',
			    dataType:'json',
			    data : {
			        ImgsArray : ImgssArr
			    },
		        success: function(response) {
		            console.log(response);
		        },
		        error: function(response) {

		        }
		    });

          }
        }
    );
  });
  /*append all images to one for an album*/

  /*logout function*/
  $('#ng-logout button').click(function(){
    FB.logout(function(response1) {
      alert("You are Now Logged out.");
      $('#ng-logout').css('display', 'none');
      location.reload();
    });
  });
  /*logout function*/
});
</script>
<style type="text/css">
  body {
    background: #e9ebee none repeat scroll 0 0;
    color: #4b4f56;
    font-family: Trebuchet MS, sans-serif;
  }
  .page-row {
    text-align: center;
  }
  #login-fb,#ng-logout {
    padding-bottom: 40px;
  }
  #login-fb > span {
      display: block;
      font-size: 14px;
      padding: 5px;
  }
  .all-albums > .single-album {
    display: inline-block;
    float: left;
    padding: 20px;
    width: 22%;
  }
  #photos img:hover {
	opacity: 0.5;
  }
</style>
<div class="page-row">
  <h1>Welcome to My Facebook APP</h1>
  <div id="login-fb">
    <fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>
    <span>Please Login to continue.</span>
  </div>

  <div id="ng-fbdata"></div>

  <div id="ng-logout" style="display: none;">
  <button style="cursor: pointer; background: transparent url('logout-facebook.png') repeat scroll 0% 0% / cover ; border: medium none; width: 118px; height: 40px; text-indent: -999999px;">Log Out</button>
  </div>

  <div id="albums"></div>
</div>

<div id="photos"></div>
</body>
</html>