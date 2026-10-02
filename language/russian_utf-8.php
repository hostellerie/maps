<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                           |
// +---------------------------------------------------------------------------+
// | english.php                                                               |
// |                                                                           |
// | English language file                                                     |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2026 by the following authors:                         |
// |                                                                           |
// | Authors: ::Ben                                                            |
// +---------------------------------------------------------------------------+
// | Created with the Geeklog Plugin Toolkit.                                  |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

/**
* @package Maps
*/

/**
* Import Geeklog plugin messages for reuse
*
* @global array $LANG32
*/
global $LANG32;

// +---------------------------------------------------------------------------+
// | Array Format:                                                             |
// | $LANGXX[YY]:  $LANG - variable name                                       |
// |               XX    - specific array name                                 |
// |               YY    - phrase id or number                                 |
// +---------------------------------------------------------------------------+

$LANG_MAPS_1 = array(
    'plugin_name'           => 'Карты',
    'plugin_conf'           => 'Настройка плагина',
    'карта'                   => 'карта',
    'need_google_api'       => 'Ключ API Google Maps для браузера не настроен. Карты Google нельзя отобразить, пока этот ключ не будет добавлен.',
    'api_status_title' => 'Состояние API Google Maps',
    'api_status_missing' => 'Ключ API Google Maps для браузера не настроен.',
    'api_status_testing' => 'Проверка Google Maps JavaScript API…',
    'api_status_key' => 'Ключ браузера',
    'api_status_ok' => 'Google Maps JavaScript API успешно загружен: ключ браузера принят для этой страницы.',
    'api_status_auth' => 'Google Maps отклонил ключ браузера или его настройки. Проверьте в консоли браузера точный код ошибки Google, затем ограничения HTTP-рефереров, включённые API и биллинг Google Cloud.',
    'api_status_load' => 'Не удалось загрузить скрипт Google Maps JavaScript API. Проверьте сеть, политику CSP, блокировщики контента и консоль браузера.',
    'api_status_timeout' => 'Google Maps JavaScript API не ответил. Проверьте консоль браузера и панель Сеть.',
    'admin_help_title'      => 'Начало работы с Картами',
    'admin_help_intro'      => 'Карты позволяют создавать несколько карт, добавлять маркеры и при необходимости использовать собственные значки или наложения.',
    'admin_help_google'     => 'Настройка Google Maps',
    'admin_help_google_1'   => 'Откройте Google Cloud Console, создайте или выберите проект и подключите платёжный аккаунт для рабочего использования.',
    'admin_help_google_2'   => 'Включите как минимум Maps JavaScript API. Также включите Geocoding API, если используется автоматическое преобразование адресов в широту/долготу.',
    'admin_help_google_3'   => 'Создайте ключ API для браузера. Ограничьте его разрешёнными сайтами (HTTP-реферерами), например https://www.example.com/*, затем ограничьте ключ Maps JavaScript API.',
    'admin_help_google_4'   => 'Для серверного геокодирования рекомендуется создать второй ключ. Ограничьте его IP-адресом сервера и только Geocoding API.',
    'admin_help_google_5'   => 'Скопируйте ключ браузера в ключ API Google Maps, а при использовании серверного ключа — в серверный ключ API Google Maps в настройках Карт Geeklog.',
    'admin_help_security'   => 'Никогда не оставляйте ключ Google Maps без ограничений в рабочей среде. Раздельные ключи браузера и сервера снижают риск несанкционированного использования.',
    'admin_help_create'     => 'Создайте первую карту',
    'admin_help_create_1'   => 'Нажмите «Создать новую карту», задайте имя и выберите центр, масштаб и тип отображения.',
    'admin_help_create_2'   => 'Сохраните карту, затем добавьте маркеры в управлении Картами. Каждый маркер может использовать адрес или точные координаты.',
    'admin_help_create_3'   => 'Значки и наложения необязательны. Начните с простой карты и нескольких маркеров, чтобы проверить настройки Google Maps.',
    'admin_help_trouble'    => 'Если карта затемнена или показывает «Только для разработки», проверьте биллинг Google Cloud, включённые API и ограничения ключа API.',
    'admin_help_official'   => 'Официальная документация Google Maps Platform',
    'admin_help_geo_title'  => 'Что делает «Проверить геолокацию пользователей»?',
    'admin_help_geo_intro'  => 'Эта команда проверяет участников, заполнивших поле Местоположение в профиле, и подготавливает координаты для карты пользователей.',
    'admin_help_geo_1'      => 'Карты отправляют каждое неразрешённое текстовое местоположение в Google Geocoding API, например «Нант, Франция».',
    'admin_help_geo_2'      => "Полученные широта и долгота кэшируются в таблице геокодирования Карт. Профиль Geeklog участника не изменяется.",
    'admin_help_geo_3'      => 'Это особенно полезно после установки, миграции или когда многие участники добавили или изменили местоположение. Требуется Geocoding API и ключ, разрешённый для серверного геокодирования.',
    'admin_help_overlays_title' => 'Для чего нужны наложения?',
    'admin_help_overlays_intro' => 'Наложение — это геопривязанное изображение, размещённое поверх карты Google между юго-западными и северо-восточными координатами. Оно добавляет визуальную информацию, отсутствующую в базовом слое Google Maps.',
    'admin_help_overlays_1' => 'Отобразить план участка, кемпинга, парка, территории, здания или фестиваля поверх реальной карты.',
    'admin_help_overlays_2' => 'Наложить историческую, кадастровую, геологическую или туристическую карту либо старый план для сравнения.',
    'admin_help_overlays_3' => 'Показать тематическую область: иллюстрированный маршрут, рабочую зону, природную территорию, границы проекта или другую графическую информацию.',
    'admin_help_overlays_4' => 'Задайте минимальный и максимальный масштаб, чтобы наложение показывалось только тогда, когда это полезно.',
    'admin_help_overlays_how' => 'Чтобы создать наложение: подготовьте подходящее изображение, откройте Наложения, введите юго-западные и северо-восточные границы и прикрепите его к карте на вкладке Наложения редактора. Наложения необязательны: для обычной карты с точками достаточно маркеров.',
    'admin_help_concepts_title' => 'Основные понятия Карт',
    'admin_help_concept_map' => 'Карта',
    'admin_help_concept_map_text' => 'Основной контейнер: центр, масштаб, тип отображения, размеры, права и общие параметры.',
    'admin_help_concept_marker' => 'Маркер',
    'admin_help_concept_marker_text' => 'Географическая точка на карте с названием, описанием, адресом и дополнительной информацией.',
    'admin_help_concept_icon' => 'Значок',
    'admin_help_concept_icon_text' => 'Необязательное изображение вместо стандартного маркера Google для различения категорий точек.',
    'admin_help_concept_users' => 'Карта пользователей',
    'admin_help_concept_users_text' => 'Карта, созданная из поля Местоположение профиля Geeklog. Координаты определяются и кэшируются системой геокодирования Карт.',
    'admin_help_trouble_title' => 'Быстрое устранение неполадок',
    'profile_title'         => 'Геолокация',
    'buy_marker'            => 'Купить маркер',
    'menu_label'            => 'Управление Картами',
    'admin_home'            => 'Главная', // In admin menu
    'user_home'             => 'Все карты', //In user menu
    'maps'                  => 'Карты',
    'маркеры'               => 'Маркеры',
    'maps_label'            => 'Карты', // For user  menu
    'create_map'            => 'Создать новую карту',
    'create_marker'         => 'Создать новый маркер',
    'map_edit'              => 'Редактировать карту',
    'marker_edit'           => 'Редактировать маркер',
    'deletion_succes'       => 'Удаление выполнено',
    'deletion_fail'         => 'Ошибка удаления',
    'error'                 => 'Ошибка',
    'save_fail'             => 'Ошибка сохранения',
    'save_success'          => 'Сохранено',
    'missing_field'         => 'Не заполнено обязательное поле…',
    'geocoder'              => 'Геокодер',
    'geocoder_text'         => 'Введите адрес и перетащите маркер для уточнения положения. Широта/долгота будут появляться в информационном окне после каждого геокодирования или перемещения.',
    'geocode_failed'         => 'Не удалось геокодировать адрес. Проверьте ключ API Google Maps, активацию Geocoding API и адрес, затем повторите попытку.',
    'go'                    => 'Перейти!',
    'name_label'            => 'Название карты: ',
    'marker_name_label'     => 'Название маркера: ',
    'description_label'     => 'Описание:',
    'ok_button'             => 'ОК',
    'edit_button'           => 'Редактировать',
    'save_button'           => 'Сохранить',
    'delete_button'         => 'Удалить',
    'yes'                   => 'Да',
    'no'                    => 'Нет',
    'required_field'        => 'Обозначает обязательное поле',
    'address_label'         => 'Адрес: ',
    'message'               => 'Сообщение',
    'general_settings'      => 'Общие настройки',
    'map_width'             => 'Ширина карты (% или px, минимум 550px): ',
    'map_height'             => 'Высота карты (только px, минимум 350px): ',
    'map_zoom'              => 'Масштаб карты (0-21): ',
    'map_type'              => 'Тип карты: ',
    'active'                => 'Карта активна: ',
    'hidden'                => 'Карта скрыта: ',
    'marker_active'         => 'Маркер активен: ',
    'marker_hidden'         => 'Маркер скрыт: ',
    'free_marker'           => 'Карта принимает бесплатные маркеры: ',
    'paid_marker'           => 'Карта принимает платные маркеры: ',
    'error_address_empty'   => 'Сначала введите корректный адрес.',
    'error_invalid_address' => 'Этот адрес некорректен. Убедитесь, что указаны номер дома и город.',
    'error_google_error'    => 'При обработке запроса возникла проблема. Повторите попытку.',
    'error_no_map_info'     => 'Для этого адреса информация карты недоступна.',
    'need_directions'       => 'Нужен маршрут? Введите адрес:',
    'directions_title'     => 'Построить маршрут',
    'directions_start'     => 'Начальная точка',
    'get_directions'        => '  Построить маршрут  ',
    'maps_list'             => 'Список карт',
    'you_can'               => 'Вы можете ',
    'user_maps_list'        => 'Просмотреть наши карты',
    'markers_list'          => 'Список маркеров',
    'map_markers_heading'   => 'Маркеры на этой карте',
    'marker_singular'      => 'маркер',
    'marker_plural'        => 'маркеры',
    'views_label'          => 'просмотры',
    'no_map'                => 'В базе данных нет карт. Необходимо создать карту, чтобы добавлять маркеры.',
    'no_map_user'           => 'Упс… В базе данных нет активной карты.',
    'value_directions'      => 'например: номер дома, улица, город, страна', // No quote here please
    'id'                    => 'ID',
    'name'                  => 'Название',
    'description'           => 'Описание',
    'active_field'          => 'Активно',
    'hidden_field'          => 'Скрыто',
    'marker_count'          => 'Маркеры',
    'status_active'         => 'Активно',
    'status_inactive'       => 'Неактивно',
    'status_visible'        => 'Видимо',
    'status_hidden'         => 'Скрыто',
    'title_display'         => 'Показать страницу карты',
    'map_header_label'      => 'Необязательный заголовок карты',
    'map_footer_label'      => 'Необязательный нижний колонтитул карты',
    'header_footer'         => 'Заголовок и нижний колонтитул',
    'informations'          => 'Информация',
    'must_belong_to'        => 'Для доступа к этой карте необходимо состоять в группе:',
    'private_access'        => 'Закрытый доступ',
    'marker_label'          => 'Маркер',
    'primary_color_label'   => 'Основной цвет',
    'stroke_color_label'    => 'Цвет контура',
    'label'                 => 'Метка',
    'label_color'           => 'Цвет метки',
    'black'                 => 'Чёрный',
    'white'                 => 'Белый',
    'payed'                 => 'Платный маркер:',
    'lat'                   => 'Широта:',
    'lng'                   => 'Долгота:',
    'ressources_tab'        => 'Вкладка Ресурсы',
    'presentation'          => 'Представление',
    'ressources'            => 'Ресурсы',
    'presentation_tab'      => 'Вкладка Представление',
    'empty_ressources'      => 'Метки ресурсов пусты. Чтобы использовать ресурсы, задайте хотя бы одну. См. настройки.',
    'empty_for_geo'         => 'Оставьте широту и долготу пустыми для автоматической геолокации по указанному выше адресу.',
    'select_marker_map'     => 'Выберите карту, на которой должен появиться маркер.',
    'remark'                => 'Примечания',
    'marker_created'        => 'Маркер создан:',
    'map_created'           => 'Карта создана:',
    'modified'              => 'Последнее изменение:',
    'marker_validity'       => 'Использовать срок действия:',
    'maps_empty'            => 'Сначала создайте карту.',
    'from'                  => 'С:',
    'до'                    => 'До:',
    'date_issue'            => 'Дата окончания раньше даты начала. Проверьте данные.',
    'max_char'              => 'максимум символов.',
    'street_label'          => 'Улица:',
    'code_label'            => 'Почтовый индекс:',
    'city_label'            => 'Город:',
    'state_label'           => 'Регион/область:',
    'country_label'         => 'Страна:',
    'tel_label'             => 'Тел.:',
    'fax_label'             => 'Дополнительный контакт:',
    'web_label'             => 'Веб:',
    'not_use_see_config'    => 'Не использовать. См. настройки',
    //global maps
    'global_map'            => 'Глобальная карта',
    'info_global_map'       => 'Все карты в одной.',
    'users_map'             => 'Карта пользователей сайта',
    'info_users_map'        => 'Это карта пользователей сайта. Вы можете добавить себя, указав местоположение в профиле.',
    //Submission
    'address'               => 'Адрес',
    'created'               => 'Дата',
    'submit_marker'         => 'Отправить маркер',
    'submit_marker_text'    => '<p><ol><li>Укажите положение маркера<li>Заполните все поля<li>Подтвердите</ol></p>',
    'markers_submissions'   => 'Заявки на маркеры',
    'submission_disabled'   => 'Очередь заявок для маркеров отключена',
    'go'                    => 'Показать этот адрес',
    //date and hits
    'last_modification'     => 'Последнее изменение:',
    'просмотры'                  => 'просмотры',
    //user marker
    'member'                => 'Участник',
    'location'              => 'Местоположение: ',
    'regdate'               => 'Участник с: ',
    'about'                 => 'О пользователе',
    'my_markers'            => 'Мои маркеры',
    'payed_label'           => 'Платный',
    'from_label'            => 'Действует с',
    'to_label'              => 'Действует до',
    'no_marker'             => 'У вас нет маркеров либо они ещё не одобрены. Если это ошибка, обратитесь к администратору сайта.',
    'marker_detail'         => 'Сведения о маркере',
    'admin_can'             => 'Как администратор карт вы можете',
    'create_map'            => 'Создать новую карту',
    'set_user_geo'          => 'Установить геолокацию пользователей',
    'set_geo_location'      => 'Система проверит и установит все геолокации.',
    'записи'               => 'записи',
    'report'                => 'Сообщить об этом маркере',
    'report_subject'        => 'Сообщение о маркере ',
    'edit_marker_text'      => '<p><ol><li>Укажите положение маркера<li>Заполните все обязательные поля<li>Затем подтвердите</ol></p>',
    'admin'                 => 'Администрирование',
    'category_label'        => 'Категория:',
    'choose_category'       => '-- Выберите категорию --',
    'categories'            => 'Категории',
    'categories_list'       => 'Список категорий',
    'cat_edit'              => 'Редактирование категории:',
    'cat_name_label'        => 'Название категории:',
    'create_cat'            => 'создать новую категорию',
    'field_list'            => 'Список полей',
    'addfield'              => 'Добавить поле',
    'field_name'            => 'Название поля',
    'field_order'           => 'Порядок',
    'field_autotag'         => 'Автотег',
    'field_rights'          => 'Права',
    'field_edit'            => 'Редактировать',
    'valid'                 => 'Допустимо',
    'editing_field'         => 'Редактирование поля',
    'category'              => 'Категория',
    'map_label'             => 'Карта',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => 'Просмотреть карту',
    'view_markers'          => 'Показать список маркеров',
    'code'                  => 'Почтовый индекс',
    'city'                  => 'Город',
    'viewing_markers'       => 'Показать список маркеров',
    'details'               => 'Подробности',
    'view_details'          => 'Просмотреть подробности',
    'print'                 => 'Печать',
	'to_complete'           => 'Требует заполнения',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - Показывает карту с id=XX. Параметры: масштаб (от 0 до 21) и центрирование карты по location.',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - Показывает карту, центрированную по названию места или адресу. Необязательные параметры: zoom, width и height. Исторический синтаксис [geo: map ...] поддерживается.',
	'autotag_desc_marker'   => '[marker: xx] - Показывает маркер с id=XX',
	//v1.1
	'marker_customisation'  => 'Настройка маркера',
	'mk_default'            => 'Использовать маркер по умолчанию',
	'overlays'              => 'Наложения',
	'overlays_list'         => 'Список наложений',
	'create_overlay'        => 'Создать новое наложение',
	'edit_overlay_text'     => 'Редактировать наложение:',
	'overlay_edit'          => 'Редактировать наложение',
	'overlay_name_label'    => 'Название наложения:',
	'overlay_presentation'  => 'Наложения — объекты карты, привязанные к координатам широты/долготы, поэтому они перемещаются при перетаскивании или масштабировании карты. Они обозначают точки, линии или области. Здесь можно добавить изображение как наложение.',
	'overlay_active'        => 'Это наложение активно:',
	'zoom_min_label'        => 'Мин. масштаб:',
	'zoom_max_label'        => 'Макс. масштаб:',
	'image_message'         => 'Выберите изображение с диска.',
	'image_replace'         => 'Загрузка нового изображения заменит текущее:',
	'image'                 => 'Изображение',
	'sw_lat'                => 'ЮЗ широта:',
    'sw_lng'                => 'ЮЗ долгота:',
	'ne_lat'                => 'СВ широта:',
    'ne_lng'                => 'СВ долгота:',
	'overlay_not_writable'  => 'Папка наложений недоступна для записи. Создайте её и разрешите запись перед использованием этой функции.',
	'map_tab'               => 'Карта',
	'overlays_tab'          => 'Наложения',
	'add_overlay'           => 'Добавить наложение',
	'remove_overlay'        => 'Удалить наложение',
	'overlay_label'         => 'Наложение',
	'import_export'         => 'Импорт/Экспорт',
	'import'                => 'Импорт',
	'export'                => 'Экспорт',
	'select_file'           => 'Выберите файл .csv',
	'import_message'        => 'Выберите карту для добавления маркеров, CSV-файл с диска, разделитель данных и поля для импорта.',
	'markers_added'         => 'Маркеры, добавленные на карту:',
	'export_message'        => 'Выберите карту для экспорта маркеров, разделитель данных и поля для экспорта.',
	'no_marker_to_export'   => 'На этой карте нет маркеров для экспорта.',
	'icons'                 => 'Значки',
	'icons_not_writable'    => 'Папка значков недоступна для записи. Создайте её и разрешите запись перед использованием этой функции.',
	'icons_list'            => 'Список значков',
	'create_icon'           => 'Создать новый значок',
	'icon_edit'             => 'Редактировать значок',
	'icon_presentation'     => 'Здесь можно загрузить новый значок для маркеров', 
	'icon_name_label'       => 'Название значка',
	'xmarkers'              => 'маркеры',
	'1marker'               => 'маркер',
	'choose_icon'           => 'Для этого маркера можно выбрать значок. Приоритетные значки располагаются над цветами.',
	'no_icon'               => 'Без значка',
	'no_custom_icons'        => 'Пользовательские значки ещё не зарегистрированы.',
	'manage_icons'           => 'Управление значками',
	'separator'             => 'Выберите разделитель',
	'markers_to_add'        => 'Проверьте все пары поле/значение и подтвердите добавление всех перечисленных маркеров на карту:',
	'choose_fields_import'  => 'Выберите поля для импорта',
	'choose_fields_export'  => 'Выберите поля для экспорта',
	'checkall'              => 'Выбрать всё',
    'import_step_1' => 'Подготовка импорта',
    'import_step_1_text' => 'Выберите целевую карту, CSV-файл, разделитель и порядок столбцов.',
    'import_step_2' => 'Проверка данных',
    'import_step_2_text' => 'Карты проверяют, нормализуют и геокодируют строки до записи данных.',
    'import_step_3' => 'Подтверждение импорта',
    'import_step_3_text' => 'Проверьте назначение, владельца и права, затем подтвердите пакет.',
    'import_minimum' => 'Минимальные поля',
    'import_minimum_help' => 'name + address или name + lat + lng. Набор Минимум использует вариант с адресом.',
    'import_recommended' => 'Рекомендуемые поля',
    'import_recommended_help' => 'name, address, lat, lng, description, street, code, city, state, country, tel и web.',
    'import_order_help' => 'Столбцы CSV должны следовать в том же порядке, что и выбранные ниже поля.',
    'import_select_minimum' => 'Минимальные поля',
    'import_select_recommended' => 'Рекомендуемые поля',
    'import_clear_fields' => 'Очистить выбор',
    'import_preview_title' => 'Проверка данных',
    'import_preview_text' => 'Это нормализованные значения, которые будут записаны после подтверждения импорта.',
    'import_summary_rows' => 'строк готово',
    'import_summary_coordinates' => 'координаты указаны',
    'import_summary_geocoded' => 'геокодировано автоматически',
    'import_summary_partial' => 'с неполными данными адреса',
    'import_status' => 'Состояние',
    'import_status_ready' => 'Готово',
    'import_status_partial' => 'Готово · неполные данные',
    'import_status_geocoded' => 'геокодировано',
    'import_confirm_title' => 'Подтверждение импорта',
    'import_confirm_text' => 'Проверьте настройки пакета перед созданием маркеров.',
    'import_confirm_button' => 'Импортировать %d маркеров',
    'import_cancel_button' => 'Отмена',
	'order'                 => 'Порядок',
	'move'                  => 'Переместить',
	'name_missing'          => 'Отсутствует как минимум одно название. Проверьте CSV-файл.',
	'need_address'          => 'Для создания маркера нужен как минимум адрес или координаты. Проверьте CSV-файл: не хватает данных.',
	'manage_groups'         => 'Управление группами наложений',
	'create_group'          => 'Создать новую группу наложений',
	'group_edit'            => 'Редактировать группу наложений',
	'group_overlay_presentation' => 'Здесь можно выбрать или изменить название группы наложений',
	'group_overlay_name_label'   => 'Название группы',
	'group_label'           => 'Группа (необязательно)',
	'choose_group'          => 'Выберите группу',
	'group'                 => 'Группа',
	
	//v1.3
	'geo_fail'              => 'Введённый адрес, похоже, некорректен',
	'on_map'                => 'На карте',
	'read_more'             => 'Подробнее',
	'from_map'              => 'Карта:',
	'show_hide_overlays'    => 'Показать / скрыть наложения',
	'fields_presentation'   => 'Отредактируйте существующую категорию, чтобы добавить или изменить поле.',
	'overlays_added'        => 'Наложения на этой карте',
	'overlays_to_add'       => 'Наложения, которые можно добавить на карту',
	'marker_modification'   => 'Изменение маркера',
	'from_owner'            => 'Добавил:',
	'marker_limited'        => 'Доступ к этому маркеру ограничен…',
	'events_map'            => 'Карта ближайших событий',
	'info_events_map'       => '',
	'from_cal'              => 'С',
	'to_cal'                => 'до',
	'on_cal'                => 'Вкл.',
    //v1.4
    'admin_menu_maps' => 'Карты',
    'admin_menu_markers' => 'Маркеры',
    'admin_menu_icons' => 'Значки',
    'admin_menu_overlays' => 'Наложения',
    'admin_menu_import_export' => 'Импорт/Экспорт',
    'admin_menu_geocoder' => 'Геокодер',
    'admin_menu_geolocation' => 'Геолокация',
    'admin_menu_configuration' => 'Конфигурация',
    'section_location' => 'Местоположение',
    'section_content_contact' => 'Содержимое и контакты',
    'section_appearance' => 'Внешний вид',
    'section_publication' => 'Публикация',
    'section_resources' => 'Ресурсы',
    'section_ownership' => 'Владелец',
    'section_permissions' => 'Права',
    'delete_confirm' => 'Удалить этот маркер навсегда?',
    'marker_not_found' => 'Маркер не найден или у вас нет прав для его просмотра.',
    'delete_map_confirm' => 'Удалить эту карту навсегда?',
    'technical_coordinates' => 'Технические координаты',
    'configuration'         => 'Конфигурация',
    // Maps 1.5.6 map editor
    'map_section_display' => 'Отображение',
    'map_section_center' => 'Центр и масштаб',
    'map_section_markers' => 'Маркеры',
    'map_section_advanced' => 'Расширенные параметры',
    'map_center_search' => 'Найти адрес',
    'map_center_search_button' => 'Найти',
    'map_center_use_button' => 'Использовать показанный центр',
    'map_center_help' => 'Найдите адрес, щёлкните по карте или перетащите маркер, чтобы точно выбрать центр карты.',
    'latitude_label' => 'Широта',
    'longitude_label' => 'Долгота',
    'map_center_marker' => 'Центр карты',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'Сообщение системы',
    'add_new_field'         => 'Новое поле успешно создано',
    'save_field'            => 'Поле успешно сохранено',
    'delete_field'          => 'Поле успешно удалено'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => 'Здравствуйте, администратор!',
    'new_marker'            => 'Новый маркер ожидает одобрения.',
    'name'                  => 'Название:',
    'on_map'                => 'На карте:',
    'submissions'           => 'Заявки: ',
    'marker_submissions'    => 'Заявки на маркеры',
	'marker_modification'   => 'Изменение маркера',
	'description'           => 'Описание:',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "Спасибо за отправку маркера на {$_CONF['site_name']}. Он передан сотрудникам на одобрение.";
$PLG_maps_MESSAGE2  = "Отправка маркеров закрыта.";
$PLG_maps_MESSAGE3  = "Упс… Произошла ошибка. Не удалось сохранить маркер.";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'Карты',
    'title' => 'Настройка Карт'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'Скрыть меню Карт',
    'maps_login_required'   => 'Для Карт требуется вход',
    'autofill_coord'        => 'Автоматически заполнять неопределённые координаты',
    'display_geo_profile'   => 'Геолокация профиля',
    'map_type_profile'      => 'Тип карты профиля',
    'map_type_geotag'       => 'Тип карты автотега geo',
    'show_directions_geo'   => 'Показывать маршрут в автотеге geo',
    'show_directions_profile' => 'Показывать маршрут в профиле',
    'map_width_geotag'      => 'Ширина карты автотега geo (в % или px)',
    'map_height_geotag'     => 'Высота карты автотега geo (только px)',
    'map_zoom_geotag'       => 'Масштаб автотега geo (0-21)',
    'map_width_profile'     => 'Ширина карты профиля (в % или px)',
    'map_height_profile'    => 'Высота карты профиля (только px)',
    'show_map'              => 'Показывать карту Google',
    'google_api_key'        => 'Ключ API Google Maps',
    'url_geocode'           => 'URL службы Google Geocoding',
    'map_width'             => 'Ширина карт по умолчанию (в % или px)',
    'map_height'            => 'Высота карт по умолчанию (только px)',
    'map_zoom'              => 'Масштаб карт по умолчанию (0-21)',
    'map_type'              => 'Тип карты по умолчанию',
    'default_permissions'   => 'Права по умолчанию',
    'map_main_header'       => 'Заголовок главной страницы, автотег welcome',
    'map_main_footer'       => 'Нижний колонтитул главной страницы, также автотег welcome',
    'map_geo'               => 'Создать карту со всеми профилями',
    'map_markers'           => 'Создать карту со всеми маркерами',
    'map_active'            => 'Карта активна',
    'map_hidden'            => 'Карта скрыта',
    'free_markers'          => 'Карта принимает бесплатные маркеры',
    'paid_markers'          => 'Карта принимает платные маркеры (требуется плагин PayPal)',
    'street'                => 'Использовать улицу',
    'code'                  => 'Использовать почтовый индекс',
    'city'                  => 'Использовать город',
    'state'                 => 'Использовать регион',
    'country'               => 'Использовать страну',
    'tel'                   => 'Использовать телефон',
    'fax'                   => 'Использовать дополнительный контакт',
    'web'                   => 'Использовать веб-сайт',
    'item_1'                => 'Метка пользовательского поля 1',
    'item_2'                => 'Метка пользовательского поля 2',
    'item_3'                => 'Метка пользовательского поля 3',
    'item_4'                => 'Метка пользовательского поля 4',
    'item_5'                => 'Метка пользовательского поля 5',
    'item_6'                => 'Метка пользовательского поля 6',
    'item_7'                => 'Метка пользовательского поля 7',
    'item_8'                => 'Метка пользовательского поля 8',
    'item_9'                => 'Метка пользовательского поля 9',
    'item_10'               => 'Метка пользовательского поля 10',
    'label_color'           => 'Цвет метки',
    'star_primary_color'    => 'Основной цвет звезды',
    'star_stroke_color'     => 'Цвет контура звезды',
    'marker_active'         => 'Маркер активен по умолчанию',
    'marker_hidden'         => 'Маркер скрыт по умолчанию',
    'marker_payed'          => 'Маркер платный по умолчанию',
    'marker_validity'       => 'Срок действия маркера по умолчанию',
    'marker_submission'     => 'Разрешить отправку маркеров',
    'users_map'             => 'Активная карта пользователей сайта',
    'global_map' 	        => 'Активная глобальная карта',
    'global_type'           => 'Тип глобальной карты',	
    'global_width'  	    => 'Ширина глобальной карты',
    'global_height' 	    => 'Высота глобальной карты',
    'global_zoom'           => 'Масштаб глобальной карты (0-21)',
    'detail_zoom'           => 'Масштаб сведений о маркере (0-21)',
    'submit_login_required' => 'Требовать вход для отправки маркеров',
    'marker_edition'        => 'Редактирование маркера',
	'use_cluster'           => 'Использовать кластеризацию маркеров',
	'zoom_profile'          => 'Масштаб карты в профиле пользователя (0-21)',
	'display_events_map'    => 'Показывать карту событий',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'Основные настройки',
    'sg_display' => 'Настройки отображения'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => 'Общие',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Карты',
    'tab_markers' => 'Маркеры',
    'tab_fields' => 'Поля маркера',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => 'Общие',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Карты',
    'tab_markers' => 'Маркеры',
    'tab_fields' => 'Поля маркера',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => 'Общие настройки',
    'fs_ads'             => 'Настройки Google Ads',
    'fs_google'          => 'Настройки Google API',
    'fs_permissions'     => 'Права по умолчанию',
    'fs_display'         => 'Карты',
    'fs_global_map'      => 'Глобальные карты',
    'fs_display_profile' => 'Профиль',
    'fs_display_geo'     => 'автотег geo',
    'fs_map_default'     => 'Настройки карты по умолчанию',
    'fs_marker_default'  => 'Настройки маркера по умолчанию',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('Истина' => 1, 'Ложь' => 0),
    1 => array('Истина' => TRUE, 'Ложь' => FALSE),
    3 => array('Да' => 1, 'Нет' => 0),
    4 => array('Вкл.' => 1, 'Выкл.' => 0),
    5 => array('Верх страницы' => 1, 'Под избранной статьёй' => 2, 'Низ страницы' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('Мили' => 'мили', 'Километры' => 'km'),
    12 => array('Нет доступа' => 0, 'Только чтение' => 2, 'Чтение и запись' => 3),
	// changed in v1.3
    20 => array('Обычная дорожная карта' => 'ROADMAP', 'Спутниковые снимки' => 'SATELLITE', 'Карта рельефа' => 'TERRAIN', 'Прозрачный слой основных дорог поверх спутниковых снимков' => 'HYBRID'),
    30 => array('Белый' => 1, 'Чёрный' => 0),
    31 => array('Временно' => 1, 'Постоянно' => 0),
);

$LANG_MAPS_1['location_search_label'] = 'Найти адрес';
$LANG_MAPS_1['location_search_help'] = 'Найдите адрес, щёлкните по карте или перетащите маркер для точной настройки положения.';
$LANG_MAPS_1['use_map_click_help'] = 'Щёлкните по карте, чтобы переместить маркер.';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = 'Основное';
$LANG_configtabs['maps']['tab_general'] = 'Общие';
$LANG_tab['maps']['tab_general'] = 'Общие';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = 'Карты';
$LANG_tab['maps']['tab_maps'] = 'Карты';
$LANG_configtabs['maps']['tab_markers'] = 'Маркеры';
$LANG_tab['maps']['tab_markers'] = 'Маркеры';
$LANG_configtabs['maps']['tab_fields'] = 'Поля маркера';
$LANG_tab['maps']['tab_fields'] = 'Поля маркера';

$LANG_fs['maps']['fs_main'] = 'Доступ и функции';
$LANG_fs['maps']['fs_permissions'] = 'Права по умолчанию';
$LANG_fs['maps']['fs_uploads'] = 'Изображения и загрузки';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = 'Общее отображение';
$LANG_fs['maps']['fs_global_map'] = 'Глобальная карта и карта пользователей';
$LANG_fs['maps']['fs_display_profile'] = 'Карта профиля пользователя';
$LANG_fs['maps']['fs_display_geo'] = 'Автотег geo';
$LANG_fs['maps']['fs_map_defaults'] = 'Настройки новых карт';
$LANG_fs['maps']['fs_events_map'] = 'Карта событий';
$LANG_fs['maps']['fs_marker_defaults'] = 'Настройки маркеров';
$LANG_fs['maps']['fs_marker_editor'] = 'Карта редактора маркеров';
$LANG_fs['maps']['fs_marker_detail'] = 'Карта сведений о маркере';
$LANG_fs['maps']['fs_marker_popup'] = 'Информационные окна маркера';
$LANG_fs['maps']['fs_marker_fields'] = 'Поля и метки маркеров';

$LANG_confignames['maps']['max_image_width'] = 'Максимальная ширина изображения (px)';
$LANG_confignames['maps']['max_image_height'] = 'Максимальная высота изображения (px)';
$LANG_confignames['maps']['max_image_size'] = 'Максимальный размер изображения (байт)';
$LANG_confignames['maps']['google_api_key'] = 'Ключ API Google Maps для браузера';
$LANG_confignames['maps']['google_server_api_key'] = 'Серверный ключ API Google Geocoding';
$LANG_confignames['maps']['google_map_id'] = 'Google Map ID (подготовка Advanced Markers)';
$LANG_confignames['maps']['google_language'] = 'Язык Google Maps (необязательно, например ru)';
$LANG_confignames['maps']['google_region'] = 'Регион Google Maps (необязательно, например RU)';
$LANG_confignames['maps']['url_geocode'] = 'URL службы Google Geocoding';
$LANG_confignames['maps']['map_primary_color'] = 'Основной цвет карты по умолчанию';
$LANG_confignames['maps']['map_stroke_color'] = 'Цвет контура карты по умолчанию';
$LANG_confignames['maps']['map_label'] = 'Метка маркера карты по умолчанию';
$LANG_confignames['maps']['map_label_color'] = 'Цвет метки карты по умолчанию';
$LANG_confignames['maps']['events_map_zoom'] = 'Масштаб карты событий';
$LANG_confignames['maps']['events_map_height'] = 'Высота карты событий';
$LANG_confignames['maps']['users_map_lat'] = 'Широта центра карты пользователей (пусто = автоматически)';
$LANG_confignames['maps']['users_map_lng'] = 'Долгота центра карты пользователей (пусто = автоматически)';
$LANG_confignames['maps']['users_map_zoom'] = 'Масштаб карты пользователей (пусто = глобальная карта)';
$LANG_confignames['maps']['users_map_type'] = 'Тип карты пользователей (пусто = глобальная карта)';
$LANG_confignames['maps']['users_map_width'] = 'Ширина карты пользователей (пусто = глобальная карта)';
$LANG_confignames['maps']['users_map_height'] = 'Высота карты пользователей (пусто = глобальная карта)';
$LANG_confignames['maps']['marker_editor_type'] = 'Тип карты редактора маркеров';
$LANG_confignames['maps']['marker_editor_zoom'] = 'Начальный масштаб редактора маркеров';
$LANG_confignames['maps']['marker_editor_width'] = 'Ширина карты редактора маркеров';
$LANG_confignames['maps']['marker_editor_height'] = 'Высота карты редактора маркеров';
$LANG_confignames['maps']['detail_width'] = 'Ширина карты сведений о маркере';
$LANG_confignames['maps']['detail_height'] = 'Высота карты сведений о маркере';
$LANG_confignames['maps']['detail_zoom'] = 'Масштаб карты сведений о маркере';
$LANG_confignames['maps']['popup_width'] = 'Ширина информационного окна';
$LANG_confignames['maps']['popup_height'] = 'Высота информационного окна';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = 'SEO стартовой страницы';
$LANG_confignames['maps']['maps_page_title'] = 'SEO-заголовок стартовой страницы Карт';
$LANG_confignames['maps']['maps_page_h1'] = 'Заголовок H1 стартовой страницы Карт';
$LANG_confignames['maps']['maps_meta_description'] = 'Метаописание стартовой страницы Карт';
$LANG_confignames['maps']['map_main_header'] = 'Вводное содержимое стартовой страницы Карт (поддерживаются автотеги)';

$LANG_MAPS_1['server_geocode_key_missing'] = 'Серверный поиск координат включён, но отдельный серверный ключ API Google Geocoding не настроен. Ключ браузера никогда не используется для серверного геокодирования.';
$LANG_MAPS_1['api_diag_title'] = 'Настройка Google Maps Platform';
$LANG_MAPS_1['api_diag_maps_js'] = 'Maps JavaScript API';
$LANG_MAPS_1['api_diag_geocoding'] = 'Geocoding API';
$LANG_MAPS_1['api_diag_directions'] = 'Directions API';
$LANG_MAPS_1['api_diag_browser_key'] = 'Ключ API браузера';
$LANG_MAPS_1['api_diag_server_key'] = 'Серверный ключ API';
$LANG_MAPS_1['api_diag_map_id'] = 'ID карты';
$LANG_MAPS_1['api_diag_configured'] = 'Ключ настроен — API не проверен';
$LANG_MAPS_1['api_diag_browser_verify'] = 'Ключ настроен — проверьте его тестом браузера ниже';
$LANG_MAPS_1['api_diag_referrer_hint'] = 'Для ограничений HTTP-реферера ключа браузера разрешите этот сайт (например: %s/*).';
$LANG_MAPS_1['api_diag_missing'] = 'Отсутствует';
$LANG_MAPS_1['api_diag_optional'] = 'Необязательно / не настроено';
$LANG_MAPS_1['integrations_title'] = 'Интеграции';
$LANG_MAPS_1['integrations_intro'] = 'Карты используют API и службы Geeklog, чтобы связанные плагины могли обнаруживать Карты без прямой привязки к базе данных.';
$LANG_MAPS_1['integration_active'] = 'Активно';
$LANG_MAPS_1['integration_missing'] = 'Плагин отсутствует';
$LANG_MAPS_1['integration_native'] = 'Встроенная поддержка';
$LANG_MAPS_1['integration_xmlsitemap'] = 'XML-карта сайта';
$LANG_MAPS_1['integration_documents'] = 'Документы';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'Ленты RSS / Atom';
$LANG_MAPS_1['use_my_location'] = 'Использовать моё местоположение';
$LANG_MAPS_1['geolocation_unavailable'] = 'Геолокация браузера недоступна. Введите начальный адрес вручную.';
$LANG_MAPS_1['geolocation_denied'] = 'Не удалось получить ваше местоположение. Разрешите доступ к местоположению или введите начальный адрес вручную.';
$LANG_MAPS_1['geolocation_https'] = 'Геолокация браузера обычно требует HTTPS.';
?>
