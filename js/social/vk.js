


VK.init({apiId: 4923090, onlyWidgets: true});

ShareSocial = {
	

  	vk:function(a)
	{
		a = {
		  	'url': location.href,
		  	'message': 'messege spevv',
		  	'title': 'title spevv',
		  	'image': 'https://www.google.com.ua/images/srpr/logo11w.png',
		}
  
    	var url="https://vk.com/share.php?url="+encodeURIComponent(a.url)+"&title="+encodeURIComponent(a.message)+"&description="+encodeURIComponent(a.title)+"&image="+encodeURIComponent(a.image)+"&noparse=true";
		this.openPopup(url);
		
		/*$(window).on("share.success",
			function(){
				console.log('success');
			}
		);*/

		$(window).on("share.window.closed",
			function(){
				$(window).off("share.window.closed");
				VK.api(
					"wall.get",	{'count':1},
					function(data) { 
						var c={};
				  		if (data.response) { 
				  			if(data.response[1].text == a.message ){
								console.log('Ура');
								$(window).trigger("share.success");
							}
							else{
								console.log('нет');
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
				if(c.error)return void $(window).trigger("share.error")
			}catch(d)
			{
			}
			$(window).trigger("share.success");
		}
	},
	
	
	ok:function(a)
	{
		
		
		
	appId = '1137718016';
	secretKey = '6320D57ED9A1D769B992081B';
	
	//attachment = '{"media": [{"type": "text", "text": "hello world!"}]}';
	//attachment = '{"media": [{"type": "link","url": "http://yii.awam-it.ru"},{"type": "text", "text": "hello world!"}, {"type": "app","text": "Text above image","images": [{"url": "http://r.mradx.net/img/38/F3C336.jpg","mark": "prize_1234","title":"Hover Text!"}],"actions": [{"text":"Hello","mark":"hello"}]} ]}';
	attachment = '{"media": [{"type": "link","url": "http://yii.awam-it.ru"},{"type": "text", "text": "hello world!"}]}';
	sigSource = 'st.attachment=' + attachment  + secretKey;
	sign = CryptoJS.MD5(sigSource);

		
		
		$(window).on("message",this.onOkMessage),
		$(window).on("share.success",
			function(){
				$(window).off("share.error");
				$(window).off("share.success");
				console.log('ok success');
		}),
		$(window).on("share.error",
			function(){
				$(window).off("share.error");
				$(window).off("share.success");
				console.log('ok error');
				
			});
			
		$(window).on("share.window.closed",
			function(){
				console.log('ok error');
				$(window).off("share.window.closed");
			})
			
		var c='http://connect.ok.ru/dk?st.cmd=WidgetMediatopicPost'+
			'&st.app='+ appId +
			'&st.attachment='+ encodeURIComponent(attachment)+
			'&st.signature='+sign+
			"&st.silent=on";
			console.log(c);
		this.openPopup(c)
	},
	

	

	openPopup : function(url)
	{
		var leftvar = (screen.width-626)/2;
	  	var topvar = (screen.height-436)/2;
		var b=window.open(url,"","toolbar=0,status=0,scrollbars=1,width=626,height=436,left="+leftvar+",top="+topvar+'"');
		c=setInterval(function(){try{(null==b||b.closed)&&(clearInterval(c),$(window).trigger("share.window.closed"))}catch(url){}},500)
	}

}




