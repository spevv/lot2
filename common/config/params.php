<?php
return [
    'adminEmail' => 'info@eduhot.biz',
    'supportEmail' => 'info@eduhot.biz',
    //'mainEmail' => 'developer.awam@gmail.com', // с какой почты будет вестись рассылка
    'mainEmail' => ['info@eduhot.biz' => 'EduHot.biz'], // с какой почты будет вестись рассылка
    'user.passwordResetTokenExpire' => 3600,
    'cronTime' => 5, // через какое время будет выполняться крон
    'lot.timeToFinish' => 9, // минуты до конца лота (отправка сообшения) должно быть (lot.timeToFinish - cronTime) = 10 ~ 15
    'lot.firstStepTimeToComment' => 7, // 7 дней, после 7 дней будут отправляться email на комменты
    'lot.secondStepTimeToComment' => 3, // следущая отправка сообщения через 3 дня, если только одна отправка то установить ""
    'delivery.countEmailInEmail' => 15, //$countEmail = 10; // количество emails которые можно отправить в одном письме = 15
    'delivery.interesStep' => 5, //шаг интереса, с которого начинается отправка email
    //'emailName' => 'EduHot.biz',
    'emailText' => [
    	// победителю
    	'toWinner' => [
    		'view' => 'email-html',
    		'subject' => 'Вы выиграли торги!',
			'messege' => '<h2>Здраствуйте, %s</h2><p>Поздравляем! Вы выиграли торги за лот "%s".</p><p>Для завершение сделки внесите оплату %s руб до %s по Москве.</p><a class="button" href="%s">Перейти к оплате</a>',
    	],
    	// проигравшим
    	'toLoser' => [
    		'view' => 'email-html',
    		'subject' => 'Лот сыгран',
			'messege' => '<h2>Здраствуйте, %s</h2><p>Торги за лот "%s" завершены.</p><p>Победила ставка %s руб от пользователя %s.</p><p>Не падайте духом! Вас ждут другие лоты.</p><a class="button" href="%s">Смотреть другие лоты</a>',
    	],
    	// перебили ставку
    	'slewRate' => [
    		'view' => 'email-html',
    		'subject' => 'Вашу ставку перебили!',
			'messege' => '<h2>Здраствуйте, %s</h2><p>Вашу ставку на лот "%s" перебили.</p><p>Лидирует пользователь %s.</p><p>Торги по лоту заканчиваются %s по Москве.</p><a class="button" href="%s">Перейти к лоту</a>',
    	],
    	// за несколько минут до окончание лота
    	'endsInMinutes' => [
    		'view' => 'email-html',
    		'subject' => 'До конца лота осталось совсем немного',
			'messege' => '<h2>Здраствуйте, %s</h2><p>Напоминаем, что до конца лота осталось %s, поспешите сделать свою ставку.</p><a class="button" href="%s">Перейти к торгам</a>',
    	],
    	// оплата лота  
    	'toPay' => [
    		// за 8 часов до конца
    		'first' => [
    			'view' => 'email-html',
    			'subject' => 'Напоминаем об оплате - 1',
				'messege' => '<h2>Здраствуйте, %s</h2><p>Напоминаем, что до %s нужно оплатить лот "%s".</p><a class="button" href="%s">Перейти к оплате</a>',
    		],
    		// за 4 часов до конца
    		'second' => [
    			'view' => 'email-html',
    			'subject' => 'Напоминаем об оплате - 2',
				'messege' => '<h2>Здраствуйте, %s</h2><p>Напоминаем, что до %s нужно оплатить лот "%s".</p><a class="button" href="%s">Перейти к оплате</a>',
    		],
    		// за 1 час до конца
    		'third' => [
    			'view' => 'email-html',
    			'subject' => 'Напоминаем об оплате - 3',
				'messege' => '<h2>Здраствуйте, %s</h2><p>Напоминаем, что до %s нужно оплатить лот "%s".</p><a class="button" href="%s">Перейти к оплате</a>',
    		],
    		// вы не можете оплачивать счет
    		'close' => [
    			'view' => 'email-html',
    			'subject' => 'Вами просрочена оплата по лоту "%s".',
				'messege' => '<h2>Здраствуйте, %s</h2><p>Вы не оплатили лот "%s". Возможность оплаты закрыта, лот завершен.</p>',
    		],
    		//оплачено
    		'payed' => [
    			'view' => 'email-html',
    			'subject' => 'Вы успешно оплатили "%s".',
				'messege' => '<h2>Здраствуйте, %s</h2><p>Вы оплатили лот "%s". Ваш код <strong>%s</strong>.</p>',
    		],
    	],
    	// рассытка по инересам
    	'followerFirst' => [
    		'view' => 'email-html',
    		'subject' => 'Рассилка оформлена',
			'messege' => '<h2>Здраствуйте!</h2><p>Мы рады, что вы присоеденились к <a href="%s">Eduhot.biz</a>.</p><p>Ознакомьтесь с нашим сервисом и приступайте к торгам.</p><p>Удачи!</p>',
			'follower' => '1',
    	],
    	// рассытка по инересам
    	'interest' => [
    		'view' => 'email-html',
    		'subject' => 'Новое предложение "%s"',
			'messege' => '<h2>Здраствуйте!</h2><p> У нас новое предложение -  <a href="%s">"%s"</a> присоединяйтесь к торгам.</p><p>Успей сделать ставку.</p><p>Удачи!</p>',
    	],
    	// рассытка подписщикам
    	/*'follower' => [
    		'view' => 'email-html',
    		'subject' => 'Рассылка подписщикам',
			'messege' => 'Вашу ставку перебили',
    	],*/
    	/// оставить свой коммент
    	'emailToComment' => [
    		'view' => 'email-html',
    		'subject' => 'Оставьте отзыв',
			'messege' => '<h2>Здраствуйте, %s</h2><p>Оставте отзыв об пройденом вами курсе.</p><a class="button" href="%s">Оставить отзыв</a>',
    	],
    ],
];
