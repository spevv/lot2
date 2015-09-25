function afterAjaxListViewUpdate(){
    console.log('afterAjaxListViewUpdate');
    $('#infscr-loading').remove();

    $('.load-time').each(
        function( i, val ) {
            //classTime = $(this).attr('class');
            time = $(this).html();
            $(this).countdown(time, function(event) {
                $(this).text(
                    event.strftime('%D %H %M')
                );
            })
            .on('finish.countdown',function() {
            	location.reload();
            })
            .on('update.countdown', function(event) {
			   
			   
var format = "";
				                if(event.offset.minutes > 0){
				                	if(event.offset.minutes >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
									}
				                	 
				                }
				                else
				                {
									format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
								}
				                if(event.offset.hours > 0)
				                {
				                	if(event.offset.hours  >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
									}
				                	 
				                } 
				                 else
				                {
									format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
								}
				                /*if(event.offset.days > 0)
				                {
				                	if(event.offset.days  >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"day\">%-D</span></div> " + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"day\">0%-D</span></div> " + format;
									}
				                	 
				                }  */
				                if(event.offset.weeks  > 0)
				                {
				                	var days = ((event.offset.weeks*7)+event.offset.days);
				                	if(days  >= 10)
				                	{
				                		var format = "";
						                if(event.offset.minutes > 0){
						                	if(event.offset.minutes >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
											}
						                	 
						                }
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
										}
						                if(event.offset.hours > 0)
						                {
						                	if(event.offset.hours  >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
											}
						                	 
						                } 
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
										}
										 format =  "<div class=\"wrt\"><span class=\"day\">"+days +"</span></div> "+ format ;
									}
									else
									{
										var format = "";
						                if(event.offset.minutes > 0){
						                	if(event.offset.minutes >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
											}
						                	 
						                }
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
										}
						                if(event.offset.hours > 0)
						                {
						                	if(event.offset.hours  >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
											}
						                	 
						                }
						                 else
						                {
											format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
										}
										format =  "<div class=\"wrt\"><span class=\"day\">0"+days  +"</span></div> "+ format ;
									}
				                	 
				                }
				                else
				                {
									if(event.offset.days > 0)
					                {
					                	if(event.offset.days  >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"day\">%-D</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"day\">0%-D</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"day\">00</span></div> " + format;
									} 
								}
								
								if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours < 25 ) {
									format = "";
									if(event.offset.seconds > 0)
									{
					                	if(event.offset.seconds >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"sec\">0%-S</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"sec\">00</span></div> " + format;
									}
									if(event.offset.minutes > 0)
									{
					                	if(event.offset.minutes >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
									}
					                if(event.offset.hours > 0)
					                {
					                	if(event.offset.hours  >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
										}
					                	 
					                } 
					                 else
					                {
										format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
									}
				                } 
				                
								if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes  < 60 ) {
									format = "";
									if(event.offset.seconds > 0)
									{
					                	if(event.offset.seconds >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"sec\">0%-S</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"sec\">00</span></div> " + format;
									}
									if(event.offset.minutes > 0)
									{
					                	if(event.offset.minutes >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
									}
				                } 
				                if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes == 0 && event.offset.seconds < 60) {
				                    format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div>";
				                }

				                $(this).html(event.strftime(format));
			   
			   
			   
			});
            $(this).removeClass();
            //console.log(val);
        }
    );
}


$('a[href^="#"]').click(function(){
	//Сохраняем значение атрибута href в переменной:
	var target = $(this).attr('href');
	$('html, body').animate({scrollTop: $(target).offset().top}, 800);
	return false;
});

$( "#toSweet" ).click(function(event){
    event.preventDefault();

    swal(
        "Что такое авторизация через соцсеть?",
        "Краткое описание авторизации через соцсети."
    );

});

$(function () {
  $.scrollUp({
    scrollName: 'scrollUp', // Element ID
    topDistance: '300', // Distance from top before showing element (px)
    topSpeed: 300, // Speed back to top (ms)
    animation: 'fade', // Fade, slide, none
    animationInSpeed: 200, // Animation in speed (ms)
    animationOutSpeed: 200, // Animation out speed (ms)
    scrollText: 'Наверх', // Text for element
    activeOverlay: false // Set CSS color to display scrollUp active point, e.g '#00FFFF'
  });
});




Share = {
    /**
     * Показать пользователю дилог шаринга в сооветствии с опциями
     * Метод для использования в inline-js в ссылках
     * При блокировке всплывающего окна подставит нужный адрес и ползволит браузеру перейти по нему
     *
     * @example <a href="" onclick="return share.go(this)">like+</a>
     *
     * @param Object _element - элемент DOM, для которого
     * @param Object _options - опции, все необязательны
     */
    go: function(_element, _options) {
        var
            self = Share,
            options = $.extend(
                {
                    type:       'vk',    // тип соцсети
                    url:        location.href,  // какую ссылку шарим
                    count_url:  location.href,  // для какой ссылки крутим счётчик
                    title:      document.title, // заголовок шаринга
                    image:      'http://yii.awam-it.ru/uploads/files/so3_-_kopiya.jpg',             // картинка шаринга
                    text:       'Какой то текст'             // текст шаринга
                },
                $(_element).data(), // Если параметры заданы в data, то читаем их
                _options            // Параметры из вызова метода имеют наивысший приоритет
            );

        if (self.popup(link = self[options.type](options)) === null) {
            // Если не удалось открыть попап
            if ( $(_element).is('a') ) {
                // Если это <a>, то подставляем адрес и просим браузер продолжить переход по ссылке
                $(_element).prop('href', link);
                return true;
            }
            else {
                // Если это не <a>, то пытаемся перейти по адресу
                location.href = link;
                return false;
            }
        }
        else {
            // Попап успешно открыт, просим браузер не продолжать обработку
            return false;
        }
    },

    // ВКонтакте
    vk: function(_options) {
        var options = $.extend({
                url:    location.href,
                title:  document.title,
                image:  '',
                text:   ''
            }, _options);

        return 'http://vkontakte.ru/share.php?'
            + 'url='          + encodeURIComponent(options.url)
            + '&title='       + encodeURIComponent(options.title)
            + '&description=' + encodeURIComponent(options.text)
            + '&image='       + encodeURIComponent(options.image)
            + '&noparse=true';
    },

    // Одноклассники
    ok: function(_options) {
        var options = $.extend({
                url:    location.href,
                text:   'Простой текст'
            }, _options);

        return 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1'
            + '&st.comments=' + encodeURIComponent(options.text)
            + '&st._surl='    + encodeURIComponent(options.url);
    },

    // Facebook
    fb: function(_options) {
        var options = $.extend({
                url:    location.href,
                title:  document.title,
                image:  '',
                text:   ''
            }, _options);
            
            
       /*  return 'http://www.facebook.com/dialog/feed?app_id=860398493995294' + 
		    '&link=' + options.url +
		    '&picture=' + options.image +
		    '&name=' + options.title + 
		    '&caption=' + '' + 
		    '&description=' + options.text + 
		    '&redirect_uri=' + options.url+ 'PopupClose.html' + 
		    '&display=popup'; */

        return 'http://www.facebook.com/sharer.php?s=100'
            + '&p[title]='     + encodeURIComponent(options.title)
            + '&p[summary]='   + encodeURIComponent(options.text)
            + '&p[url]='       + encodeURIComponent(options.url)
            + '&p[images][0]=' + encodeURIComponent(options.image);
    },

    // Живой Журнал
    lj: function(_options) {
        var options = $.extend({
                url:    location.href,
                title:  document.title,
                text:   ''
            }, _options);

        return 'http://livejournal.com/update.bml?'
            + 'subject='        + encodeURIComponent(options.title)
            + '&event='         + encodeURIComponent(options.text + '<br/><a href="' + options.url + '">' + options.title + '</a>')
            + '&transform=1';
    },

    // Твиттер
    tw: function(_options) {
        var options = $.extend({
                url:        location.href,
                count_url:  location.href,
                title:      document.title
            }, _options);

        return 'http://twitter.com/share?'
            + 'text='      + encodeURIComponent(options.title)
            + '&url='      + encodeURIComponent(options.url)
            + '&counturl=' + encodeURIComponent(options.count_url);
    },

    // Mail.Ru
    mr: function(_options) {
        var options = $.extend({
                url:    location.href,
                title:  document.title,
                image:  '',
                text:   ''
            }, _options);

        return 'http://connect.mail.ru/share?'
            + 'url='          + encodeURIComponent(options.url)
            + '&title='       + encodeURIComponent(options.title)
            + '&description=' + encodeURIComponent(options.text)
            + '&imageurl='    + encodeURIComponent(options.image);
    },

    // Открыть окно шаринга
    popup: function(url) {
    	var leftvar = (screen.width-626)/2;
  		var topvar = (screen.height-436)/2;

        return window.open(url,'','toolbar=0,status=0,scrollbars=1,width=626,height=436,left='+leftvar+',top='+topvar+"'");
    }
}



// Все элементы класса .social_share считаем кнопками шаринга
$(document).on('click', '.social_share', function(){
	//console.log(this);
    Share.go(this);
});




$('.owl-carousel').owlCarousel({
    //animateOut: 'slideOutDown',
   // animateIn: 'flipInX',
    items:1,
    loop:true,
    margin:0,
    //stagePadding:0,
    smartSpeed:450,
    nav:true,
    navText:["",""]
});














