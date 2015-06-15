
//console.log('include Ok');

function socialPostOK()
{
	
	//returnUrl = location.href;
	appId = '1137718016';
	var secretKey = '6320D57ED9A1D769B992081B';
	attachment = '{"media": [{"type": "text", "text": "hello world!"}]}';
	
	
	/*if(returnUrl)
	{
		sigSource = 'st.attachment=' + attachment + "st.return=" + returnUrl  + secretKey;
	}
	else
	{*/
		sigSource = 'st.attachment=' + attachment  + secretKey;
	/*}*/
	sign = CryptoJS.MD5(sigSource);
	
	//returnUrl = '&st.return=' + encodeURIComponent(returnUrl);
	
	
//md5("st.attachment=" + attachment + "st.return=" + returnUrl + secretKey);

	var st = 'http://connect.ok.ru/dk?st.cmd=WidgetMediatopicPost'+
		'&st.app='+ appId +
		'&st.attachment='+ encodeURIComponent(attachment)+
		'&st.signature='+sign;
		
	var leftvar = (screen.width-626)/2;
  	var topvar = (screen.height-436)/2;

    return window.open(st,'','toolbar=0,status=0,scrollbars=1,width=626,height=436,left='+leftvar+',top='+topvar+"'");
	
	
	//console.log(st);
}


/*
document.addEventListener('DOMContentLoaded', function () {
	console.log('DOMContentLoaded');
        window.addEventListener('message', function (event) {
        	console.log('message');
        	console.log(event.data);
        	if (event.data['id']) {
        		console.log('in id');
                /*var holder = document.getElementById('WidgetOKHolder');
                holder.innerHTML = '';
                holder.style.display = 'none';*/
          /*  }
            if (event.data == 'hideWidgetIframe') {
                var holder = document.getElementById('WidgetOKHolder');
                holder.innerHTML = '';
                holder.style.display = 'none';
            }
        }, false);
    }, false);
    
    
   */ 


/*


function listenForShare() {
    if (window.addEventListener) {
        window.addEventListener('message', onShare, false);
    } else {
        window.attachEvent('onmessage', onShare);
    }
}
function onShare(e) {
    var args = e.data.split("$");
    if (args[0] == "ok_shared") {
        alert(args[1]); // Вывод идентификатора фрейма кнопки - в случае нескольких кнопок на одной странице, по нему можно определить какая именно была кликнута
    }
}
listenForShare();
*/