

$( "#lot-address" ).focusout(function() {
   
    var address = $('#lot-address').val();
	get_coord(function(output){
		if(output[0].length<11){
			for (i = output[0].length; i < 11; i++) { 
			    output[0] += "0";
			}
		}
		if(output[1].length<11){
			for (i = output[1].length; i < 11; i++) { 
			    output[1] += "0";
			}
		}
		$('#lot-coordinates').val(output);
		//console.log(output + 'qwe');
	}, address);
	
  
});



function get_coord(handleData, addr){
    //var addr = "Севастополь ул. Большая Морская 12/2";
	if (addr === undefined || addr === null || addr === "") {
		return false;
	}
    var coord = "";
    var url = "http://geocode-maps.yandex.ru/1.x/?geocode="+addr;
    jQuery.ajax({
        //dataType: 'xml',
        url: url,//url Р°РґСЂРµСЃ С„Р°Р№Р»Р° РѕР±СЂР°Р±РѕС‚С‡РёРєР°
        crossDomain: true, // enable this
        success:function (data) {//РІРѕР·РІСЂР°С‰Р°РµРјС‹Р№ СЂРµР·СѓР»СЊС‚Р°С‚ РѕС‚ СЃРµСЂРІРµСЂР°
            coord = jQuery(data).find('Point pos').first().text();
            var cc = coord.split(' ');
				handleData([cc[1], cc[0]]);
        }
    });
}


/*
function handleAjaxLink(e) {
 
    e.preventDefault();
 
    var
        $link = $(e.target),
        callUrl = $link.attr('href'),
        formId = $link.data('formId'),
        onDone = $link.data('onDone'),
        onFail = $link.data('onFail'),
        onAlways = $link.data('onAlways'),
        ajaxRequest;
 
 
    ajaxRequest = $.ajax({
        type: "post",
        dataType: 'json',
        url: callUrl,
        data: (typeof formId === "string" ? $('#' + formId).serializeArray() : null)
    });
 
    // Assign done handler
    if (typeof onDone === "string" && ajaxCallbacks.hasOwnProperty(onDone)) {
        ajaxRequest.done(ajaxCallbacks[onDone]);
        alert('done');
    }
 
    // Assign fail handler
    if (typeof onFail === "string" && ajaxCallbacks.hasOwnProperty(onFail)) {
        ajaxRequest.fail(ajaxCallbacks[onFail]);
        alert('faile');
    }
 
    // Assign always handler
    if (typeof onAlways === "string" && ajaxCallbacks.hasOwnProperty(onAlways)) {
        ajaxRequest.always(ajaxCallbacks[onAlways]);
        alert('always');
    }
 
}

var ajaxCallbacks = {
    'simpleDone': function (response) {
    	 alert('simpleDone');
        // This is called by the link attribute 'data-on-done' => 'simpleDone'
        console.dir(response);
        $('#css3buttons').html(response.body);
    }
}*/


function deleteAjax(e, id)
{
	 $.ajax({
       url: $(e).attr('href'),
       type: 'post',
       dataType: 'json',
       success: function (data) {
          //console.log(data);
         $.pjax.reload({container: id});
          
       }
  });
}/*
$(".deleteBranch" ).click(function(event) {
	event.preventDefault();
	deleteBranch(this);
	console.log("ajax");
});*/





