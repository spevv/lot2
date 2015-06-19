<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'mainEmail' => 'support@example.com', // с какой почты будет вестись рассылка
    'user.passwordResetTokenExpire' => 3600,
    'cronTime' => 5, // через какое время будет выполняться крон
    'lot.timeToFinish' => 20, // минуты до конца лота (отправка сообшения) должно быть (lot.timeToFinish - cronTime) = 10 ~ 15
    
    'emailText' => [
    	// победителю
    	'toWinner' => [
    		'view' => 'email-html',
    		'subject' => 'Вы победили',
			'messege' => 'Вы победили',
    	],
    	// проигравшим
    	'toLoser' => [
    		'view' => 'email-html',
    		'subject' => 'Вы проиграли',
			'messege' => 'Вы проиграли %s',
    	],
    	// перебили ставку
    	'slewRate' => [
    		'view' => 'email-html',
    		'subject' => 'Вашу ставку перебили',
			'messege' => 'Вашу ставку перебили',
    	],
    	// за несколько минут до окончание лота
    	'endsInMinutes' => [
    		'view' => 'email-html',
    		'subject' => 'До конца лота осталось совсем не много',
			'messege' => 'До конца осталось %s минут',
    	],
    	// оплата лота  
    	'toPay' => [
    		// за 8 часов до конца
    		'first' => [
    			'view' => 'email-html',
    			'subject' => 'Данные для оплаты лота',
				'messege' => 'Что бы оплатить лот, перейдите по сслыке %s',
    		],
    		// за 4 часов до конца
    		'second' => [
    			'view' => 'email-html',
    			'subject' => 'Данные для оплаты лота',
				'messege' => 'Что бы оплатить лот, перейдите по сслыке %s',
    		],
    		// за 1 час до конца
    		'third' => [
    			'view' => 'email-html',
    			'subject' => 'Данные для оплаты лота',
				'messege' => 'Что бы оплатить лот, перейдите по сслыке %s',
    		],
    	],
    	// рассытка по инересам
    	'interest' => [
    		'view' => 'email-html',
    		'subject' => 'Вашу ставку перебили',
			'messege' => 'Вашу ставку перебили',
    	],
    	// рассытка подписщикам
    	'follower' => [
    		'view' => 'email-html',
    		'subject' => 'Вашу ставку перебили',
			'messege' => 'Вашу ставку перебили',
    	],
    ],
];
