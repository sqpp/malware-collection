<?php
	include '_set.php';
	
	$post = file_get_contents('php://input');
	if (strlen($post) < 8)
		loadSite();
	$post = json_decode($post, true);
	$msg = $post['message'];
	$iskbd = !$msg;
	if ($iskbd)
		$msg = $post['callback_query'];
	$id = beaText(strval($msg['from']['id']), chsNum());
	if (strlen($id) == 0)
		exit();
	if (isUserBanned($id)) {
		botSend([
			'<b>Ваш аккаунт заблокирован, свяжитесь с Администрацией для разблокировки</b>',
		], $id);
		exit();
	}
	$timer = 1 - (time() - intval(getUserData($id, 'time')));
	if ($timer > 0)
		exit();
	setUserData($id, 'time', time());
	$text = $msg[$iskbd ? 'data' : 'text'];
	$login = $msg['from']['username'];
	$nick = htmlspecialchars($msg['from']['first_name'].' '.$msg['from']['last_name']);
	if ($iskbd)
		$msg = $msg['message'];
	$mid = $msg[$iskbd ? 'message_id' : 'id'];
	$chat = $msg['chat']['id'];
	$image = $msg['photo'][0]['file_id'];
	$member = $msg['new_chat_member'];
	$cmd = explode(' ', $text, 2);
	$keybd = false;
	$result = false;
	$edit = false;
	$status = fileRead("settings/work.txt");

	if($status == 1) {
		$status = "<b>🌕 Все работает,</b> можно воркать!";
	} else {
		$status = "<b>🌑 Все сломалось,</b> нельзя воркать!";
	}

	$btns = [
		'stmanual' => '📖 Мануалы по работе',
		'tools' => 'Другое',
		'profile' => 'Мой профиль',
		'settings' => '♻️ О проекте',
		'myitems' => '💼 Объявления',
		'additem' => '🔗 Создать',
		'sndmail' => 'Письма',
		'menusms' => 'Смс',
		'addparsec' => '',
		'addsavito' => '🎁 Парсер Авито',
		'addsyoula' => 'Парсер Юла',
		'addsolx' => 'Парсер OLX',
		'qrcode' => 'QR',
		'addsitem' => '📦 Объявление',
		'addtaxi' => '🏎 Поездки',
		'addstrack' => '',
		'back' => 'Назад',
		'smsavito' => '🎁 Авито',
		'addsrent' => '',
		'addbank' => '',
		'smsyoula' => '🛍 Юла',
		'smswhats' => '👥 Whatsapp',
		'smsviber' => '👥 Viber',
		'emlavito' => '🎁 Авито',
		'emlyoula' => '🛍 Юла',
		'emlbxbry' => '🚚 Boxberry',
		'emlcdek' => '🚛 СДЭК',
		'emlpochta' => '🗳 Почта',
		'emlpecom' => '✈️ ПЭК',
		'emlyandx' => '🚕 Яндекс',
		'emltordr' => '💸 Оплата',
		'emltrfnd' => '💫 Возврат',
		'emltsafd' => '🔒 Безоп. сделка',
		'emltcshb' => '💳 Получ. средств',
		'stgcard' => '💳 Карта',
		'pflbout' => '📤 Вывод',
		'pflhist' => '📋 История',
		'pflchck' => '🍫 Чек',
		'pflprfs' => '💰 Профиты',
		'outyes' => '✅ Подтвердить',
		'outno' => '❌ Отказаться',
		'itmdel' => '🗑 Удалить',
		'itmst1' => '⏳ Ожидает',
		'itmst2' => '🤟 Оплачен',
		'itmst3' => '💫 Возврат',
		'itmst4' => '💳 Получение',
		'itmedtnm' => '🏷 Название',
		'itmedtam' => '💸 Стоимость',
		'stgano1' => '🌕 Ник виден',
		'stgano0' => '🌑 Ник скрыт',
		'stgfsav' => '🎧 Фейк скриншоты поддержки',
		'stgrules' => '📜 Правила',
		'stgrefi' => '',
		'stgchks' => '🍫 Мои чеки',
		'adgoto1' => '📦 Перейти к объявлению',
		'adgoto2' => '🔖 Перейти к трек номеру',
		'adgoto3' => '🚚 Перейти к поездки',
		'adgoto4' => '💸 Перейти к чеку',
		'stglchat' => '🐬 Чат',
		'stglpays' => '💸 Выплаты',
		'outaccpt' => '📤 Выплатить',
		'jncreate' => '⚡️ Перейти к заполнению заявки',
		'jniread' => '✅ Подтверждаю',
		'jnremake' => '♻️ Заново',
		'jnsend' => '🚀 Отправить',
		'jnnoref' => '🌱 Никто',
		'joinaccpt' => '✅ Принять',
		'joindecl' => '❌ Отказать',
		'topshw1' => '💸 По общей сумме профитов',
		'topshw2' => '🤝 По профиту от рефералов',
		'smsrecv' => 'Активация',
		'smssend' => 'Отправка',
		'smscode' => '♻️ Обновить',
		'smscncl' => '❌ Отменить',
		'join_from1' => 'Форум',
		'join_from2' => 'Реклама',
		'level_skam1' => 'Низкий',
		'level_skam2' => 'Средний',
		'level_skam3' => 'Высокий',
		'otrisovka' => 'Отрисовка',
		// Кнопки где отдельный do и else
		'olxstr' => '🌏 OLX Страны',
		'newstr' => '👑 Новые страны',
		'dhlstr' => '🇪🇺 DHL',

	];
	if (getUserdata($id, 'bot') == 'rendering') {
		if ($text == '⬅️ Назад к основному боту') {
			setUserData($id, 'bot', 'main');
			$text = '/start';
		} elseif(substr($msg['chat']['id'], 0, 1) != '-') {
			chdir('rendering');
			include 'rendering/bot.php';
			die;
		}
	}

	function doSms($t, $t1, $t2) {
		global $id, $btns;
		$result = [
			'✅ <b>Успешно</b>',
			'',
			'🆔 <b>ID:</b> <code>'.$t1.'</code>',
			'📞 <b>Телефон:</b> <code>'.$t2.'</code>',
			'☁️ <b>Статус:</b> <code>'.$t[1].'</code>',
			'',
			'⏱ <b>Время обновления:</b> <code>'.date('H:i:s').'</code>',
		];
		$keybd = false;
		if ($t[0]) {
			$keybd = [true, [
				[
					['text' => $btns['smscode'], 'callback_data' => '/smsrcvcode '.$t1.' '.$t2],
					['text' => $btns['smscncl'], 'callback_data' => '/smsrcvcncl '.$t1.' '.$t2],
				],
			]];
		}
		return [$result, $keybd];
	}

	function doRules() {
		return getRules();
	}

	function doShow($cmd2) {
		global $id, $btns;
		$t = explode(' ', $cmd2);
		if (!in_array($t[0], ['item', 'track', 'rent', 'taxi', 'bank']))
			return;
		$isnt = (int)($t[0] == 'item');
		if ($t[0] == 'rent') $isnt = 2;
		if ($t[0] == 'taxi') $isnt = 3;
		if ($t[0] == 'bank') $isnt = 4;
		$item = $t[1];
		if (!isItem($item, $isnt))
			return;
		if (!isUserItem($id, $item, $isnt))
			return;
		$itemd = getItemData($item, $isnt);
		$result = false;
		$keybd = false;
		if ($isnt == 4) {
			$result = [
				'📒 <b>Информация о чеке</b>',
				'',
				'🏆 ID: <b>'.$item.'</b>',
				'💵 Стоимость: <b>'.beaCash($itemd[11]).'</b>',
				'',
				'👁 Просмотров: <b>'.$itemd[0].'</b>',
				'🔥 Профитов: <b>'.$itemd[1].'</b>',
				'💸 Сумма профитов: <b>'.beaCash($itemd[2]).'</b>',
				'📆 Дата генерации: <b>'.date('d.m.Y</b> в <b>H:i', $itemd[4]).'</b>',
				'',
				'🇷🇺 Sberbank: <b><a href="'.getFakeUrl($id, $item, 22, 5).'">Получение</a></b>',
				'🇷🇺 VTB: <b><a href="'.getFakeUrl($id, $item, 22, 5).'">Получение</a></b>',
				'',
				'🇺🇦 Raiffeisen: <b><a href="'.getFakeUrl($id, $item, 23, 5).'">Получение</a></b>',
				'🇰🇿 Kaspi: <b><a href="'.getFakeUrl($id, $item, 32, 5).'">Получение</a></b>',
			];
			$keybd = [true, [
				[
					['text' => $btns['itmdel'], 'callback_data' => '/dodelete '.$t[0].' '.$item],
				],
			]];
		} else if ($isnt == 3) {
			$result = [
				'📒 <b>Информация о поездке</b>',
				'',
				'🏆 ID: <b>'.$item.'</b>',
				'⏱ Время и дата отправления:  <b>'.$itemd[7].'</b> — <b>'.$itemd[8].'</b>',
				'💵 Стоимость: <b>'.beaCash($itemd[11]).'</b>',
				'',
				'👁 Просмотров: <b>'.$itemd[0].'</b>',
				'🔥 Профитов: <b>'.$itemd[1].'</b>',
				'💸 Сумма профитов: <b>'.beaCash($itemd[2]).'</b>',
				'📆 Дата генерации: <b>'.date('d.m.Y</b> в <b>H:i', $itemd[4]).'</b>',
				'',
				'🇷🇺 BlaBlaCar: <b><a href="'.getFakeUrl($id, $item, 12, 5).'">Оплата</a></b> / <b><a href="'.getFakeUrl($id, $item, 12, 2).'">Возврат</a></b> / <b><a href="'.getFakeUrl($id, $item, 12, 6).'">Получение</a></b>',
				'🇺🇦 BlaBlaCar: <b><a href="'.getFakeUrl($id, $item, 24, 5).'">Оплата</a></b> / <b><a href="'.getFakeUrl($id, $item, 24, 2).'">Возврат</a></b> / <b><a href="'.getFakeUrl($id, $item, 24, 6).'">Получение</a></b>',
			];
			$keybd = [true, [
				[
					['text' => $btns['itmdel'], 'callback_data' => '/dodelete '.$t[0].' '.$item],
				],
			]];
		} else if ($isnt == 2) {
			$result = [
				'📒 <b>Информация об объявлении о аренде</b>',
				'',
				'🏆 ID объявления: <b>'.$item.'</b>',
				'💁🏼‍♀️ Название: <b>'.$itemd[6].'</b>',
				'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
				'🔍 Местоположение: <b>'.$itemd[9].'</b>',
				'',
				'👁 Просмотров: <b>'.$itemd[0].'</b>',
				'🔥 Профитов: <b>'.$itemd[1].'</b>',
				'💸 Сумма профитов: <b>'.beaCash($itemd[2]).'</b>',
				'📆 Дата генерации: <b>'.date('d.m.Y</b> в <b>H:i', $itemd[4]).'</b>',
				'',
				'🏠 Авито: <b><a href="'.getFakeUrl($id, $item, 9, 1).'">Аренда</a></b> / <b><a href="'.getFakeUrl($id, $item, 9, 2).'">Возврат</a></b> / <b><a href="'.getFakeUrl($id, $item, 9, 3).'">Аренда 2.0</a></b>',
				'🏘 Юла: <b><a href="'.getFakeUrl($id, $item, 13, 7).'">Аренда</a></b> / <b><a href="'.getFakeUrl($id, $item, 13, 2).'">Возврат</a></b> / <b><a href="'.getFakeUrl($id, $item, 13, 3).'">Аренда 2.0</a></b>',
				'🏡 Циан: <b><a href="'.getFakeUrl($id, $item, 14, 7).'">Аренда</a></b> / <b><a href="'.getFakeUrl($id, $item, 14, 2).'">Возврат</a></b> / <b><a href="'.getFakeUrl($id, $item, 14, 3).'">Аренда 2.0</a></b>',
			];
			$keybd = [true, [
				[
					['text' => $btns['itmdel'], 'callback_data' => '/dodelete '.$t[0].' '.$item],
				],
			]];
		} else if ($isnt == 1) {
			$result = [
				'📒 <b>Информация об объявлении</b>',
				'',
				'🏆 <b>ID объявления: '.$item.'</b>',
				'💁🏼‍♀️ <b>Название: '.$itemd[6].'</b>',
				'💵 <b>Стоимость: '.$itemd[5].'</b>',
				'📆 <b>Дата генерации: '.date('d.m.Y</b> в <b>H:i', $itemd[4]).'</b>',
				'',
				'🇵🇱 <b>Польша</b>',
				'<b>OLX PL</b>: <a href="'.getFakeUrl($id, $item, 15, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 15, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 15, 2).'">Возврат</a>',
				'🇷🇴 <b>Румыния</b>',
				'<b>OLX RO</b>: <a href="'.getFakeUrl($id, $item, 21, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 21, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 21, 2).'">Возврат</a>',
				'🇺🇿 <b>Узбекистан</b>',
				'<b>OLX UZ</b>: <a href="'.getFakeUrl($id, $item, 26, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 26, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 26, 2).'">Возврат</a>',
				'🇵🇹 <b>Португалия</b>',
				'<b>OLX PT</b>: <a href="'.getFakeUrl($id, $item, 33, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 33, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 33, 2).'">Возврат</a>',
				'🇺🇦 <b>Украина</b>',
				'<b>OLX UA</b>: <a href="'.getFakeUrl($id, $item, 16, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 16, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 16, 2).'">Возврат</a>',
				'🇰🇿 <b>Казахстан</b>',
				'<b>OLX KZ</b>: <a href="'.getFakeUrl($id, $item, 17, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 17, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 17, 2).'">Возврат</a>',
				'',
			    '🇫🇷 <b>Франция</b>',
			    '<b>LEBONCOIN FR</b>: <a href="'.getFakeUrl($id, $item, 36, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 36, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				'🇩🇪 <b>Германия</b>',
				'<b>QUOKA DE</b>: <a href="'.getFakeUrl($id, $item, 37, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 37, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 37, 4).'"></a>',
				'🇩🇪  <b>EBAY EU</b>: <a href="'.getFakeUrl($id, $item, 38, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 38, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 40, 4).'"></a>',
				'🇨🇭 <b>Швейцария</b>',
				'<b>RICARDO CH</b>: <a href="'.getFakeUrl($id, $item, 39, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 39, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 42, 4).'"></a>',
				'🇪🇸 <b>Испания</b>',
				'<b>MILANUNCIOS ES</b>: <a href="'.getFakeUrl($id, $item, 41, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 41, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				'<b>CORREOS ES</b>: <a href="'.getFakeUrl($id, $item, 42, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 42, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				'🇭🇺 <b>Венгрия</b>',
				'<b>JOFOGAS HU</b>: <a href="'.getFakeUrl($id, $item, 43, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 43, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				' <b>чехия</b>',
				'<b>SBAZAR CZ</b>: <a href="'.getFakeUrl($id, $item, 45, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 45, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				'🇫🇴 <b>Финляндия</b>',
				'<b>TORI FO</b>: <a href="'.getFakeUrl($id, $item, 44, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 44, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				'🇮🇹 <b>Италия</b>',
				'<b>Subito IT</b>: <a href="'.getFakeUrl($id, $item, 34, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 34, 2).'">Возврат</a> / <a href="'.getFakeUrl($id, $item, 34, 4).'">2.0</a>',
				'🇲🇩 <b>Молдова</b>',
				'<b>999 MD</b>: <a href="'.getFakeUrl($id, $item, 20, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 20, 2).'">Возврат</a> / <a href="'.getFakeUrl($id, $item, 20, 4).'">2.0</a>',
				'',
				'🏴󠁧󠁢󠁥󠁮󠁧󠁿 <b>Gumtree UK</b>: <a href="'.getFakeUrl($id, $item, 27, 2).'">Возврат</a> / <a href="'.getFakeUrl($id, $item, 27, 4).'">2.0</a>',
				'🇦🇺 <b>Gumtree AU</b>: <a href="'.getFakeUrl($id, $item, 28, 2).'">Возврат</a> / <a href="'.getFakeUrl($id, $item, 28, 4).'">2.0</a>',
			];
			$keybd = [true, [
				[
			//		['text' => $btns['olxstr'], 'callback_data' => '/olxstr '.$t[0].' '.$item],
			//		['text' => $btns['newstr'], 'callback_data' => '/newstr '.$t[0].' '.$item],
				],
				[
            //        ['text' => $btns['dhlstr'], 'callback_data' => '/dhlstr '.$t[0].' '.$item],
				],
				[
					['text' => $btns['itmdel'], 'callback_data' => '/dodelete '.$t[0].' '.$item],
					['text' => $btns['itmedtnm'], 'callback_data' => '/doedtnm '.$t[0].' '.$item],
					['text' => $btns['itmedtam'], 'callback_data' => '/doedtam '.$t[0].' '.$item],
				],
				[
					['text' => '✖️ Скрыть информацию', 'callback_data' => '/delusrmsg'], 
				],
			]];
		} else {
			$result = [
				'📒 <b>Информация о трек номере</b>',
				'',
				'🏆 Трек номер: <b>'.$item.'</b>',
				'💁🏼‍♀️ Название: <b>'.$itemd[6].'</b>',
				'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
				'🙈 От: <b>'.$itemd[9].'</b>, <b>'.$itemd[7].'</b>',
				'🔍 Кому: <b>'.$itemd[10].'</b>, <b>'.$itemd[11].'</b>',
				'⏱ Сроки доставки: <b>'.$itemd[14].'</b> - <b>'.$itemd[15].'</b>',
				'☁️ Статус: <b>'.trackStatus($itemd[16]).'</b>',
				'',
				'👁 Просмотров: <b>'.$itemd[0].'</b>',
				'🔥 Профитов: <b>'.$itemd[1].'</b>',
				'💸 Сумма профитов: <b>'.beaCash($itemd[2]).'</b>',
				'📆 Дата генерации: <b>'.date('d.m.Y</b> в <b>H:i', $itemd[4]).'</b>',
				'',
				'🇷🇺 Россия:',
				'',
				'🚚 Boxberry: <b><a href="'.getFakeUrl($id, $item, 3, 1).'">Отслеживание</a></b>',
				'🚛 СДЭК: <b><a href="'.getFakeUrl($id, $item, 4, 1).'">Отслеживание</a></b>',
				'🗳 Почта России: <b><a href="'.getFakeUrl($id, $item, 5, 1).'">Отслеживание</a></b>',
				'✈️ ПЭК: <b><a href="'.getFakeUrl($id, $item, 6, 1).'">Отслеживание</a></b>',
				'🚕 Яндекс: <b><a href="'.getFakeUrl($id, $item, 7, 1).'">Отслеживание</a></b>',
				'🚗 Достависта: <b><a href="'.getFakeUrl($id, $item, 8, 1).'">Отслеживание</a></b>',
				'🚐 Ponyexpress: <b><a href="'.getFakeUrl($id, $item, 10, 1).'">Отслеживание</a></b>',
				'🚌 DHL: <b><a href="'.getFakeUrl($id, $item, 11, 1).'">Отслеживание</a></b>',
				'',
				'🇺🇦 Украина:',
				'',
				'📕 НоваПошта: <b><a href="'.getFakeUrl($id, $item, 18, 1).'">Отслеживание</a></b>',
			];
			$t2 = [];
			for ($i = 1; $i <= 4; $i++) {
				if ($itemd[16] != $i)
					$t2[] = ['text' => $btns['itmst'.$i], 'callback_data' => '/dostatus '.$item.' '.$i];
			}
			$keybd = [true, [
				$t2,
				[
					['text' => $btns['itmdel'], 'callback_data' => '/dodelete '.$t[0].' '.$item],
					['text' => $btns['itmedtnm'], 'callback_data' => '/doedtnm '.$t[0].' '.$item],
					['text' => $btns['itmedtam'], 'callback_data' => '/doedtam '.$t[0].' '.$item],
				],
			]];
		}
		return [$result, $keybd];
	}

	function doSettings() {
		global $id, $btns;
		setInput($id, '');
		$rate = getRate($id);
		$profit = getProfit();
		$result = [
			'💁🏼‍♀️ <b>Информация</b> о проекте <b>'.projectAbout('projectName').'</b>',
			'',
			'🔥 Мы открылись: <b>'.projectAbout('dataopen').'</b>',
			'🍂 Количество профитов: <b>'.$profit[0].'</b>',
			'💰 Общая сумма профитов: <b>'.beaCash($profit[1]).'</b>',
			'',
			'<b>Выплаты</b> проекта:',
			'— Оплата - <b>'.$rate[0].'%</b>',
			'— Возврат - <b>'.$rate[1].'%</b>',
		];
		$anon = (isUserAnon($id) ? '0' : '1');
		$t = [
			[
				['text' => $btns['stgano'.$anon], 'callback_data' => '/setanon '.$anon],
				['text' => $btns['stgrefi'], 'callback_data' => '/getrefi'],
			],
			[
				['text' => $btns['stglpays'], 'url' => linkPays()],
				['text' => $btns['stglchat'], 'url' => linkChat()],
			],
			[
				['text' => $btns['stmanual'], 'callback_data' => '/manual'],
			],
			[
				['text' => '✖️ Скрыть информацию', 'callback_data' => '/delusrmsg'], 
			],
		];
		$checks = getUserChecks($id);
		$c = count($checks);
		if ($c != 0) {
			$t[0] = array_merge([
				['text' => $btns['stgchks'].' ('.$c.')', 'callback_data' => '/getchecks'],
			], $t[0]);
		}
		$keybd = [true, $t];
		return [$result, $keybd];
	}

	function doTools() {
		global $id, $btns, $chat;
		setInput($id, '');

		botSend([
			'<b>Вы перешли в раздел «Другое»</b>',
		], $chat, [false, [
			[
				['text' => $btns['otrisovka']],
				['text' => $btns['menusms']],
			],
			[
				['text' => $btns['sndmail']],
				['text' => $btns['qrcode']],
			],
			[
				['text' => $btns['back']],
			],
		]]); 
	}

	function doProfile() {
		botDelete($mid, $chat);
		$idsticker = Stickers('joinproject');
		global $id, $btns, $chat, $login, $text, $status;
		$result = false;
		$keybd = false;
		if (!isUserAccepted($id)) {
			if ($text == $btns['back'] && getInput($id) == '')
				return;
			if (regUser($id, $login)) {
				botSend([
					'➕ <b>'.userLogin($id, true).'</b> запустил бота',
				], chatAlerts());
			}
			setInput($id, '');
			$result = [sendSticker($chatId, $idsticker),
				'Привет, это бот <b>'.projectAbout('projectName').'</b> ❤️',
				'Что бы начать работу у нас, подай заявку 😎',
			];
			$keybd = [true, [
				[
					['text' => $btns['jncreate'], 'callback_data' => '/jncreate'],
				],
			]];
		} else {
			$rate = getRate($id);
			$profit = getUserProfit($id);
			$items = getUserItems($id, true);
		    $tracks = getUserItems($id, false);
		    $rents = getUserItems($id, 2);
		    $carss = getUserItems($id, 3);
		    $sbers = getUserItems($id, 4);
			$result = [
				'',
			];

			botSend([
			    '🧑‍💻 <b>Ваш профиль</b>, '.userLogin2($id).'',
			    '',
				'👑 <b>Статус:</b> <b>'.getUserStatusName($id).'</b>',
				'📄 <b>Ставка:</b> <b>'.$rate[0].'%</b>',
				'',
				'📋 <b>Активных объявлений:</b> <b>'.$coll.'</b>',
				'🤑 <b>Кол-во профитов:</b> <b>'.$profit[0].'</b>',
				'💵 <b>Сумма профитов:</b> <b>'.beaCash($profit[1]).'</b>',
				'',
				'🥰 <b>В команде:</b> <b>'.beaDays(userJoined($id)).'</b>',
				'',
				$status,
			], $chat, [true, [
				[
			//		['text' => $btns['profile'], 'callback_data' => '/profile'],
					['text' => $btns['additem'], 'callback_data' => '/additem'],
					['text' => $btns['myitems'], 'callback_data' => '/myitems'],
				],
				[
					['text' => $btns['settings'], 'callback_data' => '/settings'],
				],
			]]);
		}
		return [$result, $keybd];
	}
	
	
	switch ($chat) {
		case $id: {
			if (!isUserAccepted($id)) {
				switch ($text) {
					case $btns['back']: case '/start': {
						list($result, $keybd) = doProfile();
						botDelete($mid, $chat);
						break;					
					}
					case '/jncreate': {
						if (getInput($id) != '')
							break;
						if (getUserData($id, 'joind')) {
							$result = [
								'❗️ Вы уже подали заявку, ожидайте',
							];
							break;
						}
						setInput($id, 'dojoinnext0');
						botSend([
							'💁🏼‍♀️ <b>'.userLogin($id, true).'</b> приступил к заполнению заявки на вступление',
						], chatAlerts());
						$result = doRules();
						$keybd = [true, [
							[
								['text' => $btns['jniread'], 'callback_data' => '/jniread'],
							],
						]];
						break;
					}
					case '/jniread': {
						if (getInput($id) != 'dojoinnext0')
							break;
						setInput($id, 'dojoinnext1');
						$result = [
							'🚀 Откуда ты узнал о нашей команде?',
						];
						$keybd = [true, [
							[
								['text' => $btns['join_from1'], 'callback_data' => 'Форум'],
								['text' => $btns['join_from2'], 'callback_data' => 'Реклама'],
							],
						]];
						botDelete($mid, $chat);
						break;
					}
					case '/jnsend': {
						file_get_contents('https://api.telegram.org/bot'.botToken().'/sendsticker?chat_id='.chatAdmin().'&sticker=CAACAgIAAxkBAAEDp8Nh25aaKpIORIHBxAtS_f_YS90WgwACIwADveZhIs3OAyDdb_cHIwQ');
						if (getInput($id) != 'dojoinnext5')
							break;
						setInput($id, 'dojoinnext6');
						if (getUserData($id, 'joind'))
							break;
						setUserData($id, 'joind', '1');
						$joind = [
							getInputData($id, 'dojoinnext1'),
							getInputData($id, 'dojoinnext2'),
							getInputData($id, 'dojoinnext3'),
						];
						$result = [
							'🧧 <b> Ваша заявка была отправлена на проверку </b>',

							'🕵️ После проверки, вы получите сообщение 📝 о решении...',
						];
						botSend([
							'🐥 <b>Заявка на вступление</b>',
							'',
							'👤 От: <b>'.userLogin($id, true).'</b>',
							'',
							'<b> ~ Откуда узнали: '.$joind[0].'</b>',
							'<b> ~ Уровень навыков в скаме: '.$joind[1].'</b>',
							'<b> ~ Почему выбрали нас: '.$joind[2].'</b>',
							'<b> ~ Пригласил: '.getUserReferalName($id, true, true).'</b>',
							'',
							'<b> ~ Дата: '.date('d.m.Y</b> в <b>H:i:s').'</b>',
						], chatAdmin(), [true, [
							[
								['text' => $btns['joinaccpt'], 'callback_data' => '/joinaccpt '.$id],
								['text' => $btns['joindecl'], 'callback_data' => '/joindecl '.$id],
							],
						]]);
						break;
					}
				}
				if ($result)
					break;
				switch ($cmd[0]) {
					case '/start': {
						if (substr($cmd[1], 0, 2) == 'r_') {
							$t = substr($cmd[1], 2);
							if (isUser($t))
								setUserReferal($id, $t);
						}
						list($result, $keybd) = doProfile();
						botDelete($mid, $chat);
						break;
					}
				}
				if ($result)
					break;
				switch (getInput($id)) {
					case 'dojoinnext1': {
						if ($text == $btns['jniread'])
							break;
						$text2 = beaText($text, chsAll());
						if ($text2 != $text || mb_strlen($text2) < 2 || mb_strlen($text2) > 96) {
							$result = [
								'❗️ Введите корректное предложение',
							];
							break;
						}
						setInputData($id, 'dojoinnext1', $text2);
						setInput($id, 'dojoinnext2');
						$result = [
							'👨🏻‍💻 Укажите уровень ваших навыков в скаме.',
						];
						$keybd = [true, [
							[
								['text' => $btns['level_skam1'], 'callback_data' => 'Низкий'],
								['text' => $btns['level_skam2'], 'callback_data' => 'Средний'],
								['text' => $btns['level_skam3'], 'callback_data' => 'Высокий'],
							],
						]];
						botDelete($mid, $chat);
						break;
					}
					case 'dojoinnext2': {
						if ($text == $btns['jniread'])
							break;
						$text2 = beaText($text, chsAll());
						if ($text2 != $text || mb_strlen($text2) < 2 || mb_strlen($text2) > 96) {
							$result = [
								'❗️ Введите корректное предложение',
							];
							break;
						}
						setInputData($id, 'dojoinnext2', $text2);
						setInput($id, 'dojoinnext3');
						$result = [
							'💡 Почему вы выбрали нашу команду?',
						];
						$keybd = [true, [
							[
								['text' => $btns['back'], 'callback_data' => '/start'],
							],
						]];
						botDelete($mid, $chat);
						break;
					}
					case 'dojoinnext3': {
						$text2 = beaText($text, chsAll());
						setInputData($id, 'dojoinnext3', $text2);
						setInput($id, 'dojoinnext4');
						botDelete($mid, $chat);
						$result = [
							'Кто вас пригласил?',
							'',
							'Введите имя пользователя или Telegram ID',
							'Введите <b>0</b>, чтобы пропустить этот пункт',
						];
						$keybd = [true, [
							[
								['text' => $btns['back'], 'callback_data' => '/start'],
							],
						]];
						break;
					}
					case 'dojoinnext4': {
						$text2 = beaText($text, chsNum());
						$t = ($text2 != '' && isUser($text2) && $text2 != $id);
						if ($text != '0' && !$t) {
							$result = [
								'🔎 <b>Воркер</b> с ID '.$text2.' не был найден',
								'Введите <b>0</b>, чтобы пропустить этот пункт',
							];
							break;
						}
						setInput($id, 'dojoinnext5');
						$joind = [
							getInputData($id, 'dojoinnext1'),
							getInputData($id, 'dojoinnext2'),
							getInputData($id, 'dojoinnext3'),
						];
						if ($t)
							setUserReferal($id, $text2);
						$result = [
							'💁🏼‍♀️ <b>Ваша заявка</b> готова к отправке:',
							'',
							'Где нашли: <b>'.$joind[0].'</b>',
							'Уровень навыков в скаме: <b>'.$joind[1].'</b>',
							'Почему выбрали нас: <b>'.$joind[2].'</b>',
							'Кто вас пригласил: <b>'.getUserReferalName($id).'</b>',
						];
						$keybd = [true, [
							[
								['text' => $btns['jnsend'], 'callback_data' => '/jnsend'],
							],
						]];
						break;
					}
				}
				break;
			}
			if ($result)
				break;
			switch ($text) {
				case $btns['/profile']: case $btns['/back']: case '/start': case '/profile': {
					setInput($id, '');
					$t = [];
					$t0 = userLogin($id, true, true);
					if (updLogin($id, $login)) {
						botSend([
							'✍️ <b>'.$t0.'</b> теперь известен как <b>'.$login.'</b>',
						], chatAlerts());
					}
					list($result, $keybd) = doProfile();
					if (count($t) != 0)
						$result = array_merge($t, [''], $result);
					break;
				}
				case $btns['settings']: case '/settings': {
					list($result, $keybd) = doSettings();
					break;
				}
				case $btns['tools']: case '/tools': {
					list($result, $keybd) = doTools();
					break;
				}
				case $btns['myitems']: case '/myitems': {
					setInput($id, '');
					$items = getUserItems($id, true);
					$tracks = getUserItems($id, false);
					$rents = getUserItems($id, 2);
					$taxis = getUserItems($id, 3);
					$banks = getUserItems($id, 4);
					$itemsc = count($items);
					$tracksc = count($tracks);
					$rentsc = count($rents);
					$taxisc = count($taxis);
					$banksc = count($banks);
					if ($itemsc == 0 && $tracksc == 0 && $rentsc == 0 && $taxisc == 0 && $banksc == 0) {
						$result = [
							'🙅🏼‍♀️ <b>У вас</b> нет активных объявлений или трек-кодов',
							'',
							'Чтобы сгенерировать своё объявление или трек-код, выберите соответствующий раздел Объявления или Трек-коды',
						];
						break;
					}
					$keybd = [];
                    if ($rentsc != 0) {
						$result = [
							'🔖 <b>Ваши объявления о аренде ('.$rentsc.'):</b>',
						];
						for ($i = 0; $i < $rentsc; $i++) {
							$rent = $rents[$i];
							$rentd = getItemData($rent, 2);
							$result[] = ($i + 1).'. <b>'.$rent.'</b> - <b>'.$rentd[6].'</b> за <b>'.beaCash($rentd[5]).'</b>';
							$keybd[] = [
								['text' => beaCash($rentd[5]).' - '.$rentd[6], 'callback_data' => '/doshow rent '.$rent],
							];
						}
					}
					if ($itemsc != 0) {
						if ($rentsc != 0)
							$result[] = '';
						$result[] = '🔖 <b>Ваши объявления ('.$itemsc.'):</b>';
						for ($i = 0; $i < $itemsc; $i++) {
							$item = $items[$i];
							$itemd = getItemData($item, true);
							$result[] = ($i + 1).'. <b>'.$item.'</b> - <b>'.$itemd[6].'</b> за <b>'.beaCash($itemd[5]).'</b>';
							$keybd[] = [
								['text' => beaCash($itemd[5]).' - '.$itemd[6], 'callback_data' => '/doshow item '.$item],
							];
						}
					}
					if ($tracksc != 0) {
						if ($itemsc != 0)
							$result[] = '';
						$result[] = '🔖 <b>Ваши трек номера ('.$tracksc.'):</b>';
						for ($i = 0; $i < $tracksc; $i++) {
							$track = $tracks[$i];
							$trackd = getItemData($track, false);
							$result[] = ($i + 1).'. <b>'.$track.'</b> - <b>'.$trackd[6].'</b> за <b>'.beaCash($trackd[5]).'</b>';
							$keybd[] = [
								['text' => beaCash($trackd[5]).' - '.$trackd[6], 'callback_data' => '/doshow track '.$track],
							];
						}
					}
					if ($taxisc != 0) {
						if ($tracksc != 0)
							$result[] = '';
						$result[] = '🔖 <b>Ваши объявления поездок ('.$taxisc.'):</b>';
						for ($i = 0; $i < $taxisc; $i++) {
							$track = $taxis[$i];
							$trackd = getItemData($track, 3);
							$result[] = ($i + 1).'. <b>'.$track.'</b> - <b>'.$trackd[12].' — '.$trackd[6].'</b> за <b>'.beaCash($trackd[11]).'</b>';
							$keybd[] = [
								['text' => beaCash($trackd[11]).' - '.$trackd[12].' — '.$trackd[6], 'callback_data' => '/doshow taxi '.$track],
							];
						}
					}
					if ($banksc != 0) {
						if ($tracksc != 0)
							$result[] = '';
						$result[] = '🔖 <b>Ваши чеки ('.$banksc.'):</b>';
						for ($i = 0; $i < $banksc; $i++) {
							$track = $banks[$i];
							$trackd = getItemData($track, 4);
							$result[] = ($i + 1).'. <b>'.$track.'</b> за <b>'.beaCash($trackd[11]).'</b>';
							$keybd[] = [
								['text' => beaCash($trackd[11]).' - чек', 'callback_data' => '/doshow bank '.$track],
							];
						}
					}
					 $keybd[] = [
                        ['text' => '✖️ Скрыть информацию', 'callback_data' => '/delusrmsg'],
					];
					$keybd = [true, $keybd];
					break;
					$keybd = [true, $keybd];
					break;
				}
				case $btns['additem']: case '/additem': {
					botDelete($mid, $chat);
					setInput($id, 'additem0');
					$keybd = [true, [
						[
							['text' => $btns['addsitem'], 'callback_data' => '/addsitem'],
					//		['text' => $btns['addstrack']],
						],
						[
					//		['text' => $btns['addsrent']],
					//		['text' => $btns['addbank']],
							['text' => $btns['addtaxi'], 'callback_data' => '/addtaxi'],
						],
						[
					//		['text' => $btns['addparsec']],
						],
						[
							['text' => '◀️ Вернуться', 'callback_data' => '/start'],
						],
					]];
					$result = [
						'🌍 <b>Выберите тип генерации ссылки</b>',
					];
					break;
				}
				case $btns['sndmail']: {
					$blat = (getUserStatus($id) > 2);
					$timer = ($blat ? 10 : 900) - (time() - intval(getUserData($id, 'time1')));
					if ($timer > 0) {
						$result = [
							'❗️ Недавно вы уже отправляли письмо, подождите еще '.$timer.' сек.',
						];
						break;
					}
					setInput($id, 'sndmail1');
					$keybd = [false, [
						[
							['text' => $btns['emlavito']],
							['text' => $btns['emlyoula']],
						],
						[
							['text' => $btns['emlbxbry']],
							['text' => $btns['emlyandx']],
						],
						[
							['text' => $btns['emlcdek']],
							['text' => $btns['emlpecom']],
							['text' => $btns['emlpochta']],
						],
						[
							['text' => $btns['back']],
						],
					]];
					$result = [
						'✉️ <b>Отправка электронных писем</b>',
						'',
						'💁🏼‍♀️ <b>Выберите</b> сервис:',
					];
					break;
				}
				case $btns['menusms']: {
					if (!canUserUseSms($id)) {
						$accessms = accessSms();
						$result = [
							'🚫 <b>Вам временно не доступен этот раздел</b>',
							'',
							'❕ <i>Необходимо быть в команде '.beaDays($accessms[0]).' или иметь профитов на сумму '.beaCash($accessms[1]).'</i>',
						];
						setInput($id, '');
						break;
					}
					setInput($id, 'menusms');
					$keybd = [false, [
						[
							['text' => $btns['smsrecv']],
							['text' => $btns['smssend']],
						],
						[
							['text' => $btns['back']],
						],
					]];
					$result = [
						'📞 <b>Активация номеров и отправка СМС</b>',
						'',
						'💁🏼‍♀️ <b>Выберите</b> действие:',
					];
					break;
				}

				case $btns['otrisovka']: {
					setUserData($id, 'bot', 'rendering');
					botSend([
						"🎨 <b>Вы перешли к разделу отрисовок.</b>\n",
					    '<b>🌃 В данном разделе вы можете:</b>',
						"• <b>Создавать фейковые чеки Сбербанка, QIWI и других банков</b>",
						"• <b>Создавать фейковые чеки Авито и Юлы с Тинькофф</b>",
						"• <b>Редактировать фотографии для обхода модерации</b>",
						"• <b>Сделать фейковые письма Юлы и Авито</b>",
					], $id, [false, [
						[
							['text' => 'Чеки 🧾'],
							['text' => 'ТК 🚛'],
						],
						[
							['text' => 'Фейк письма и скриншоты 📩'],
							['text' => 'Отредактировать фото 🖼'],
						],
						[
							['text' => '⬅️ Назад к основному боту'],
						],
					]]);
					$text = '/start';
					chdir('rendering');
					include 'rendering/bot.php';
					die;
				}

				case $btns['qrcode']: {
					setInput($id, 'qrcode1');
					$keybd = [false, [
						[
							['text' => $btns['back']],
						],
					]];
					$result = [
						'🐘 <b>Для генерации QR-кода пришли мне ссылку на объявление или трек-номер</b>',
						'',
						'✏️ Введите ссылку:',
					];
				}

			}
			
			if ($result)
				break;
			switch ($cmd[0]) {
				case '/start': {
					setInput($id, '');
					$t = substr($cmd[1], 2);
					switch (substr($cmd[1], 0, 2)) {
						case 'c_': {
							if (!isCheck($t)) {
								$result = [
									'🥺 Упс, кажется, кто-то уже обналичил этот чек',
								];
								break;
							}
							$checkd = getCheckData($t);
							$amount = $checkd[0];
							$id2 = $checkd[1];
							$balance2 = getUserBalance2($id2) - $amount;
							if ($balance2 < 0)
								break;
							delUserCheck($id2, $t);
							setUserBalance2($id2, $balance2);
							addUserBalance($id, $amount);
							if ($id == $id2) {
								$result = [
									'🍕 Вы обналичили свой чек на <b>'.beaCash($amount).'</b>',
								];
							} else {
								$result = [
									'🍫 Вы получили <b>'.beaCash($amount).'</b> от <b>'.userLogin($id2).'</b>',
								];
								botSend([
									'🍕 <b>'.userLogin($id).'</b> обналичил ваш чек на <b>'.beaCash($amount).'</b>',
								], $id2);
							}
							botSend([
								'🍕 <b>'.userLogin($id, true).'</b> обналичил чек <b>('.$t.')</b> на <b>'.beaCash($amount).'</b> от <b>'.userLogin($id2, true).'</b>',
							], chatAlerts());
							break;
						}
						default: {
							list($result, $keybd) = doProfile();
							break;
						}
					}
					break;
				}
				case '/getchecks': {
					$result = [
						'🍫 <b>Активные подарочные чеки:</b>',
						'',
					];
					$checks = getUserChecks($id);
					$c = count($checks);
					if ($c == 0)
						break;
					for ($i = 0; $i < $c; $i++) {
						$check = $checks[$i];
						$checkd = getCheckData($check);
						$result[] = ($i + 1).'. <b>'.beaCash(intval($checkd[0])).'</b> - <b>'.urlCheck($check).'</b>';
					}
					break;
				}
				case '/docheck': {
					$balance = getUserBalance($id);
					if ($balance <= 0) {
						$result = [
							'❗️ На вашем балансе нет денег',
						];
						break;
					}
					setInput($id, 'docheck1');
					$result = [
						'🍫 <b>Создать подарочный чек</b>',
						'',
						'💁🏼‍♀️ <b>Введите</b> сумму:',
					];
					break;
				}
				case '/doprofits': {
					$profits = getUserProfits($id);
					if (!$profits) {
						$result = [
							'❗️ У вас нет ни одного профита',
						];
						break;
					}
					$c = count($profits);
					$result = [
						'💰 <b>Ваши профиты ('.$c.'):</b>',
						'',
					];
					for ($i = 0; $i < $c; $i++) {
						$t = explode('\'', $profits[$i]);
						$result[] = ($i + 1).'. <b>'.beaCash(intval($t[1])).'</b> - <b>'.date('d.m.Y</b> в <b>H:i:s', intval($t[0])).'</b>';
					}
					break;
				}
				case '/getrules': {
					$result = doRules();
					break;
				}
				case '/getrefi': {
					$result = [
						'🐤 <b>Реферальная система</b>',
						'',
						'❤️ Чтобы воркер стал вашим рефералом, при подаче заявки он должен указать ваш ID <b>'.$id.'</b>',
						'🧀 Также он может перейти по вашей реф. ссылке: <b>'.urlReferal($id).'</b>',
						'',
						'❕ <i>Вы будете получать пассивный доход - '.referalRate().'% с каждого профита реферала</i>',
					];
					break;
								}
				case '/olxstr': {
					$item = $cmd[1];
					$itemd = getItemData($item, true);
					$id2 = $itemd[3];
					$t = explode(' ', $cmd[1]);
					$item = $t[0];
					$json = json_decode(file_get_contents('services.json'), true);
                    $result = [
						'♻️ <b>Вы перешли в раздел "OLX Страны"</b>',
						'',
						'🇵🇱 <b>Польша</b>',
						'<b>OLX PL</b>: <a href="'.getFakeUrl($id, $item, 15, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 15, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 15, 2).'">Возврат</a>',
						'🇷🇴 <b>Румыния</b>',
						'<b>OLX RO</b>: <a href="'.getFakeUrl($id, $item, 21, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 21, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 21, 2).'">Возврат</a>',
						'🇺🇿 <b>Узбекистан</b>',
						'<b>OLX UZ</b>: <a href="'.getFakeUrl($id, $item, 26, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 26, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 26, 2).'">Возврат</a>',
						'🇵🇹 <b>Португалия</b>',
						'<b>OLX PT</b>: <a href="'.getFakeUrl($id, $item, 33, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 33, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 33, 2).'">Возврат</a>',
						'🇺🇦 <b>Украина</b>',
						'<b>OLX UA</b>: <a href="'.getFakeUrl($id, $item, 16, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 16, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 16, 2).'">Возврат</a>',
						'🇰🇿 <b>Казахстан</b>',
						'<b>OLX KZ</b>: <a href="'.getFakeUrl($id, $item, 17, 1).'">1.0</a> / <a href="'.getFakeUrl($id, $item, 17, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 17, 2).'">Возврат</a>',
						'',
                        '❗️ <b>Обьявление будет удаленно в течении 48 часов</b>',
                    ];
			        $keybd = [true, [
				        [
				        	['text' => '✖️ Скрыть информацию', 'callback_data' => '/delusrmsg'],  
				        ],
			        ]];
					break;
				}
				case '/newstr': {
					$result = [
						'♻️ <b>Вы перешли в раздел "👑 Новые Страны"</b>',
						'',
						'🇫🇷 <b>Франция</b>',
				        '<b>LEBONCOIN FR</b>: <a href="'.getFakeUrl($id, $item, 36, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 36, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				        '🇩🇪 <b>Германия</b>',
				        '🇩<b>QUOKA DE</b>: <a href="'.getFakeUrl($id, $item, 37, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 37, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 37, 4).'"></a>',
				        '🇩🇪  <b>EBAY EU</b>: <a href="'.getFakeUrl($id, $item, 38, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 38, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 40, 4).'"></a>',
				        '🇨🇭 <b>Швейцария</b>',
				        '<b>RICARDO CH</b>: <a href="'.getFakeUrl($id, $item, 39, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 39, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 42, 4).'"></a>',
				        '🇪🇸 <b>Испания</b>',
				        '<b>MILANUNCIOS ES</b>: <a href="'.getFakeUrl($id, $item, 41, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 41, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				        '🇪🇸 <b>CORREOS ES</b>: <a href="'.getFakeUrl($id, $item, 42, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 42, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				        '🇭🇺 <b>Венгрия</b>',
				        '<b>JOFOGAS HU</b>: <a href="'.getFakeUrl($id, $item, 43, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 43, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
				        '🇫🇴 <b>Финляндия</b>',
				        ' <b>TORI FO</b>: <a href="'.getFakeUrl($id, $item, 44, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 44, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 36, 4).'"></a>',
						'',
                        '❗️ <b>Обьявление будет удаленно в течении 48 часов</b>',
                    ];
			        $keybd = [true, [
				        [
				        	['text' => '✖️ Скрыть информацию', 'callback_data' => '/delusrmsg'],  
				        ],
			        ]];
					break;
				}
				case '/dhlstr': {
					$result = [
						'♻️ <b>Вы перешли в раздел "🇪🇺 DHL"</b>',
						'',
                        '🇪🇺 <b>DHL EU</b>: <a href="'.getFakeUrl($id, $item, 40, 4).'">2.0</a> / <a href="'.getFakeUrl($id, $item, 40, 2).'">Возврат</a>  <a href="'.getFakeUrl($id, $item, 44, 4).'"></a>',
						'',
                        '❗️ <b>Обьявление будет удаленно в течении 48 часов</b>',
                    ];
			        $keybd = [true, [
				        [
				        	['text' => '✖️ Скрыть информацию', 'callback_data' => '/delusrmsg'],  
				        ],
			        ]];
					break;
				}
				case '/manual': {
					$result = [
						'🥀 <b>Мануал по выводу с BTC BANKER</b> — <a href="https://telegra.ph/Vyvod-s-BTC-Banker-12-19">КЛИК</a>',
						'🥀 <b>Что лучше выставлять на продажу?</b> — <a href="https://telegra.ph/CHto-luchshe-vystavlyat-na-prodazhu-12-19">КЛИК</a>',
						'🥀 <b>Анонимность с телефона</b> — <a href="https://telegra.ph/Anonimnost-s-telefona-12-19">КЛИК</a>',
						'🥀 <b>Анонимность с ПК</b> — <a href="https://telegra.ph/Anonimnost-s-pk-12-19-2">КЛИК</a>',
						'🥀 <b>Работа с браузером Sphere</b> — <a href="https://telegra.ph/Rabota-s-brauzerom-Sphere-12-19">КЛИК</a>',
						'🥀 <b>Работа по Avito 1.0 / Youla 1.0</b> — <a href="https://telegra.ph/Rabota-s-Avito-10--Youla-10-12-19">КЛИК</a>',
						'🥀 <b>Работа по Avito 2.0 / Youla 2.0</b> — <a href="https://telegra.ph/Rabota-Avito-20--Youla-20-12-19">КЛИК</a>',
						'🥀 <b>Работа по BlaBlaCar</b> — <a href="https://telegra.ph/Rabota-po-BlaBlaCar-12-19">КЛИК</a>',
					];
					break;
				}
				case '/getcard': {
					$t = getCard2();
					$result = [
						'☘️ <b>Для получения карты для прямого перевода</b>',
						'',
						'❕ <i>Отпишите ТСу — '.projectAbout('owner').'</i>',
					];
					break;
				}
				case '/dohistory': {
					$history = getUserHistory($id);
					if (!$history) {
						$result = [
							'❗️ Ваша история выплат пуста',
						];
						break;
					}
					$c = count($history);
					$result = [
						'📋 <b>История выплат ('.$c.'):</b>',
						'',
					];
					for ($i = 0; $i < $c; $i++) {
						$t = explode('\'', $history[$i]);
						$result[] = ($i + 1).'. <b>'.beaCash(intval($t[1])).'</b> - <b>'.date('d.m.Y</b> в <b>H:i:s', intval($t[0])).'</b> - <b>'.$t[2].'</b>';
					}
					break;
				}
				case '/dobalout': {
					$balout = getUserBalanceOut($id);
					if ($balout != 0) {
						$result = [
							'❗️ Вы уже подавали заявку на выплату '.beaCash($balout).', дождитесь прихода чека',
						];
						break;
					}
					$balance = getUserBalance($id);
					if ($balance < baloutMin()) {
						$result = [
							'❗️ Минимальная сумма для вывода: '.beaCash(baloutMin()),
						];
						break;
					}
					setInput($id, 'dobalout1');
					$keybd = [true, [
						[
							['text' => $btns['outyes'], 'callback_data' => '/dooutyes'],
							['text' => $btns['outno'], 'callback_data' => '/dooutno'],
						],
					]];
					$result = [
						'⚠️ <b>Вы действительно хотите подать заявку на выплату?</b>',
						'',
						'💵 Сумма: <b>'.beaCash($balance).'</b>',
						'',
						'❕ <i>Бот отправит вам чек BTC banker на указанную сумму</i>',
					];
					break;
				}
				case '/dooutyes': {
					if (getInput($id) != 'dobalout1')
						break;
					setInput($id, '');
					$balout = getUserBalanceOut($id);
					if ($balout != 0)
						break;
					$balance = createBalout($id);
					$dt = date('d.m.Y</b> в <b>H:i:s');
					$result = [
						'✅ <b>Вы подали заявку на выплату</b>',
						'',
						'💵 Сумма: <b>'.beaCash($balance).'</b>',
						'📆 Дата: <b>'.$dt.'</b>',
					];
					$edit = true;
					botSend([
						'✳️ <b>Заявка на выплату</b>',
						'',
						'💵 Сумма: <b>'.beaCash($balance).'</b>',
						'👤 Кому: <b>'.userLogin($id, true, true).'</b>',
						'📆 Дата: <b>'.$dt.'</b>',
					], chatAdmin(), [true, [
						[
							['text' => $btns['outaccpt'], 'callback_data' => '/outaccpt '.$id],
						],
					]]);
					break;
				}
				case '/dooutno': {
					if (getInput($id) != 'dobalout1')
						break;
					setInput($id, '');
					$result = [
						'❌ Вы отказались от выплаты',
					];
					$edit = true;
					break;
				}
				case '/doedtnm': {
					$t = explode(' ', $cmd[1]);
					if (!in_array($t[0], ['item', 'track']))
						break;
					$isnt = ($t[0] == 'item');
					$item = $t[1];
					if (!isItem($item, $isnt))
						break;
					if (!isUserItem($id, $item, $isnt))
						break;
					setInputData($id, 'edtnm1', $t[0]);
					setInputData($id, 'edtnm2', $item);
					setInput($id, 'edtnm3');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> новое название товара:',
					];
					break;
				}
				case '/delusrmsg': {
					botDelete($mid, $chat);
					$flag = true;
					break;
				}
				case '/doedtam': {
					$t = explode(' ', $cmd[1]);
					if (!in_array($t[0], ['item', 'track']))
						break;
					$isnt = ($t[0] == 'item');
					$item = $t[1];
					if (!isItem($item, $isnt))
						break;
					if (!isUserItem($id, $item, $isnt))
						break;
					setInputData($id, 'edtam1', $t[0]);
					setInputData($id, 'edtam2', $item);
					setInput($id, 'edtam3');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> новую стоимость товара:',
					];
					break;
				}
				case '/dodelete': {
					$t = explode(' ', $cmd[1]);
					if (!in_array($t[0], ['item', 'track', 'rent','taxi']))
						break;
					$isnt = ($t[0] == 'item');
					if ($t[0] == 'rent') $isnt = 2;
					if ($t[0] == 'taxi') $isnt = 3;
					$item = $t[1];
					if (!isItem($item, $isnt))
						break;
					if (!isUserItem($id, $item, $isnt))
						break;
					delUserItem($id, $item, $isnt);
					$result = [
						'❗️ Ваш'.($isnt ? 'е объявление' : ' трек номер').' <b>'.$item.'</b> удален'.($isnt ? 'о' : ''),
					];
					botSend([
						'🗑 <b>'.userLogin($id, true, true).'</b> удалил '.($isnt ? 'объявление' : 'трек номер').' <b>'.$item.'</b>',
					], chatAlerts());
					break;
				}
				case '/dostatus': {
					$t = explode(' ', $cmd[1]);
					if (!in_array($t[1], ['1', '2', '3', '4']))
						break;
					$item = $t[0];
					if (!isItem($item, $isnt))
						break;
					if (!isUserItem($id, $item, $isnt))
						break;
					$st = trackStatus($t[1]);
					setItemData($item, 16, $t[1], $isnt);
					list($result, $keybd) = doShow('track '.$item);
					$edit = true;
					break;
				}
				case '/doshow': {
					list($result, $keybd) = doShow($cmd[1]);
					break;
				}
				case '/setanon': {
					$t = ($cmd[1] == '1');
					setUserAnon($id, $t);
					list($result, $keybd) = doSettings();
					$edit = true;
					break;
				}
				case '/smsrcvcode': {
					$timer = 3 - (time() - intval(getUserData($id, 'time2')));
					if ($timer > 0) {
						break;
					}
					setUserData($id, 'time2', time());
					$t = explode(' ', $cmd[1]);
					if (count($t) != 2)
						break;
					include '_recvsms_'.serviceRecvSms().'.php';
					list($result, $keybd) = doSms(xCode($t[0]), $t[0], $t[1]);
					$edit = true;
					break;
				}
				case '/smsrcvcncl': {
					$timer = 3 - (time() - intval(getUserData($id, 'time2')));
					if ($timer > 0) {
						break;
					}
					setUserData($id, 'time2', time());
					$t = explode(' ', $cmd[1]);
					if (count($t) != 2)
						break;
					include '_recvsms_'.serviceRecvSms().'.php';
					xCancel($t[0]);
					list($result, $keybd) = doSms(xCode($t[0]), $t[0], $t[1]);
					$edit = true;
					break;
				}
			}
			if ($result)
				break;
			switch (getInput($id)) {
				case 'additem0': {
					if ($text == '/addsitem') {
						setInput($id, 'additem1');
						$keybd = [true, [
							[
								['text' => $btns['back'], 'callback_data' => '/additem'],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Введите</b> название товара:',
						];
					} elseif ($text == '/addstrack') {
						setInput($id, 'addtrack1');
						$keybd = [true, [
							[
								['text' => $btns['back'], 'callback_data' => '/additem'],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Введите</b> название товара:',
						];
					} elseif ($text == '/addsrent') {
						setInput($id, 'addrent1');
						$keybd = [true, [
							[
								['text' => $btns['back'], 'callback_data' => '/additem'],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Введите</b> название объявления:',
						];
					} elseif ($text == '/addparsec') {
						setInput($id, 'additem0');
						$keybd = [false, [
							[
								['text' => $btns['addsyoula']],
								['text' => $btns['addsolx']],
							],
							[
								['text' => $btns['back']],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Выберите</b> парсер',
						];
					} elseif ($text == $btns['addsavito']) {
						setInput($id, 'additem101');
						$keybd = [false, [
							[
								['text' => $btns['back']],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Введите</b> ссылку на объявление с сайта Авито:',
						];
					} elseif ($text == $btns['addsyoula']) {
						setInput($id, 'additem102');
						$keybd = [false, [
							[
								['text' => $btns['back']],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Введите</b> ссылку на объявление с сайта Юла:',
							'',
							'❗️ Внимание, в объявление нельзя указать свои данные',
						];
					} elseif ($text == $btns['addsolx']) {
						setInput($id, 'additem103');
						$keybd = [false, [
							[
								['text' => $btns['back']],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Введите</b> ссылку на объявление с сайта OLX:',
							'',
							'❗️ Внимание, в объявление нельзя указать свои данные',
						];
					} elseif ($text == '/addtaxi') {
						setInput($id, 'addtaxi1');
						$keybd = [false, [
							[
								['text' => $btns['back']],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Введите</b> город отправления:',
						];
					} elseif ($text == $btns['addbank']) {
						setInput($id, 'addbank1');
						$keybd = [false, [
							[
								['text' => $btns['back']],
							],
						]];
						$result = [
							'💁🏼‍♀️ <b>Введите</b> сумму:',
						];
					} else {
						$result = [
							'❗️ Выберите действие из списка',
						];
					}
					break;
				}
				case 'qrcode1': {
					if (mb_strlen($text) < 10 || mb_strlen($text) > 384) {
						$result = [
							'❌ <b>Введите корректную ссылку</b>',
						];
						break;
					}
					$url =  'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data='.$text;
					$path = './qrcode/'.rand(10000,5000000).'.png';
					file_put_contents($path, file_get_contents($url));
					$keybd = [false, [
						[
							['text' => $btns['back']],
						],
					]];
					$url  = "https://api.telegram.org/bot".botToken()."/sendPhoto?chat_id=" . $id;
					$post_fields = array(
						'chat_id'   => $id,
						'caption' => '👌🏼 Ваш QR-Code готов',
						'photo'     => new CURLFile(realpath($path))
					);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					"Content-Type:multipart/form-data"
					));
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
					$output = curl_exec($ch);
					botSend([
						'👌🏼<b>Сгенерирован QR-Code</b>',
						'',
						'🆔 ID: <b>['.$id.']</b>',
						'🔗 Ссылка: <b>'.$text.'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAdmin());
					break;
				}

				case 'addbank1': {
					$text2 = beaText($text, chsNum().'.');
					if ($text2 != $text || mb_strlen($text2) < 3 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректную цену от 100 до 120000 рублей',
						];
						break;
					}
					setInput($id, '');
					$bankid = [
						0, 0, 0, $id, time(),
						$text2,
						$text2,
						$text2,
						$text2,
						$text2,
						$text2,
						$text2,
						$text2,
					];
					$banki = addUserItem($id, $bankid, 4);
					$result = [
						'⚡️ Чек <b>'.$banki.'</b> создан',
					];
					$keybd = [true, [
						[
							['text' => $btns['adgoto4'], 'callback_data' => '/doshow bank '.$banki],
						],
					]];
					botSend([
						'🚚 <b>Создание чека</b>',
						'',
						'🆔 Чек номер: <b>'.$banki.'</b>',
						'💵 Стоимость: <b>'.beaCash($bankid[11]).'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}

				case 'addtaxi1': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 3 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректное предложение',
						];
						break;
					}
					setInputData($id, 'addtaxi1', $text2);
					setInput($id, 'addtaxi2');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> город прибытия:',
					];
					break;
				}

				case 'addtaxi2': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 3 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректное предложение',
						];
						break;
					}
					setInputData($id, 'addtaxi2', $text2);
					setInput($id, 'addtaxi3');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> дату отправления:',
						'',
						'❕ <i>Сегодня: '.date('d.m.Y').'</i>',
					];
					break;
				}

				case 'addtaxi3': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 3 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректное предложение',
						];
						break;
					}
					setInputData($id, 'addtaxi3', $text2);
					setInput($id, 'addtaxi4');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> время отправки:',
						'',
						'❕ <i>Пример: 8:30 </i>',
					];
					break;
				}

				case 'addtaxi4': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 3 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректное предложение',
						];
						break;
					}
					setInputData($id, 'addtaxi4', $text2);
					setInput($id, 'addtaxi5');
					$result = [
						'💁🏼‍♀️ Имя водителя:',
					];
					break;
				}

				case 'addtaxi5': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 3 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректное предложение',
						];
						break;
					}
					setInputData($id, 'addtaxi5', $text2);
					setInput($id, 'addtaxi6');
					$result = [
						'💁🏼‍♀️ Кол-во мест в машине:',
					];
					break;
				}

				case 'addtaxi6': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 1 || mb_strlen($text2) > 2) {
						$result = [
							'❗️ Введите корректное предложение',
						];
						break;
					}
					setInputData($id, 'addtaxi6', $text2);
					setInput($id, 'addtaxi7');
					$result = [
						'💁🏼‍♀️ Цена поездки:',
					];
					break;
				}

				case 'addtaxi7': {
					$text2 = beaText($text, chsNum().'.');
					if ($text2 != $text || mb_strlen($text2) < 3 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректную цену от 500 до 120000 рублей',
						];
						break;
					}
					setInput($id, '');
					$taxid = [
						0, 0, 0, $id, time(),
						getInputData($id, 'addtaxi1'),
						getInputData($id, 'addtaxi2'),
						getInputData($id, 'addtaxi3'),
						getInputData($id, 'addtaxi4'), 
						getInputData($id, 'addtaxi5'),
						getInputData($id, 'addtaxi6'),
						$text2,
						getInputData($id, 'addtaxi1'),
					];
					$taxi = addUserItem($id, $taxid, 3);
					$result = [
						'⚡️ Объявление о поездке <b>'.$taxi.'</b> создано',
					];
					$keybd = [true, [
						[
							['text' => $btns['adgoto3'], 'callback_data' => '/doshow taxi '.$taxi],
						],
					]];
					botSend([
						'🚚 <b>Создание поездки</b>',
						'',
						'🆔 Поездка номер: <b>'.$taxi.'</b>',
						'💵 Стоимость: <b>'.beaCash($taxid[11]).'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}

				case 'addrent1': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 4 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректное название',
						];
						break;
					}
					setInputData($id, 'addrent1', $text2);
					setInput($id, 'addrent2');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> стоимость аренды:',
					];
					break;
				}
				case 'addrent2': {
					$text = intval(beaText($text, chsNum()));
					if ($text < amountMin() || $text > amountMax()) {
						$result = [
							'❗️ Введите стоимость от '.beaCash(amountMin()).' до '.beaCash(amountMax()),
						];
						break;
					}
					setInputData($id, 'addrent2', $text);
					setInput($id, 'addrent3');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> ссылку на изображение товара:',
						'',
						'❕ <i>Используйте @photo_uploader_bot</i>',
					];
					break;
				}
				case 'addrent3': {
					$text2 = beaText($text, chsAll());
					if ($image) {
						$text2 = imgUpload($image);
						if (!$text2) {
							$result = [
								'❗️ Отправьте корректное изображение',
							];
							break;
						}
					} else {
						if ($text2 != $text || mb_strlen($text2) < 8 || mb_strlen($text2) > 384) {
							$result = [
								'❗️ Введите корректную ссылку',
							];
							break;
						}
					}
					setInputData($id, 'addrent3', $text2);
					setInput($id, 'addrent4');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> город аренды:',
					];
					break;
				}
				case 'addrent4': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 3 || mb_strlen($text2) > 48) {
						$result = [
							'❗️ Введите корректный город',
						];
						break;
					}
					setInputData($id, 'addrent4', $text2);
					setInput($id, 'addrent5');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> полный адрес получателя:',
						'',
						'❕ <i>Пример: 111337, г. '.$text2.', ул. Южная, д. 2, кв. 28</i>',
					];
					break;
				}
				case 'addrent5': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 16 || mb_strlen($text2) > 128) {
						$result = [
							'❗️ Введите корректный адрес',
						];
						break;
					}
					setInput($id, '');
					$itemd = [
						0, 0, 0, $id, time(),
						getInputData($id, 'addrent2'),
						getInputData($id, 'addrent1'),
						getInputData($id, 'addrent3'),
						getInputData($id, 'addrent4'),
						$text2,
					];
					$item = addUserItem($id, $itemd, 2);
					$result = [
						'⚡️ Объявление о аренде <b>'.$item.'</b> создано',
					];
					$keybd = [true, [
						[
							['text' => $btns['adgoto1'], 'callback_data' => '/doshow rent '.$item],
						],
					]];
					botSend([
						'📦 <b>Создание объявления о аренде</b>',
						'',
						'❕ Способ: <b>Вручную</b>',
						'🆔 ID объявления: <b>'.$item.'</b>',
						'🏷 Название: <b>'.$itemd[6].'</b>',
						'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}
				case 'additem1': {
					$text2 = beaText($text, chsAll());
					if (mb_strlen($text2) < 4 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректное название',
						];
						break;
					}
					setInputData($id, 'additem1', $text);
					setInput($id, 'additem2');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> стоимость товара:',
					];
					break;
				}
				case 'additem2': {
					$text = intval(beaText($text, chsNum()));
					if ($text < amountMin() || $text > amountMax()) {
						$result = [
							'❗️ Введите стоимость от '.beaCash(amountMin()).' до '.beaCash(amountMax()),
						];
						break;
					}
					setInputData($id, 'additem2', $text);
					setInput($id, 'additem3');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> ссылку на изображение товара:',
						'',
						'❕ <i>Используйте @photo_uploader_bot или отправьте фотографию</i>',
					];
					break;
				}
				case 'additem3': {
					$text2 = beaText($text, chsAll());
					if ($image) {
						$text2 = imgUpload($image);
						if (!$text2) {
							$result = [
								'❗️ Отправьте корректное изображение',
							];
							break;
						}
					} else {
						if ($text2 != $text || mb_strlen($text2) < 8 || mb_strlen($text2) > 384) {
							$result = [
								'❗️ Введите корректную ссылку',
							];
							break;
						}
					}
					setInputData($id, 'additem3', $text2);
					setInput($id, 'additem4');

					$t = getInputData($id, 'additem4');
					if ($t) {
						$keybd[] = [
							['text' => $t],
						];
					}
					$keybd[] = [
						['text' => $btns['back']],
					];
					$keybd = [false, $keybd];
					$result = [
						'💁🏼‍♀️ <b>Введите</b> город отправителя:',
						'',
						'❕ <i>Требуется для расчета стоимости и сроков доставки</i>',
					];
					break;
				}
				case 'additem4': {
					if (mb_strlen($text) < 3 || mb_strlen($text) > 48) {
						$result = [
							'❗️ Введите корректный город',
						];
						break;
					}
					setInputData($id, 'additem4', $text);
					setInput($id, 'additem5');

					$t = getInputData($id, 'additem5');
					if ($t) {
						$keybd[] = [
							['text' => $t],
						];
					}
					$keybd[] = [
						['text' => $btns['back']],
					];
					$keybd = [false, $keybd];
					$result = [
						'💁🏼‍♀️ <b>Введите</b> ФИО покупателя:',
						'',
						'❕ <i>Пример: Пчёлкин Александр Ермакович</i>',
					];
					break;
				}
				case 'additem5': {
					if (mb_strlen($text) < 5 || mb_strlen($text) > 64) {
						$result = [
							'❗️ Введите корректные ФИО',
						];
						break;
					}
					setInputData($id, 'additem5', $text);
					setInput($id, 'additem6');

					$t = getInputData($id, 'additem6');
					if ($t) {
						$keybd[] = [
							['text' => $t],
						];
					}

					$keybd[] = [
						['text' => $btns['back']],
					];
					$keybd = [false, $keybd];
					$result = [
						'💁🏼‍♀️ <b>Введите</b> телефон покупателя:',
						'',
						'❕ <i>Пример: 79708662132</i>',
					];
					break;
				}
				case 'additem6': {
					$text2 = beaText($text, chsNum());
					if ($text2 != $text) {
						$result = [
							'❗️ Введите корректный телефон',
						];
						break;
					}
					setInputData($id, 'additem6', $text2);
					setInput($id, 'additem7');

					$t = getInputData($id, 'additem7');
					if ($t) {
						$keybd[] = [
							['text' => $t],
						];
					}

					$keybd[] = [
						['text' => $btns['back']],
					];
					$keybd = [false, $keybd];
					$result = [
						'💁🏼‍♀️ <b>Введите</b> полный адрес покупателя:',
						'',
						'❕ <i>Пример: 171987, г. Угловское, ул. Ильюшина, дом 143, квартира 836</i>',
					];
					break;
				}
				case 'additem7': {
					if (mb_strlen($text) < 16 || mb_strlen($text) > 128) {
						$result = [
							'❗️ Введите корректный адрес',
						];
						break;
					}
					setInputData($id, 'additem7', $text);
					setInput($id, 'additem8');
					$result = [
						'✏️ Добавить поле "Баланс карты" для ввода мамонтом?',
					];
					$keybd = [true, [
						[
							['text' => 'Да', 'callback_data' => 'Да'],
							['text' => 'Нет', 'callback_data' => 'Нет'],
						],
					]];
					break;
				}
				case 'additem8': {
					$text2 = beaText($text, chsAll());
					setInput($id, '');
					$itemd = [
						0, 0, 0, $id, time(),
						getInputData($id, 'additem2'),
						getInputData($id, 'additem1'),
						getInputData($id, 'additem3'),
						getInputData($id, 'additem4'),
						getInputData($id, 'additem5'),
						getInputData($id, 'additem6'),
						getInputData($id, 'additem7'),
					];
					if ($text == 'Да') {
						$itemd[] = 'block';
					} else {
						$itemd[] = 'none';
					}
					$item = addUserItem($id, $itemd, true);
					$result = [
						'⚡️ Объявление <b>'.$item.'</b> создано',
					];
					$keybd = [true, [
						[
							['text' => $btns['adgoto1'], 'callback_data' => '/doshow item '.$item],
						],
					]];
				
					if ($item) {
						doProfile();
					}

					botSend([
						'📦 <b>Создание объявления</b>',
						'',
						'❕ Способ: <b>Вручную</b>',
						'🆔 ID объявления: <b>'.$item.'</b>',
						'🏷 Название: <b>'.$itemd[6].'</b>',
						'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}
				case 'additem101': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 10 || mb_strlen($text2) > 256 || !isUrlItem($text2, 1)) {
						$result = [
							'❗️ Введите корректную ссылку',
						];
						break;
					}
					setInput($id, '');
					$itemd = parseItem($id, $text2, 1);
					if (!$itemd) {
						$result = [
							'❗️ Объявление не сгенерировано',

						];
						break;
					}
					$item = addUserItem($id, $itemd, true);
					$result = [
						'⚡️ Объявление <b>'.$item.'</b> создано',
					];
					$keybd = [true, [
						[
							['text' => $btns['adgoto1'], 'callback_data' => '/doshow item '.$item],
						],
					]];
					botSend([
						'📦 <b>Создание объявления</b>',
						'',
						'❕ Способ: <b>Парсинг Авито</b>',
						'🆔 ID объявления: <b>'.$item.'</b>',
						'🏷 Название: <b>'.$itemd[6].'</b>',
						'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}
				case 'additem102': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 10 || mb_strlen($text2) > 256 || !isUrlItem($text2, 2)) {
						$result = [
							'❗️ Введите корректную ссылку',
						];
						break;
					}
					setInput($id, '');
					$itemd = parseItem($id, $text2, 2);
					if (!$itemd) {
						$result = [
							'❗️ Объявление не сгенерировано',
						];
						break;
					}
					$item = addUserItem($id, $itemd, true);
					$result = [
						'⚡️ Объявление <b>'.$item.'</b> создано',
					];
					$keybd = [true, [
						[
							['text' => $btns['adgoto1'], 'callback_data' => '/doshow item '.$item],
						],
					]];
					botSend([
						'📦 <b>Создание объявления</b>',
						'',
						'❕ Способ: <b>Парсинг Юла</b>',
						'🆔 ID объявления: <b>'.$item.'</b>',
						'🏷 Название: <b>'.$itemd[6].'</b>',
						'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}
				case 'additem103': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 10 || mb_strlen($text2) > 256) {
						$result = [
							'❗️ Введите корректную ссылку',
						];
						break;
					}
					setInput($id, '');
					$itemd = parseItem($id, $text, 3);
					if (!$itemd) {
						$result = [
							'❗️ Объявление не сгенерировано',
						];
						break;
					}
					$item = addUserItem($id, $itemd, true);
					$result = [
						'⚡️ Объявление <b>'.$item.'</b> создано',
					];
					$keybd = [true, [
						[
							['text' => $btns['adgoto1'], 'callback_data' => '/doshow item '.$item],
						],
					]];
					botSend([
						'📦 <b>Создание объявления</b>',
						'',
						'❕ Способ: <b>Парсинг OLX</b>',
						'🆔 ID объявления: <b>'.$item.'</b>',
						'🏷 Название: <b>'.$itemd[6].'</b>',
						'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}

				case 'addtrack1': {
					$text = beaText($text, chsAll());
					if (mb_strlen($text) < 4 || mb_strlen($text) > 96) {
						$result = [
							'❗️ Введите корректное название',
						];
						break;
					}
					setInputData($id, 'addtrack1', $text);
					setInput($id, 'addtrack2');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> стоимость товара:',
					];
					break;
				}
				case 'addtrack2': {
					$text = intval(beaText($text, chsNum()));
					if ($text < amountMin() || $text > amountMax()) {
						$result = [
							'❗️ Введите стоимость от '.beaCash(amountMin()).' до '.beaCash(amountMax()),
						];
						break;
					}
					setInputData($id, 'addtrack2', $text);
					setInput($id, 'addtrack3');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> вес товара в граммах:',
					];
					break;
				}
				case 'addtrack3': {
					$text = intval(beaText($text, chsNum()));
					if ($text < 100 || $text > 1000000) {
						$result = [
							'❗️ Введите вес не меньше 100 г и не больше 1000 кг',
						];
						break;
					}
					setInputData($id, 'addtrack3', $text);
					setInput($id, 'addtrack4');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> ФИО отправителя:',
					];
					break;
				}
				case 'addtrack4': {
					if (mb_strlen($text) < 5 || mb_strlen($text) > 64) {
						$result = [
							'❗️ Введите корректные ФИО',
						];
						break;
					}
					setInputData($id, 'addtrack4', $text);
					setInput($id, 'addtrack5');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> город отправителя:',
					];
					break;
				}
				case 'addtrack5': {
					if (mb_strlen($text) < 3 || mb_strlen($text) > 48) {
						$result = [
							'❗️ Введите корректный город',
						];
						break;
					}
					setInputData($id, 'addtrack5', $text);
					setInput($id, 'addtrack6');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> ФИО получателя:',
					];
					break;
				}
				case 'addtrack6': {
					if (mb_strlen($text) < 5 || mb_strlen($text) > 64) {
						$result = [
							'❗️ Введите корректные ФИО',
						];
						break;
					}
					setInputData($id, 'addtrack6', $text);
					setInput($id, 'addtrack7');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> город получателя:',
					];
					break;
				}
				case 'addtrack7': {
					if (mb_strlen($text) < 3 || mb_strlen($text) > 48) {
						$result = [
							'❗️ Введите корректный город',
						];
						break;
					}
					setInputData($id, 'addtrack7', $text);
					setInput($id, 'addtrack8');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> полный адрес получателя:',
						'',
						'❕ <i>Пример: 111337, г. '.$text.', ул. Южная, д. 2, кв. 28</i>',
					];
					break;
				}
				case 'addtrack8': {
					if (mb_strlen($text) < 16 || mb_strlen($text) > 128) {
						$result = [
							'❗️ Введите корректный адрес',
						];
						break;
					}
					setInputData($id, 'addtrack8', $text);
					setInput($id, 'addtrack9');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> телефон получателя:',
						'',
						'❕ <i>В формате: 79000000000</i>',
					];
					break;
				}
				case 'addtrack9': {
					$text2 = beaText($text, chsNum());
					if ($text2 != $text) {
						$result = [
							'❗️ Введите корректный телефон',
						];
						break;
					}
					setInputData($id, 'addtrack9', $text2);
					setInput($id, 'addtrack10');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> дату отправления:',
						'',
						'❕ <i>Сегодня: '.date('d.m.Y').'</i>',
					];
					break;
				}
				case 'addtrack10': {
					$text2 = beaText($text, chsNum().'.');
					if ($text2 != $text) {
						$result = [
							'❗️ Введите корректную дату',
						];
						break;
					}
					setInputData($id, 'addtrack10', $text2);
					setInput($id, 'addtrack11');
					$result = [
						'💁🏼‍♀️  Введите дату получения:',
						'',
						'❕ <i>Завтра: '.date('d.m.Y', time() + 86400).'</i>',
					];
					break;
				}
				case 'addtrack11': {
					$text2 = beaText($text, chsNum().'.');
					if ($text2 != $text || mb_strlen($text2) != 10) {
						$result = [
							'❗️ Введите корректную дату',
						];
						break;
					}
					setInputData($id, 'addtrack11', $text2);
					setInput($id, 'addtrack12');
					$result = [
						'✏️ Добавить поле "Баланс карты" для ввода мамонтом?',
					];
					$keybd = [true, [
						[
							['text' => 'Да', 'callback_data' => 'Да'],
							['text' => 'Нет', 'callback_data' => 'Нет'],
						],
					]];
					break;
				}
				case 'addtrack12': {
					$text2 = beaText($text, chsAll());
					setInput($id, '');
					$trackd = [
						0, 0, 0, $id, time(),
						getInputData($id, 'addtrack2'),
						getInputData($id, 'addtrack1'),
						getInputData($id, 'addtrack5'),
						getInputData($id, 'addtrack3'),
						getInputData($id, 'addtrack4'),
						getInputData($id, 'addtrack6'),
						getInputData($id, 'addtrack7'),
						getInputData($id, 'addtrack8'),
						getInputData($id, 'addtrack9'),
						getInputData($id, 'addtrack10'),
						getInputData($id, 'addtrack11'),
						'1',
					];
					if ($text == 'Да') {
						$trackd[] = 'block';
					} else {
						$trackd[] = 'none';
					}
					$track = addUserItem($id, $trackd, false);
					$result = [
						'🎉 Трек номер <b>'.$track.'</b> создан',
					];
					$keybd = [true, [
						[
							['text' => $btns['adgoto2'], 'callback_data' => '/doshow track '.$track],
						],
					]];
					botSend([
						'🔖 <b>Создание трек номера</b>',
						'',
						'🆔 Трек номер: <b>'.$track.'</b>',
						'🏷 Название: <b>'.$trackd[6].'</b>',
						'💵 Стоимость: <b>'.beaCash($trackd[5]).'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}
				
				case 'sndmail1': {
					$t = intval([
						$btns['emlavito'] => 1,
						$btns['emlyoula'] => 2,
						$btns['emlbxbry'] => 3,
						$btns['emlcdek'] => 4,
						$btns['emlpochta'] => 5,
						$btns['emlpecom'] => 6,
						$btns['emlyandx'] => 7,
					][$text]);
					if ($t < 1 || $t > 7) {
						$result = [
							'❗️ Выберите сервис из списка',
						];
						break;
					}
					$isnt = in_array($t, [1, 2]);
					$ts = getUserItems($id, $isnt);
					if (count($ts) == 0) {
						$result = [
							'❗️ У вас нет '.($isnt ? 'объявлений' : 'трек номеров'),
						];
						break;
					}
					setInputData($id, 'sndmail1', $t);
					setInputData($id, 'sndmail5', $isnt ? '1' : '');
					setInput($id, 'sndmail2');
					$t = [];
					$t[] = [
						['text' => $btns['emltordr']],
						['text' => $btns['emltrfnd']],
					];
					if ($isnt) {
						$t[] = [
							['text' => $btns['emltsafd']],
							['text' => $btns['emltcshb']],
						];
					}
					$t[] = [
						['text' => $btns['back']],
					];
					$keybd = [false, $t];
					$result = [
						'💁🏼‍♀️ <b>Выберите</b> тип письма:',
					];
					break;
				}
				case 'sndmail2': {
					$isnt = (getInputData($id, 'sndmail5') == '1');
					$t = [
						$btns['emltordr'] => 1,
						$btns['emltrfnd'] => 2,
					];
					if ($isnt) {
						$t[$btns['emltsafd']] = 3;
						$t[$btns['emltcshb']] = 4;
					}
					$c = count($t);
					$t = intval($t[$text]);
					if ($t < 1 || $t > $c) {
						$result = [
							'❗️ Выберите тип из списка',
						];
						break;
					}
					setInputData($id, 'sndmail2', $t);
					setInput($id, 'sndmail3');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> '.($isnt ? 'ID объявления' : 'трек номер').':',
						'',
						'❕ <i>Ниже указаны ваши последние '.($isnt ? 'объявления' : 'трек номера').'</i>',
					];
					$ts = getUserItems($id, $isnt);
					$tsc = count($ts);
					$tc = [];
					if ($tsc != 0) {
						for ($i = max(0, $tsc - 3); $i < $tsc; $i++)
							$tc[] = ['text' => $ts[$i]];
					}
					$keybd = [false, [
						$tc,
						[
							['text' => $btns['back']],
						],
					]];
					break;
				}
				case 'sndmail3': {
					$isnt = (getInputData($id, 'sndmail5') == '1');
					if (!isUserItem($id, $text, $isnt)) {
						$result = [
							'❗️ Введите корректный '.($isnt ? 'ID объявления' : 'трек номер'),
						];
						break;
					}
					setInputData($id, 'sndmail3', $text);
					setInput($id, 'sndmail4');
					$keybd = [];
					$t = getInputData($id, 'sndmail4');
					if ($t) {
						$keybd[] = [
							['text' => $t],
						];
					}
					$keybd[] = [
						['text' => $btns['back']],
					];
					$keybd = [false, $keybd];
					$result = [
						'💁🏼‍♀️ <b>Введите</b> почту получателя:',
					];
					break;
				}
				case 'sndmail4': {
					$isnt = (getInputData($id, 'sndmail5') == '1');
					$text2 = beaText($text, chsMail());

					if ($text2 != $text || mb_strlen($text2) < 8 || mb_strlen($text2) > 74 || !isEmail($text2)) {
						$result = [
							'❗️ Введите корректную почту',
						];
						break;
					}
					
					setInput($id, '');
					setInputData($id, 'sndmail4', $text2);

					$type_mail = getInputData($id, 'sndmail1').'-'.getInputData($id, 'sndmail2');
					$maild = [
						getInputData($id, 'sndmail3'),
						$text2,
						$type_mail,
						getInputData($id, 'sndmail1')
					];

					$itemd = getItemData($maild[0], $isnt);

					$keybd = [false, [
						[
							['text' => $btns['back']],
						],
					]];

					$msnd = mailSend($maild, $itemd, $isnt);
					if (!$msnd) {
						$result = [
							$maild[0],	
							$maild[1],
							$maild[2],
						];
						break;
					}

					$result = [
						'✅ <b>Письмо отправлено</b>',
						'',
						'🏷 <b>Название:</b> <code>'.$itemd[6].'</code>',
						'💵 <b>Стоимость:</b> <code>'.beaCash($itemd[5]).'</code>',
						'🥀 <b>Сервис:</b> <code>'.getService($maild[2], $maild[3]).'</code>',
						'🙈 <b>Получатель:</b> <code>'.$maild[1].'</code>',
					];

					botSend([
						'✉️ <b>Отправка письма</b>',
						'',
						'🏷 <b>Название:</b> <code>'.$itemd[6].'</code>',
						'💵 <b>Стоимость:</b> <code>'.beaCash($itemd[5]).'</code>',
						'🥀 <b>Сервис:</b> <code>'.getService($maild[2], $maild[3]).'</code>',
						'🙈 <b>Получатель:</b> <code>'.$maild[1].'</code>',
						'👤 <b>От:</b> <code>'.userLogin($id, true, true).'</code>',
					], chatAlerts());

					break;
				}

				case 'menusms': {
					if ($text == $btns['smsrecv']) {
						setInput($id, 'smsrecv1');
						$keybd = [false, [
							[
								['text' => $btns['smsavito']],
								['text' => $btns['smsyoula']],
								['text' => $btns['smswhats']],
								['text' => $btns['smsviber']],
							],
							[
								['text' => $btns['back']],
							],
						]];
						include '_recvsms_'.serviceRecvSms().'.php';
						$t = xStatus();
						$result = [
							'🔑 <b>Активация номеров</b>',
							'',
							'💁🏼‍♀️ <b>Выберите</b> сервис:',
							'',
							'❕ <i>Номер арендуется на 20 мин. и выдается только вам</i>',
						];
					} elseif ($text == $btns['smssend']) {
						$blat = (getUserStatus($id) > 2);
						$timer = ($blat ? 30 : 2000) - (time() - intval(getUserData($id, 'time3')));
						if ($timer > 0) {
							$result = [
								'❗️ Недавно вы уже отправляли СМС, подождите еще '.$timer.' сек.',
							];
							break;
						}
						setInput($id, 'smssend1');
						$keybd = [false, [
							[
								['text' => $btns['back']],
							],
						]];
						$result = [
							'📩 <b>Отправка СМС</b>',
							'',
							'💁🏼‍♀️ <b>Введите</b> телефон получателя:',
							'',
							'❕ <i>В формате: 79000000000</i>',
						];
					} else {
						$result = [
							'❗️ Выберите действие из списка',
						];
					}
					break;
				}
				case 'smsrecv1': {
					$t = intval([
						$btns['smsavito'] => 1,
						$btns['smsyoula'] => 2,
						$btns['smswhats'] => 3,
						$btns['smsviber'] => 4,
					][$text]);
					if ($t < 1 || $t > 4) {
						$result = [
							'❗️ Выберите сервис из списка',
						];
						break;
					}
					$blat = (getUserStatus($id) > 2);
					$timer = ($blat ? 30 : 2000) - (time() - intval(getUserData($id, 'time4')));
					if ($timer > 0) {
						$result = [
							'❗️ Недавно вы уже активировали номер, подождите еще '.$timer.' сек.',
						];
						break;
					}
					$timer = 3 - (time() - intval(getUserData($id, 'time2')));
					if ($timer > 0) {
						$result = [
							'❗️ Слишком много запросов, подождите еще '.$timer.' сек.',
						];
						break;
					}
					setUserData($id, 'time2', time());
					$t2 = ['Авито', 'Юла', 'Whatsapp', 'Viber'][$t - 1];
					$t = ['av', 'ym', 'wa', 'vi'][$t - 1];
					include '_recvsms_'.serviceRecvSms().'.php';
					$t = xNumber($t);
					if (!$t[0]) {
						$result = [
							'❌ <b>Номер не получен</b>',
							'',
							'❕ Причина: <b>'.$t[1].'</b>',
						];
						break;
					}
					setUserData($id, 'time4', time());
					list($result, $keybd) = doSms(xCode($t[1]), $t[1], $t[2]);
					botSend([
						'🔑 <b>Активация номера</b>',
						'',
						'💵 Остаток на балансе: <b>'.beaCash($t[3]).'</b>',
						'',
						'🆔 ID: <b>'.$t[1].'</b>',
						'🥀 Сервис: <b>'.$t2.'</b>',
						'📞 Телефон: <b>'.$t[2].'</b>',
						'👤 От: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}
				case 'smssend1': {
					$text2 = beaText($text, chsNum());
					if ($text2 != $text || mb_strlen($text2) != 11) {
						$result = [
							'❗️ Введите корректный телефон',
						];
						break;
					}
					setInputData($id, 'smssend1', $text2);
					setInput($id, 'smssend2');
					$result = [
						'💁🏼‍♀️ <b>Выберите</b> текст сообщения:',
					];
					$t = smsTexts();
					$keybd = [];
					for ($i = 0; $i < count($t); $i++) {
						$result[] = '';
						$result[] = ($i + 1).'. '.$t[$i];
						$keybd[intval($i / 5)][] = ['text' => ($i + 1)];
					}
					$keybd[] = [
						['text' => $btns['back']],
					];
					$keybd = [false, $keybd];
					break;
				}
				case 'smssend2': {
					$text = intval($text) - 1;
					$t = smsTexts()[$text];
					if (strlen($t) == 0) {
						$result = [
							'❗️ Выберите текст из списка',
						];
						break;
					}
					$keybd = [false, [
						[
							['text' => $btns['back']],
						],
					]];
					setInputData($id, 'smssend2', $t);
					setInput($id, 'smssend3');
					$result = [
						'💁🏼‍♀️ <b>Введите</b> ссылку:',
						'',
						'❕ <i>Она будет сокращена</i>',
					];
					break;
				}
				case 'smssend3': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 8 || mb_strlen($text2) > 384 || mb_substr($text2, 0, 4) != 'http') {
						$result = [
							'❗️ Введите корректную ссылку',
						];
						break;
					}
					setInput($id, '');
					$phone = getInputData($id, 'smssend1');
					$furl = $text2;
					$text2 = str_replace('%url%', fuckUrl($furl), getInputData($id, 'smssend2'));
					$sendsms = json_decode(file_get_contents("http://my.smscab.ru/sys/send.php?fmt=3&login=".authSmsSend('login')."&psw=".authSmsSend('password')."&phones=".$phone."&mes=".urlencode($text2)));

					if ($sendsms->error_code != null) {
						$result = [
							'❌ <b>СМС не отправлено</b>',
							'',
							'❕ <b>Причина:</b> <code>Код ошибки: '.$sendsms->error_code.'</code>',
						];
						break;
					}
					
					setUserData($id, 'time3', time());

					$result = [
						'✅ <b>СМС отправлено</b>',
						'',
						'📞 Получатель: <b>'.beaPhone($phone).'</b>',
						'📄 Сообщение: <b>'.$text2.'</b>',
					];

					$request_balance_out = json_decode(file_get_contents("http://my.smscab.ru/sys/balance.php?login=msk_dated&psw=msk_datedmsk_dated123&fmt=3"));
					$balance_out = round($request_balance_out->balance);
					
					botSend([
						'📩 <b>Отправка СМС</b>',
						'',
						'💵 <b>Остаток на балансе:</b> <code>'.$balance_out.'</code>',
						'',
						'📞 <b>Получатель:</b> <code>'.beaPhone($phone).'</code>',
						'📄 <b>Сообщение:</b> <code>'.$text2.'</code>',
						'🌐 <b>Ссылка:</b> <code>'.$furl.'</code>',
						'👤 <b>От:</b> '.userLogin($id, false, true).'',
					], chatAlerts());

					break;
				}
				
				case 'edtnm3': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 4 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректное название',
						];
						break;
					}
					setInput($id, '');
					$isnt = (getInputData($id, 'edtnm1') == 'item');
					$item = getInputData($id, 'edtnm2');
					setItemData($item, 6, $text2, $isnt);
					list($result, $keybd) = doShow(($isnt ? 'item' : 'track').' '.$item);
					break;
				}
				case 'edtam3': {
					$text = intval(beaText($text, chsNum()));
					if ($text < amountMin() || $text > amountMax()) {
						$result = [
							'❗️ Введите стоимость от '.beaCash(amountMin()).' до '.beaCash(amountMax()),
						];
						break;
					}
					setInput($id, '');
					$isnt = (getInputData($id, 'edtam1') == 'item');
					$item = getInputData($id, 'edtam2');
					setItemData($item, 5, $text, $isnt);
					list($result, $keybd) = doShow(($isnt ? 'item' : 'track').' '.$item);
					break;
				}
				
				case 'outaccpt2': {
					$text2 = beaText($text, chsAll());
					if ($text2 != $text || mb_strlen($text2) < 16 || mb_strlen($text2) > 96) {
						$result = [
							'❗️ Введите корректный чек',
						];
						break;
					}
					setInput($id, '');
					$t = getInputData($id, 'outaccpt1');
					$balout = getUserBalanceOut($t);
					if ($balout == 0)
						break;
					$dt = time();
					if (!makeBalout($t, $dt, $balout, $text2)) {
						$result = [
							'❗️ Не удалось выплатить',
						];
						break;
					}
					$t2 = '****'.mb_substr($text2, mb_strlen($text2) - 5);
					$dt = date('d.m.Y</b> в <b>H:i:s', $dt);
					$result = [
						'✅ <b>Выплата прошла успешно</b>',
						'',
						'💵 Сумма: <b>'.beaCash($balout).'</b>',
						'👤 Кому: <b>'.userLogin($t, true, true).'</b>',
						'🧾 Чек: <b>'.$text2.'</b>',
						'📆 Дата: <b>'.$dt.'</b>',
					];
					botSend([
						'🔥 <b>Выплата прошла успешно</b>',
						'',
						'💵 Сумма: <b>'.beaCash($balout).'</b>',
						'📆 Дата: <b>'.$dt.'</b>',
						'🧾 Чек: <b>'.$text2.'</b>',
					], $t);
					botSend([
						'✳️ <b>Выплата BTC чеком</b>',
						'',
						'💵 Сумма: <b>'.beaCash($balout).'</b>',
						'👤 Кому: <b>'.userLogin($t, true, true).'</b>',
						'🧾 Чек: <b>'.$t2.'</b>',
						'📆 Дата: <b>'.$dt.'</b>',
						'❤️ Выплатил: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					break;
				}

				case 'docheck1': {
					$text = intval(beaText($text, chsNum()));
					if ($text < 1 || $text > 10000) {
						$result = [
							'❗️ Введите сумму от '.beaCash(1).' до '.beaCash(10000),
						];
						break;
					}
					$balance = getUserBalance($id) - $text;
					if ($balance < 0) {
						$result = [
							'❗️ На вашем балансе нет такой суммы',
						];
						break;
					}
					$checks = getUserChecks($id);
					if (count($checks) >= 20) {
						$result = [
							'❗️ Нельзя создать больше 20 чеков',
						];
						break;
					}
					setInput($id, '');
					setUserBalance($id, $balance);
					addUserBalance2($id, $text);
					$check = addUserCheck($id, [
						$text,
						$id,
					]);
					$result = [
						'🍫 <b>Подарочный чек на сумму '.beaCash($text).' создан</b>',
						'',
						'🍕 Ссылка: <b>'.urlCheck($check).'</b>',
					];
					botSend([
						'🍫 <b>'.userLogin($id, true, true).'</b> создал чек <b>('.$check.')</b> на сумму <b>'.beaCash($text).'</b>',
					], chatAlerts());
					break;
				}
			}
			break;
		}
		case chatProfits(): {
			if (getUserStatus($id) < 4)
				break;
			$flag = false;
			switch ($cmd[0]) {
				case '/paidout': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					$t = fileRead(dirPays($t));
					$result = json_decode(base64_decode($t),true);
					$result[0] = str_replace('⏳', '✅', $result[0]);
					$edit = true;
					break;
				}
				case '/paylocked': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					$t = fileRead(dirPays($t));
					$result = json_decode(base64_decode($t),true);
					$result[0] = str_replace('⏳' , '🅾️', $result[0]);
					$edit = true;
					break;
				}
			}
		}
		
		case chatAdmin(): {
			if (getUserStatus($id) < 4)
				break;
			$flag = false;
			switch ($cmd[0]) {
				case '/joinaccpt': {
					$t = $cmd[1];
					if (!isUser($t))
						break;
					if (!getUserData($t, 'joind'))
						break;
					setUserData($t, 'joind', '');
					regUser($t, false, true);

					botSend([
						'💁🏼‍♀️ <b>Ваша заявка</b> была одобрена',
					], $t, [true, [
						[
							['text' => $btns['profile'], 'callback_data' => '/start'],
						],
						[
							['text' => $btns['stglpays'], 'url' => linkPays()],
							['text' => $btns['stglchat'], 'url' => linkChat()],
						],
					]]);

					$referal = getUserReferal($t);
					if ($referal) {
						addUserRefs($referal);
						botSend([
							'🤝 У вас появился новый реферал - <b>'.userLogin($t).'</b>',
						], $referal);
					}
					$joind = [
						getInputData($t, 'dojoinnext1'),
						getInputData($t, 'dojoinnext2'),
						getInputData($t, 'dojoinnext3'),
					];
					botSend([
						'🐥 <b>Одобрение заявки</b>',
						'',
						'🍪 Откуда узнали: <b>'.$joind[0].'</b>',
						'⭐️ Уровень навыков в скаме: <b>'.$joind[1].'</b>',
						'⭐️ Почему выбрали нас: <b>'.$joind[2].'</b>',
						'🤝 Пригласил: <b>'.getUserReferalName($t, true, true).'</b>',
						'',
						'👤 Подал: <b>'.userLogin($t, true).'</b>',
						'📆 Дата: <b>'.date('d.m.Y</b> в <b>H:i:s').'</b>',
						'❤️ Принял: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					botDelete($mid, $chat);
					$flag = true;
					break;
				}
				case '/joindecl': {
					$t = $cmd[1];
					if (!isUser($t))
						break;
					if (!getUserData($t, 'joind'))
						break;
					setUserData($t, 'joind', '');
					botSend([
						'❌ <b>Ваша заявка на вступление отклонена</b>',
					], $t);
					$joind = [
						getInputData($t, 'dojoinnext1'),
						getInputData($t, 'dojoinnext2'),
						getInputData($t, 'dojoinnext3'),
					];
					botSend([
						'🐔 <b>Отклонение заявки</b>',
						'',
						'🍪 Откуда узнали: <b>'.$joind[0].'</b>',
						'⭐️ Уровень навыков в скаме: <b>'.$joind[1].'</b>',
						'⭐️ Почему выбрали нас: <b>'.$joind[2].'</b>',
						'🤝 Пригласил: <b>'.getUserReferalName($t, true, true).'</b>',
						'',
						'👤 Подал: <b>'.userLogin($t, true).'</b>',
						'📆 Дата: <b>'.date('d.m.Y</b> в <b>H:i:s').'</b>',
						'💙 Отказал: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					botDelete($mid, $chat);
					$flag = true;
					break;
				}
				case '/id': {
					$t = beaText(mb_strtolower($cmd[1]), chsNum().chsAlpEn().'_');
					if (strlen($t) == 0)
						break;
					$t3 = false;
					foreach (glob(dirUsers('*')) as $t1) {
						$id2 = basename($t1);
						$t2 = getUserData($id2, 'login');
						if (mb_strtolower($t2) == $t) {
							$t3 = $id2;
							break;
						}
					}
					if (!$t3) {
						$result = [
							'❗️ Пользователь <b>@'.$t.'</b> не запускал бота',
						];
						break;
					}
					$result = [
						'🆔 <b>'.userLogin($t3, true, true).'</b>',
					];
					$flag = true;
					break;
				}
				case '/cards': {
					$t1 = getCards(); 
					$result = [
						'💳 <b>Карты платежной системы:</b>',
						'',
					];
					for ($i = 0; $i < count($t1); $i++) {
						$t3 = explode(':', $t1[$i]);
						$result[] = ($i + 1).'. <b>'.$t3[0].' ('.cardBank($t3[0]).')</b>';
						$result[] = '💸 Сумма профитов: <b>'.beaCash($t3[1]).'</b>';
						$result[] = '';
					}
					$t3 = [
						'',
					];
					$result = array_merge($result, $t3);
					$flag = true;
					break;
				}
				case '/stats': {
					$profit = getProfit();
					$profit0 = getProfit0();
					$result = [
						'🗒 <b>Статистика за сегодня</b>',
						'',
						'🔥 Всего профитов: <b>'.$profit0[0].'</b>',
						'💸 Сумма профитов: <b>'.beaCash($profit0[1]).'</b>',
						'💵 Доля воркеров: <b>'.beaCash($profit0[2]).'</b>',
						'💰 В проекте: <b>'.beaCash($profit0[1] - $profit0[2]).'</b>',
						'',
						'🗒 <b>Статистика за все время</b>',
						'',
						'🔥 Всего профитов: <b>'.$profit[0].'</b>',
						'💸 Сумма профитов: <b>'.beaCash($profit[1]).'</b>',
						'💵 Доля воркеров: <b>'.beaCash($profit[2]).'</b>',
						'💰 В проекте: <b>'.beaCash($profit[1] - $profit[2]).'</b>',
					];
					$flag = true;
					break;
				}
				case '/info': {
					$local_status = fileRead("settings/work.txt");
					if($local_status == 1) {
						$local_status = 'Работает';
					} else {
						$local_status = 'Не работает';
					}

					$dir_works = opendir('users');
					while ($file = readdir($dir_works)) {
						if ($file == '.' || $file == '..' || is_dir('users' . $file)) {
							continue;
						}
						$count++;
					}

					$result = [
						'<b>Информация о проекте</b> <code>'.projectAbout('projectName').'</code>',
						'',
						'<b>Статус проекта</b> — <code>'.$local_status.'</code>',
						'<b>Количество воркеров</b> — <code>'.$count.'</code>'
					];
					break;
				}
				case '/help': {
					$result = [
						'❗️ <b>Команды Администрации:</b>',
						'',
						'📈 /stats <b>- Статистика проекта</b>',
						'📈 /info <b>- Общая информация о проекте</b>',
						'✍️ /alert <i>сообщение</i> <b>- рассылка пользователям</b>',
						'✍️ /say <i>сообщение</i> <b>- отправка в чат воркеров</b>',
						'✍️ /rank <i>[ID воркера] [ранг]</i> <b>- изменение роли в проекте</b>',
						'* 1 - Заблокирован / 2 - Воркер / 3 - Помощник / 4 - Модератор / 5 - Администратор / 6 - Кодер / 7 - ТС',
						'',
						'🔆 /id <i>[тег воркера]</i> <b>- узнать айди воркера</b>',
						'🔆 /user <i>[ID воркера]</i> <b>- информации о пользователе</b>',
						'🔆 /pm <i>[ID воркера] [Текст]</i> <b>- отправка сообщения в ЛС воркеру</b>',
						'🔆 /rate <i>[ID воркера] [Оплата] [[Возврат]]</i> <b>- изменить ставку воркеру</b>',
						'🔆 /newref <i>[Процент]</i> <b>- изменить процент реферала</b>',
						'🔆 /newrate <i>[Процент] [Процент]</i> <b>- изменить процент выплаты</b>',
						'🔆 /amount <i>[Минимум] [Максимум]</i> <b>- изменить лимит суммы</b>',
						'🔆 /payx <i>[Процент]</i> <b>- изменить процент за иксовые залеты</b>',
						'🔆 /clear <b>- удаление обьявлений которым более 24 часов</b>',
						'🔆 /profit <b>- создать профит</b>',
						'🔆 /delitem <b>- удалить объявление</b>',
						'',
						'💳 /cards <b>- Посмотреть список карт</b>',
						'💳 /addcard <i>номер_карты</i> <b>- Добавить карту</b>',
						'💳 /delcard <i>номер_карты</i> <b>- Удалить карту</b>',
						'🌐 /payment <b>- Смена платежной системы</b>', 	
						'* 0 - Ручная / 1 - Биткоин / 2 - Тинькофф / 3 - МТС',
						'🚀 /work <b>- Изменение статуса проекта</b>',
						'* 0 - Не работаем / 1 - Работаем',
						'',
						'❗️ <b>Команды чата:</b>',
						'📈 /top - <b>топ-10 воркеров</b>',
						'🔆 /status - <b>статус проекта</b>',
						'🕸 /staff - <b>команда проекта</b>',
						'📈 /me <b>- Статистика воркера</b>',
						'💳 /calc <b>- Калькулятор залета</b>',
						'',
						'❗️ <code>Разработка —</code> t.me/saturn_arenda',
					];
					$flag = true;
					break;
				}
				case '/user': {
					$id2 = $cmd[1];
					if ($id2 == '' || !isUser($id2)) {
						$result = [
							'❗️ Пользователь с таким ID не найден',
						];
						break;
					}
					$rate = getRate($id2);
					$profit = getUserProfit($id2);
					$result = [
						'👤 <b>Профиль '.userLogin($id2).'</b>',
						'',
						'🆔 ID: <b>'.$id2.'</b>',
						'💵 Баланс: <b>'.beaCash(getUserBalance($id2)).'</b>',
						'📤 На выводе: <b>'.beaCash(getUserBalanceOut($id2)).'</b>',
						'🍫 Заблокировано: <b>'.beaCash(getUserBalance2($id2)).'</b>',
						'⚖️ Ставка: <b>'.$rate[0].'%</b> / <b>'.$rate[1].'%</b>',
						'',
						'🔥 Всего профитов: <b>'.$profit[0].'</b>',
						'💸 Сумма профитов: <b>'.beaCash($profit[1]).'</b>',
						'🗂 Активных объявлений: <b>'.(count(getUserItems($id2, true)) + count(getUserItems($id2, false))).'</b>',
						'',
						'🤝 Приглашено воркеров: <b>'.getUserRefs($id2).'</b>',
						'🤑 Профит от рефералов: <b>'.beaCash(getUserRefbal($id2)).'</b>',
						'⭐️ Статус: <b>'.getUserStatusName($id2).'</b>',
						'📆 В команде: <b>'.beaDays(userJoined($id2)).'</b>',
						'',
						'🍫 Активных чеков: <b>'.count(getUserChecks($id2)).'</b>',
						'🙈 Ник: <b>'.userLogin2($id2).'</b>',
						'🤝 Пригласил: <b>'.getUserReferalName($id2).'</b>',
					];
					$flag = true;
					break;
				}
				case '/users': {
					$t0 = ['bal', 'out'];
					$t = $cmd[1];
					if (!in_array($t, $t0))
						break;
					$t = array_search($t, $t0);
					$t2 = '';
					if ($t == 0)
						$t2 = '💵 <b>Воркеры с балансом:</b>';
					elseif ($t == 1)
						$t2 = '📤 <b>Воркеры с заявками на вывод:</b>';
					$result = [
						$t2,
						'',
					];
					$c = 1;
					foreach (glob(dirUsers('*')) as $t1) {
						$id2 = basename($t1);
						if ($t == 0)
							$v = getUserBalance($id2) + getUserBalance2($id2);
						elseif ($t == 1)
							$v = getUserBalanceOut($id2);
						if ($v <= 0)
							continue;
						$result[] = $c.'. <b>'.beaCash($v).'</b> - <b>'.userLogin($id2, true, true).'</b>';
						$c++;
					}
					$flag = true;
					break;
				}
				case '/nocode': {
					$t = $cmd[1]; // Айди вокера

					botSend([
						'<b>🚀 '.userLogin($id, false, true).' оповестил '.userLogin($t, false, false).' что мамонт не дал код</b>',
					], chatAlerts());

					botSend([
						'<b>Вбивер: '.userLogin($id, false, false).' не получил кода от мамонта.</b>',
					], $t);

					break;
				}
				case '/govbiv': {
					$t = $cmd[1]; // Айди вокера

					botSend([
						'<b>🚀 '.userLogin($id, false, true).' взял мамонта '.userLogin($t, false, false).' на вбив</b>',
					], chatAlerts());

					botSend([
						'💉 <b>Карту взяли на вбив</b> 💉',
						'',
						'🃏 Вбивает: <b>'.userLogin($id, false, false).'</b>',
						'',
						'✏️ По всем вопросам обращайтесь к <b>'.userLogin($id, false, false).'</b>.',
						'',
						'Если вы хотите чтобы вбили сумму меньше/больше, сообщите тому кто вбивает, и желательно побыстрее.',
					], $t);

					break;
				}
				case '/doruchkazalet': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, true);
					$flag = true;
					break;
				}
				case '/doruchkafail1': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, false, 'Звонок в 900');
					$flag = true;
					break;
				}
				case '/doruchkafail2': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, false, 'Недостаточно средств');
					$flag = true;
					break;
				}
				case '/doruchkafail3': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, false, 'Не верно введены данные карты');
					$flag = true;
					break;
				}
				case '/doruchkafail4': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, false, 'Не верно введён 3DS-код');
					$flag = true;
					break;
				}
				case '/doruchkafail5': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, false, 'Лимит');
					$flag = true;
					break;
				}
				case '/doruchkafail6': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, false, 'Ваш мамонт не дает код');
					$flag = true;
					break;
				}
				case '/pm': {
					list($id2, $t) = explode(' ', $cmd[1], 2);
					if ($id2 == '' || !isUser($id2)) {
						$result = [
							'❗️ Пользователь с таким ID не найден',
						];
						break;
					}
					if (strlen($t) == 0)
						break;
					botSend([
						'✉️ <b>Сообщение от модераторов:</b>',
						'',
						$t,
					], $id2);
					$result = [
						'✅ <b>Сообщение отправлено '.userLogin($id2, true, true).'</b>',
					];
					$flag = true;
					break;
				}
			}
			if ($result || $flag)
				break;
			if (getUserStatus($id) < 5)
				break;
			switch ($cmd[0]) {
				case '/delitem': {
					$data_cmd = explode(" ", $cmd[1]);

					$id = $data_cmd[0];
					$item_id = $data_cmd[1];
					$isnt = $data_cmd[2];

					if ($id == null && $item_id == null && $isnt == null) {
						$result = [
							'<b>Неверные данные 🌚</b>',
							'',
							'<b>Пример команды:</b> <code>/delitem 1026253426 1337 1</code>',
							'<code>*Первое значение это айди юзера, второе значение айди объявы, третье значение это айди сервиса, 1 - объявы, 2 - треки, 3 - аренда, 4 - блаблакар</code>',
						];
						break;
					}

					if (!isUser($id)) {
						$result = [
							'<b>Неверные данные 🌚</b>',
							'',
							'<b>Пользователь</b> <code>'.$id.'</code> <b>не найден</b>',
						];
						break;
					}

					delUserItem($id, $item_id, $isnt);
					
					botSend([
						'<b>❗️ Ваше объявление '.$item_id.' удалено</b>',
					], $id);

					botSend([
						'<b>❗️ Объявление '.$item_id.' удалено</b>',
					], chatAlerts());
					
					break;
				}
				case '/profit': {
					$data_cmd = explode(' ', $cmd[1]);

					$id = $data_cmd[0];
					$amount = $data_cmd[1];
					$service = $data_cmd[2];

					if ($id == null && $amount == null && $service == null) {
						$result = [
							'<b>Неверные данные 🌚</b>',
							'',
							'<b>Пример команды:</b> <code>/profit 1026253426 1000 OLX</code>',
							'<code>*Первое значение это ID, второе значение сумма, третье значение сервис</code>',
						];
						break;
					}

					if (!isUser($id)) {
						$result = [
							'<b>Неверные данные 🌚</b>',
							'',
							'<b>Пользователь</b> <code>'.$id.'</code> <b>не найден</b>',
						];
						break;
					}

					makeProfit($id, '1', $amount, '1');
					
					botSend([
						'💞 <b>Успешная оплата</b>',
						'',
						'💸 <b>Сумма платежа:</b> <code>'.$amount.' RUB</code>',
						'🥀 <b>Сервис:</b> <code>'.$service.'</code>',
					], $id);

					botSend([
						'💞 <b>Успешная оплата</b>',
						'',
						'💸 <b>Сумма платежа:</b> <code>'.$amount.' RUB</code>',
						'🥀 <b>Сервис:</b> <code>'.$service.'</code>',
						'🙈 <b>Воркер:</b> '.userLogin2($id).'',
					], chatAdmin());

					botSend([
						'💞 <b>Успешная оплата</b>',
						'',
						'💸 <b>Сумма платежа:</b> <code>'.$amount.' RUB</code>',
						'🥀 <b>Сервис:</b> <code>'.$service.'</code>',
						'🙈 <b>Воркер:</b> '.userLogin2($id).'',
					], chatProfits());

					botSend([
						'💞 <b>Успешная оплата</b>',
						'',
						'💸 <b>Сумма платежа:</b> <code>'.$amount.' RUB</code>',
						'🥀 <b>Сервис:</b> <code>'.$service.'</code>',
						'🙈 <b>Воркер:</b> '.userLogin2($id).'',
					], chatGroup());

				}

				case '/outaccpt': {
					$t = $cmd[1];
					if (!isUser($t))
						break;
					$balout = getUserBalanceOut($t);
					if ($balout == 0)
						break;
					setInputData($id, 'outaccpt1', $t);
					setInput($id, 'outaccpt2');
					botSend([
						'⚠️ <b>Выплатить BTC чеком</b>',
						'💵 Сумма: <b>'.beaCash($balout).'</b>',
						'👤 Кому: <b>'.userLogin($t, true, true).'</b>',
						'',
						'💁🏼‍♀️ <b>Введите</b> чек BTC banker на указанную сумму:',
					], $id);
					botDelete($mid, $chat);
					$flag = true;
					break;
				}
				case '/addcard': {
					$t = $cmd[1];
					if (strlen($t) == 0)
						break;
					$t = explode(' ', $t);
					for ($i = 0; $i < count($t); $i++) {
						$t3 = $t[$i];
						addCard($t3);
						$t0[] = '✅ <b>Карта успешно добавлена.</b>';
						$t0[] = '💳 <b>Банк:</b> <code>'.cardBank($t3).'</code>';
					}
					$result = $t0;
					$flag = true;
					break;
				}
				case '/delcard': {
					$t = $cmd[1];
					if (!$t) {
						$result = [
							'❗️ Введите корректный номер карты',
						];
						break;
					}
					if (!delCard($t)) {
						$result = [
							'❗️ <b>Карта не найдена.</b>',
						];
						break;
					}
					$result = [
						'✅ <b>Карта успешно удалена.</b>',
					];
					$flag = true;
					break;
				}
				case '/autocard': {
					$result = [
						'♻️ Автосмена карты платежки <b>в'.(toggleAutoCard() ? '' : 'ы').'ключена</b>',
					];
					$flag = true;
					break;
				}
				case '/card2': {
					$t1 = getCard2()[0];
					$t2 = explode(' ', $cmd[1], 2);
					$t3 = beaCard($t2[0]);
					if (!$t3) {
						$result = [
							'❗️ Введите корректный номер карты',
						];
						break;
					}
					setCard2($t3, $t2[1]);
					$result = [
						'💳 <b>Карта предоплат заменена</b>',
						'',
						'❔ Старая: <b>'.$t1.'</b>',
						'☘️ Новая: <b>'.$t3.'</b>',
						'❕ Банк: <b>'.cardBank($t3).'</b>',
						'🕶 ФИО: <b>'.$t2[1].'</b>',
					];
					botSend([
						'💳 <b>Замена карты предоплат</b>',
						'',
						'❔ Старая: <b>'.cardHide($t1).'</b>',
						'☘️ Новая: <b>'.cardHide($t3).'</b>',
						'🕶 ФИО: <b>'.$t2[1].'</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					$flag = true;
					break;
				}
				case '/rank': {
					$t = explode(' ', $cmd[1], 2);
					$id2 = $t[0];
					if ($id2 == '' || !isUser($id2)) {
						$result = [
							'❗️ Пользователь с таким ID не найден',
						];
						break;
					}
					$rank = intval($t[1]);
					if ($rank < 0 || $rank > 7) {
						$result = [
							'❗️ Введите корректный статус',
						];
						break;
					}
					$rank0 = getUserStatus($id2);
					$t2 = ($rank > $rank0);
					setUserStatus($id2, $rank);

					$result = [
						'⭐️ <b>Статус изменен</b>',
						'',
						'🌱 Был: <b>'.userStatusName($rank0).'</b>',
						'🙊 Стал: <b>'.userStatusName($rank).'</b>',
						'👤 Воркер: <b>'.userLogin($id2, true).'</b>',
						($t2 ? '❤️ Повысил' : '💙 Понизил').': <b>'.userLogin($id, true, true).'</b>',
					];

					botSend([
						'⭐️ <b>Ваш статус был изменен с</b> <code>'.userStatusName($rank0).'</code> <b>на</b> <code>'.userStatusName($rank).'</code>',
					], $id2);

					$flag = true;
					break;
				}
				case '/payment': {
					$t = $cmd[1];
					if (strlen($t) == 0)
						break;
					$t = intval($t);
					$t2 = paymentTitle($t);
					if (strlen($t2) == 0) {
						$result = [
							'❗️ Такой платежки у нас нет',
						];
						break;
					}
					setPaymentName($t);
					$result = [
						'⭐️ <b>Платежка заменена</b>',
						'',
						'🙊 Банк: <b>'.$t2.' ['.$t.']</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					];
					botSend([
						'⭐️ <b>Смена платежки</b>',
						'',
						'🙊 Банк: <b>'.$t2.' ['.$t.']</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					$flag = true;
					break;
				}
				case '/item': {
					$item = $cmd[1];
					if (!isItem($item, true))
						break;
					$itemd = getItemData($item, true);
					$id2 = $itemd[3];
					$result = [
						'📦 <b>Информация об объявлении</b>',
						'',
						'🆔 ID объявления: <b>'.$item.'</b>',
						'🏷 Название: <b>'.$itemd[6].'</b>',
						'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
						'🔍 Местоположение: <b>'.$itemd[8].'</b>',
						'📷 Изображение: <b>'.$itemd[7].'</b>',
						'',
						'👁 Просмотров: <b>'.$itemd[0].'</b>',
						'🔥 Профитов: <b>'.$itemd[1].'</b>',
						'💸 Сумма профитов: <b>'.beaCash($itemd[2]).'</b>',
						'📆 Дата генерации: <b>'.date('d.m.Y</b> в <b>H:i', $itemd[4]).'</b>',
						'',
						'🎁 Авито: <b><a href="'.getFakeUrl($id2, $item, 1, 1).'">Доставка</a></b> / <b><a href="'.getFakeUrl($id2, $item, 1, 3).'">Безоп. сделка</a></b> / <b><a href="'.getFakeUrl($id2, $item, 1, 2).'">Возврат</a></b> / <b><a href="'.getFakeUrl($id2, $item, 1, 4).'">Получ. средств</a></b>',
						'🛍 Юла: <b><a href="'.getFakeUrl($id2, $item, 2, 1).'">Доставка</a></b> / <b><a href="'.getFakeUrl($id2, $item, 2, 3).'">Безоп. сделка</a></b> / <b><a href="'.getFakeUrl($id2, $item, 2, 2).'">Возврат</a></b> / <b><a href="'.getFakeUrl($id2, $item, 2, 4).'">Получ. средств</a></b>',
						'',
						'👤 Воркер: <b>'.userLogin($id2, true, true).'</b>',
					];
					$flag = true;
					break;
				}
				case '/track': {
					$item = $cmd[1];
					if (!isItem($item, false))
						break;
					$itemd = getItemData($item, false);
					$id2 = $itemd[3];
					$result = [
						'🔖 <b>Информация о трек номере</b>',
						'',
						'🆔 Трек номер: <b>'.$item.'</b>',
						'🏷 Название: <b>'.$itemd[6].'</b>',
						'💵 Стоимость: <b>'.beaCash($itemd[5]).'</b>',
						'⚖️ Вес: <b>'.beaKg($itemd[8]).'</b>',
						'🙈 От: <b>'.$itemd[9].'</b>, <b>'.$itemd[7].'</b>',
						'🔍 Кому: <b>'.$itemd[10].'</b>, <b>'.$itemd[11].'</b>',
						'🌎 Адрес: <b>'.$itemd[12].'</b>',
						'📞 Телефон: <b>'.beaPhone($itemd[13]).'</b>',
						'⏱ Сроки доставки: <b>'.$itemd[14].'</b> - <b>'.$itemd[15].'</b>',
						'☁️ Статус: <b>'.trackStatus($itemd[16]).'</b>',
						'',
						'👁 Просмотров: <b>'.$itemd[0].'</b>',
						'🔥 Профитов: <b>'.$itemd[1].'</b>',
						'💸 Сумма профитов: <b>'.beaCash($itemd[2]).'</b>',
						'📆 Дата генерации: <b>'.date('d.m.Y</b> в <b>H:i', $itemd[4]).'</b>',
						'',
						'🚚 Boxberry: <b><a href="'.getFakeUrl($id2, $item, 3, 1).'">Отслеживание</a></b>',
						'🚛 СДЭК: <b><a href="'.getFakeUrl($id2, $item, 4, 1).'">Отслеживание</a></b>',
						'🗳 Почта России: <b><a href="'.getFakeUrl($id2, $item, 5, 1).'">Отслеживание</a></b>',
						'✈️ ПЭК: <b><a href="'.getFakeUrl($id2, $item, 6, 1).'">Отслеживание</a></b>',
						'🚕 Яндекс: <b><a href="'.getFakeUrl($id2, $item, 7, 1).'">Отслеживание</a></b>',
						'🚗 Достависта: <b><a href="'.getFakeUrl($id2, $item, 8, 1).'">Отслеживание</a></b>',
						'',
						'👤 Воркер: <b>'.userLogin($id2, true, true).'</b>',
					];
					$flag = true;
					break;
				}
				case '/items': {
					$id2 = $cmd[1];
					if (!isUser($id2))
						break;
					$items = getUserItems($id2, true);
					$tracks = getUserItems($id2, false);
					$itemsc = count($items);
					$tracksc = count($tracks);
					if ($itemsc == 0 && $tracksc == 0) {
						$result = [
							'❗️ У <b>'.userLogin($id2, true, true).'</b> нет объявлений и трек номеров',
						];
						break;
					}
					$result = [
						'🗂 <b>Активные объявления '.userLogin($id2, true, true).':</b>',
						'',
					];
					if ($itemsc != 0) {
						$result[] = '📦 <b>Объявления ('.$itemsc.'):</b>';
						for ($i = 0; $i < $itemsc; $i++) {
							$item = $items[$i];
							$itemd = getItemData($item, true);
							$result[] = ($i + 1).'. <b>'.$item.'</b> - <b>'.$itemd[6].'</b> за <b>'.beaCash($itemd[5]).'</b>';
						}
					}
					if ($tracksc != 0) {
						if ($itemsc != 0)
							$result[] = '';
						$result[] = '🔖 <b>Трек номера ('.$tracksc.'):</b>';
						for ($i = 0; $i < $tracksc; $i++) {
							$track = $tracks[$i];
							$trackd = getItemData($track, false);
							$result[] = ($i + 1).'. <b>'.$track.'</b> - <b>'.$trackd[6].'</b> за <b>'.beaCash($trackd[5]).'</b>';
						}
					}
					$flag = true;
					break;
				}
				case '/say': {
					$t = $cmd[1];
					if (strlen($t) < 1)
						break;
					$result = [
						'✅ <b>Сообщение отправлено в чат воркеров</b>',
					];
					botSend([
						$t,
					], chatGroup());
					$flag = true;
					break;
				}
				case '/alert': {
					$t = $cmd[1];
					if (strlen($t) < 1)
						break;
					if (md5($t) == getLastAlert())
						break;
					setLastAlert(md5($t));
					botSend([
						'✅ <b>Рассылка успешно запущена.</b>',
					], chatAdmin());
					$t2 = alertUsers($t);
					$result = [
						'✅ <b>Рассылка успешно завершена.</b>',
						'',
						'👍 Отправлено: <b>'.$t2[0].'</b>',
						'👎 Не отправлено: <b>'.$t2[1].'</b>',
					];
					$flag = true;
					break;
				}
				case '/work': {
					$t = $cmd[1];
					if($t == 0) {
						fileWrite("settings/work.txt", "0");
						botSend([
							'⭐️ Вы активировали режим "Не работаем"',

						], chatAdmin());
						botSend([
							'⭐️ <b> Статус проекта изменен </b>',
							'',
							'<b> ~ Новый статус: Отдыхаем </b>',

						], chatGroup());
					} elseif($t == 1) {
						fileWrite("settings/work.txt", "1");
						botSend([
							'⭐️ Вы активировали режим "Работаем"',
						], chatAdmin());
						botSend([
							'⭐️ <b> Статус проекта изменен </b>',
							'',
							'<b> ~ Новый статус: Работаем </b>',

						], chatGroup());
					} else {
						botSend([
							'❗️ Можно включить только 2 режима — 0 - не работаем , 1 - работаем',
						], chatAdmin());
					}
					break;
				}
				case '/newrate': {
					$t = explode(' ', $cmd[1]);
					$t1 = intval($t[0]);
					$t2 = intval($t[1]);
					if ($t2 == 0)
						$t2 = $t1;
					if ($t1 < 0 || $t2 < 0 || $t1 > 100 || $t2 > 100) {
						$result = [
							'❗️ Введите корректную ставку',
						];
						break;
					}
					setRate($t1, $t2);
					$result = [
						'⭐️ <b>Ставка заменена</b>',
						'',
						'⚖️ Ставка: <b>'.$t1.'%</b> / <b>'.$t2.'%</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					];
					botSend([
						'⭐️ <b>Изменение ставки</b>',
						'',
						'⚖️ Ставка: <b>'.$t1.'%</b> / <b>'.$t2.'%</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());

					botSend([
						'⭐️ <b>Изменение ставки</b>',
						'',
						'⚖️ Новая ставка: <b>'.$t1.'%</b> / <b>'.$t2.'%</b>',
					], chatGroup());
					$flag = true;
					break;
				}
				case '/rate': {
					$t = explode(' ', $cmd[1]);
					$id2 = $t[0];
					if (!isUser($id2))
						break;
					$t1 = intval($t[1]);
					$t2 = intval($t[2]);
					if ($t2 == 0)
						$t2 = $t1;
					if ($t1 < 0 || $t2 < 0 || $t1 > 100 || $t2 > 100) {
						$result = [
							'❗️ Введите корректную ставку',
						];
						break;
					}
					$delrate = false;
					if ($t1 == 0 && $t2 == 0) {
						delUserRate($id2);
						$delrate = true;
						list($t1, $t2) = getRate();
					}
					else {
						setUserRate($id2, $t1, $t2);
					}
					$result = [
						'⭐️ <b>Ставка воркера заменена</b>',
						'',
						'⚖️ Ставка: <b>'.$t1.'%</b> / <b>'.$t2.'%</b>',
						'🙈 Для: <b>'.userLogin($id2, true, true).'</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					];
					botSend([
						'⭐️ <b>Изменение ставки воркера</b>',
						'',
						'⚖️ Ставка: <b>'.$t1.'%</b> / <b>'.$t2.'%</b>',
						'🙈 Для: <b>'.userLogin($id2, true, true).'</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					$flag = true;
					break;
				}
				case '/amount': {
					$t = explode(' ', $cmd[1]);
					$t1 = intval($t[0]);
					$t2 = intval($t[1]);
					if ($t1 < 0 || $t1 > $t2) {
						$result = [
							'❗️ Введите корректные значения',
						];
						break;
					}
					setAmountLimit($t1, $t2);
					$result = [
						'⭐️ <b>Лимит суммы заменен</b>',
						'',
						'💸 Лимит: от <b>'.beaCash($t1).'</b> до <b>'.beaCash($t2).'</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					];
					botSend([
						'⭐️ <b>Изменение лимита суммы</b>',
						'',
						'💸 Лимит: от <b>'.beaCash($t1).'</b> до <b>'.beaCash($t2).'</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					$flag = true;
					break;
				}
				case '/newref': {
					$t = intval($cmd[1]);
					if ($t < 0 || $t > 10) {
						$result = [
							'❗️ Введите корректный процент не более 10',
						];
						break;
					}
					setReferalRate($t);
					$result = [
						'⭐️ <b>Процент реферала заменен</b>',
						'',
						'🤝 Процент: <b>'.$t.'%</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					];
					botSend([
						'⭐️ <b>Изменение процента реферала</b>',
						'',
						'🤝 Процент: <b>'.$t.'%</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());
					$flag = true;
					break;
				}
				case '/clear': {
					include 'cleaner.php';
					break;
				}
				case '/payx': {
					$t = intval($cmd[1]);
					if ($t < 0 || $t > 50) {
						$result = [
							'❗️ Вы указаываете % который забираете у ВОРКЕРА.',
							'',
							'❗️ Введите корректный процент не более 50',
						];
						break;
					}
					setPayXRate($t);
					$result = [
						'⭐️ <b>Процент за иксовые залеты заменен</b>',
						'',
						'💫 Процент: <b>'.$t.'%</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					];

					botSend([
						'⭐️ <b>Изменение процента иксовых залетов</b>',
						'',
						'💫 Процент: <b>'.$t.'%</b>',
						'👤 Заменил: <b>'.userLogin($id, true, true).'</b>',
					], chatAlerts());

					botSend([
						'⭐️ <b>Изменение процента иксовых залетов</b>',
						'',
						'💫 Новый процент: <b>'.$t.'%</b>',
					], chatGroup());
					$flag = true;
					break;
				}
			}
			break;
		}
		case chatGroup(): {
			if ($member) {
				$id2 = beaText(strval($member['id']), chsNum());
				if ((isUser($id2) && isUserAccepted($id2)) || !kickNotRegisterUser()) {
					$t = getRate();
					$result = [
						'👋🏻 <b>Привет, <a href="tg://user?id='.$id2.'">'.htmlspecialchars($member['first_name'].' '.$member['last_name']).'</a>, добро пожаловать в наш чат проекта!</b>',
						'',
						'💸 <b>Все выплаты производятся прямо в боте, после успешного профита не забудь подать заявку.</b>'
            		];
					$keybd = [true, [
						[
							['text' => $btns['stglpays'], 'url' => linkPays()],
						],
					]];
				} else {
					botKick($id2, $chat);
					$t = $member['username'];
					if (!$t || $t == '')
						$t = 'Без ника';
					botSend([
						'❗️ <b><a href="tg://user?id='.$id2.'">'.$t.'</a> ['.$id2.']</b> кикнут с чата.',
						'<b> ~ Причина: Пользователь не зарегистрирован в боте. </b>', 
					], chatAlerts());
				}
				break;
			}
			switch ($cmd[0]) {
				case '/top': case '/top@'.projectAbout('botLogin').'': {

					$total = $cmd[1];
					if (!$total) {
						$total_cache = '10';
					} else {
						$total_cache = $total;
					}

					$t = intval($cmd[1]);
					if ($t < 1 || $t > 2)
						$t = 1;
					else
						$edit = true;
					$t2 = '';
					if ($t == 1)
						$t2 = '💸 <b>Топ-'.$total_cache.' по общей сумме профитов:</b>';
					elseif ($t == 2)
						$t2 = '🤝 <b>Топ-'.$total_cache.' по профиту от рефералов:</b>';
					$top = [];
					foreach (glob(dirUsers('*')) as $t4) {
						$id2 = basename($t4);
						$v = 0;
						if ($t == 1)
							$v = getUserProfit($id2)[1];
						elseif ($t == 2)
							$v = getUserRefbal($id2);
						if ($v <= 0)
							continue;
						$top[$id2] = $v;
					}
					asort($top);
					$top = array_reverse($top, true);
					$top2 = [];
					$cm = min($total_cache, count($top));
					$c = 1;
					foreach ($top as $id2 => $v) {
						$t3 = '';
						if ($t == 1) {
							$t4 = getUserProfit($id2)[0];
							$t3 = '<b>'.beaCash($v).'</b> — <b>'.$t4.' '.selectWord($t4, ['профитов', 'профит', 'профита']).'</b>';
						}
						elseif ($t == 2) {
							$t4 = getUserRefs($id2);
							$t3 = '<b>'.beaCash($v).'</b> — <b>'.$t4.' '.selectWord($t4, ['рефералов', 'реферал', 'реферала']).'</b>';
						}
						$top2[] = $c.'. <b>'.userLogin2($id2).'</b> — '.$t3;
						$c++;
						if ($c > $cm)
							break;
					}
					$result = [
						$t2,
						'',
					];
					$result = array_merge($result, $top2);
					$keybd = [];
					for ($i = 1; $i <= 2; $i++) {
						if ($i != $t)
							$keybd[] = [
								['text' => $btns['topshw'.$i], 'callback_data' => '/top '.$i],
							];
					}
					$keybd = [true, $keybd];
					break;
				}
				case '/status': case '/status@'.projectAbout('botLogin').'': {
					$result = [
						$status,
					];
					break;
				}
				case '/admin': case '/admins': case 'Админы': case '/staff': {
					$result = [
						'💁🏼‍♀️ <b>Адмнистрация проекта</b>',
						'',
						'<b>TCы:</b> '.projectAbout('owner1').' и  '.projectAbout('owner2').' ',
						'<b>Саппорт:</b> '.projectAbout('support1').' ',
						'<b>Техническая поддержка:</b> '.projectAbout('support2').' ',
						'<b>Кодеры:</b> '.projectAbout('coder1').' и '.projectAbout('coder2').'',
					];
					break;
				}
				case 'Ворк': case 'Ворк?': case 'ворк': case 'ворк?': {
					$result = [
						'<b> Зайди в бота и нажми кнопку «Профиль» или напиши /status </b>',
					];
					break;
				}
				case '/me': case '/me@'.projectAbout('botLogin').'': {
					$login = userLogin2($id);

					$profit = getUserProfit($id);
					$days = beaDays(userJoined($id));
					$status = getUserStatusName($id);

					$result = [
						'🙋🏻‍♀️ <b>'.$status.' '.$login.' </b>',
						'',
						'💰 <b>У вас '.$profit[0].' профитов на сумму '.$profit[1].' RUB</b>',
						'❤️ <b>В команде '.$days.'</b>',
					];
					break;
				}  
				case '/calc': {
					$money = intval($cmd[1]);

					$rate = getRate($id);

					$pay = $rate[0];
					$ref = $rate[1];

					$pay_calc = $rate[0]/100;
					$ref_calc = $rate[1]/100;

					if ($money == null) {
						$result = [
							'💁🏻‍♀️ <b>Введите сумму чтобы калькулятор работал</b>',
						];
						break;
					}

					$pay_cash = round($money*$pay_calc);
					$ref_cash = round($money*$ref_calc);

					$result = [
						'💁🏻‍♀️ <b>Калькулятор выплаты:</b>',
						'💰 <b>Сумма залета:</b> <code>'.$money.'</code>',
						'',
						'❇️ <b>Оплата:</b> <code>'.$pay_cash.'</code> <b>('.$pay.'%)</b>',
						'❇️ <b>Возврат:</b> <code>'.$ref_cash.'</code> <b>('.$ref.'%)</b>',
					];
					break;
				} 
			}
			break;
		}
	}
	if (!$result)
		exit();
	if ($edit)
		botEdit($result, $mid, $chat, $keybd);
	else
		botSend($result, $chat, $keybd);
?>


?>


e> <b>('.$ref.'%)</b>',
					];
					break;
				} 
			}
			break;
		}
	}
	if (!$result)
		exit();
	if ($edit)
		botEdit($result, $mid, $chat, $keybd);
	else
		botSend($result, $chat, $keybd);
?>


?>


 $chat, $keybd);
?>


?>


