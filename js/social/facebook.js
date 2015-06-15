

window.fbAsyncInit = function() {
	FB.init({
	  appId      : '860398493995294',
	  xfbml      : true,
	  version    : 'v2.3'
	});
	}; 



function socialPostFB()
{
	alert('socialPostFB');
  FB.ui({
    method: 'feed',
    language: 'russian',
    name: 'title',
    caption: 'caption 111',
    link: 'http://lot2.localhost/',
    picture: 'https://www.google.com.ua/images/srpr/logo11w.png',
    description: 'описание'
  }, function (response) {
    if (response && (response.post_id || (response instanceof Array))) {
      //postData.success();
      alert('s');
    } else {
      //postData.error();
       alert('e');
    }
    
        
    
  });

}