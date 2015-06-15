
/*
data ={
	apiId: 4923090,
}
ShareSocial.vkInit(data);

-------------

data ={
	apiId: 860398493995294,
}
ShareSocial.fbInit(data);
*/


ShareSocial = {
	
	vkInit: function(data){
		VK.init({apiId: data.apiId, onlyWidgets: true});
	},
	
	fbInit: function(data){
		window.fbAsyncInit = function() {
			FB.init({
			  appId      : data.apiId,
			  xfbml      : true,
			  version    : 'v2.3'
			});
		};
	},
	
	wiretapping: function(ReturnAlert){
		console.log('wiretapping');
		
		
		$(window).on("share.success",
			function(){
				ReturnAlert.lotSuccess();
				console.log('success');
				$(window).off("share.success");
				$(window).off("share.error");
			}
		);
		

		$(window).on("share.error",
			function(){
				ReturnAlert.lotError();
				console.log('error');
				$(window).off("share.error");
				$(window).off("share.success");
			}
		);
	},
	
	
	fb: function(data)
	{
		FB.ui({
			method: 'feed',
			language: 'russian',
			name: data.name,
			caption: data.caption,
			link: data.link,
			picture: data.picture,
			description: data.description
			}, function (response) {
			if (response && (response.post_id || (response instanceof Array))) {
				 $(window).trigger("share.success");
			} else {
			   $(window).trigger("share.error");
			}
		});	
	},
	
  	vk:function(a)
	{
		/*a = {
		  	'url': location.href,
		  	'message': 'messege spevv',
		  	'title': 'title spevv',
		  	'image': 'https://www.google.com.ua/images/srpr/logo11w.png',
		}*/
  
    	var url="https://vk.com/share.php?url="+encodeURIComponent(a.url)+"&title="+encodeURIComponent(a.message)+"&description="+encodeURIComponent(a.title)+"&image="+encodeURIComponent(a.image)+"&noparse=true";
		this.openPopup(url);
		
		$(window).on("share.window.closed",
			function(){
				$(window).off("share.window.closed");
				VK.api(
					"wall.get",	{'count':1},
					function(data) { 
						var c={};
				  		if (data.response) { 
				  			if(data.response[1].attachment.link.url == a.url ){
								$(window).trigger("share.success");
							}
							else{
								$(window).trigger("share.error");
							}
				  		}
				  	} 
				);
			})
	},
	
	
	
	onOkMessage:function(a)
	{
		var b=a.originalEvent;
		if("http://connect.ok.ru"==b.origin&&"loaded"!=b.data)
		{
			try
			{
				var c=$.parseJSON(b.data);
				console.log(c);
				if(c.error)return void $(window).trigger("share.error")
			}catch(d)
			{
			}
			console.log('share.success write');
			$(window).trigger("share.success");
			//console.log('share.success write');
		}
	},
	
	
	ok:function(a)
	{
		$(window).on("message",this.onOkMessage),
		$(window).on("share.window.closed",
			function(){
				$(window).trigger("share.error");
				$(window).off("share.window.closed");
			})
		this.openPopup(a)
	},
	

	openPopup : function(url)
	{
		var leftvar = (screen.width-626)/2;
	  	var topvar = (screen.height-436)/2;
		var b=window.open(url,"","toolbar=0,status=0,scrollbars=1,width=626,height=436,left="+leftvar+",top="+topvar+'"');
		c=setInterval(function(){try{(null==b||b.closed)&&(clearInterval(c),$(window).trigger("share.window.closed"))}catch(url){}},500)
	}

}




