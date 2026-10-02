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
    'plugin_name'           => 'מפות',
    'plugin_conf'           => 'הגדרות התוסף',
    'map'                   => 'מפה',
    'need_google_api'       => 'לא מוגדר מפתח API של Google Maps לדפדפן. לא ניתן להציג מפות Google עד להוספת המפתח.',
    'api_status_title' => 'מצב API של Google Maps',
    'api_status_missing' => 'לא מוגדר מפתח API של Google Maps לדפדפן.',
    'api_status_testing' => 'בודק את Google Maps JavaScript API…',
    'api_status_key' => 'מפתח דפדפן',
    'api_status_ok' => 'Google Maps JavaScript API נטען בהצלחה: מפתח הדפדפן התקבל עבור עמוד זה.',
    'api_status_auth' => 'Google Maps דחה את מפתח הדפדפן או את ההגדרה שלו. בדקו במסוף הדפדפן את קוד השגיאה המדויק של Google, ולאחר מכן את הגבלות מפני HTTP, ממשקי ה-API הפעילים והחיוב ב-Google Cloud.',
    'api_status_load' => 'לא ניתן לטעון את סקריפט Google Maps JavaScript API. בדקו את הרשת, מדיניות CSP, חוסמי תוכן ומסוף הדפדפן.',
    'api_status_timeout' => 'Google Maps JavaScript API לא הגיב. בדקו את מסוף הדפדפן ואת חלונית הרשת.',
    'admin_help_title'      => 'תחילת עבודה עם מפות',
    'admin_help_intro'      => 'מפות מאפשר ליצור מספר מפות, להוסיף סמנים ובמידת הצורך להשתמש בסמלים מותאמים או שכבות-על.',
    'admin_help_google'     => 'הגדרת Google Maps',
    'admin_help_google_1'   => 'פתחו את Google Cloud Console, צרו או בחרו פרויקט וחברו חשבון חיוב לשימוש בסביבת ייצור.',
    'admin_help_google_2'   => 'הפעילו לפחות את Maps JavaScript API. הפעילו גם את Geocoding API אם נעשה שימוש בהמרה אוטומטית של כתובות לקווי רוחב/אורך.',
    'admin_help_google_3'   => 'צרו מפתח API לשימוש בדפדפן. הגבילו אותו לאתרים מורשים (HTTP referrers), למשל https://www.example.com/*, ולאחר מכן הגבילו אותו ל-Maps JavaScript API.',
    'admin_help_google_4'   => 'לגאוקידוד בצד השרת מומלץ ליצור מפתח שני. הגבילו אותו לכתובת ה-IP של השרת ול-Geocoding API בלבד.',
    'admin_help_google_5'   => 'העתיקו את מפתח הדפדפן לשדה Google Maps API key ואת מפתח השרת, אם משתמשים בו, לשדה Google Maps server API key בהגדרות מפות של Geeklog.',
    'admin_help_security'   => 'אל תשאירו מפתח Google Maps ללא הגבלות בסביבת ייצור. הפרדת מפתחות הדפדפן והשרת מפחיתה את הסיכון לשימוש לא מורשה.',
    'admin_help_create'     => 'יצירת המפה הראשונה',
    'admin_help_create_1'   => 'לחצו על יצירת מפה חדשה, תנו לה שם ובחרו מרכז, רמת תקריב וסוג תצוגה.',
    'admin_help_create_2'   => 'שמרו את המפה ולאחר מכן הוסיפו סמנים מניהול מפות. כל סמן יכול להשתמש בכתובת או בקואורדינטות מדויקות.',
    'admin_help_create_3'   => 'סמלים ושכבות-על הם אופציונליים. התחילו במפה פשוטה ובכמה סמנים כדי לוודא שהגדרת Google Maps תקינה.',
    'admin_help_trouble'    => 'אם המפה אפורה או מציגה „For development purposes only”, בדקו את החיוב ב-Google Cloud, ממשקי API פעילים והגבלות המפתח.',
    'admin_help_official'   => 'התיעוד הרשמי של Google Maps Platform',
    'admin_help_geo_title'  => 'מה עושה „בדיקת מיקום משתמשים”?',
    'admin_help_geo_intro'  => 'פקודה זו סורקת חברים שמילאו את שדה המיקום בפרופיל ומכינה קואורדינטות למפת המשתמשים.',
    'admin_help_geo_1'      => 'מפות שולח כל מיקום טקסט שלא נפתר אל Google Geocoding API, למשל „נאנט, צרפת”.',
    'admin_help_geo_2'      => "קו הרוחב והאורך המוחזרים נשמרים במטמון בטבלת הגאוקידוד של מפות. פרופיל Geeklog של החבר אינו משתנה.",
    'admin_help_geo_3'      => 'הדבר שימושי בעיקר לאחר התקנה, הגירה, או כאשר חברים רבים הוסיפו או שינו מיקום. נדרשים Geocoding API ומפתח המורשה לגאוקידוד בצד השרת.',
    'admin_help_overlays_title' => 'למה משמשות שכבות-על?',
    'admin_help_overlays_intro' => 'שכבת-על היא תמונה עם ייחוס גאוגרפי המונחת מעל מפת Google בין קואורדינטות דרום-מערב וצפון-מזרח. היא מוסיפה מידע חזותי שאינו חלק משכבת הבסיס של Google Maps.',
    'admin_help_overlays_1' => 'הצגת תכנית של אתר, חניון, פארק, אחוזה, בניין או פסטיבל מעל המפה האמיתית.',
    'admin_help_overlays_2' => 'הנחת מפה היסטורית, קדסטרית, גאולוגית או תיירותית, או תכנית ישנה, לצורך השוואה.',
    'admin_help_overlays_3' => 'הצגת אזור נושאי כגון מסלול מאויר, אזור עבודה, שטח טבע, תחום פרויקט או מידע גרפי אחר.',
    'admin_help_overlays_4' => 'הגדירו רמות תקריב מינימלית ומקסימלית כך ששכבת-העל תוצג רק כשיש בה צורך.',
    'admin_help_overlays_how' => 'כדי ליצור שכבת-על: הכינו תמונה מתאימה, פתחו שכבות-על, הזינו גבולות דרום-מערב וצפון-מזרח וחברו אותה למפה מלשונית שכבות-על בעורך המפה. שכבות-על הן אופציונליות; למפה רגילה עם נקודות מספיקים סמנים.',
    'admin_help_concepts_title' => 'מושגי מפות בקצרה',
    'admin_help_concept_map' => 'מפה',
    'admin_help_concept_map_text' => 'המכולה הראשית: מרכז, תקריב, סוג תצוגה, מידות, הרשאות ואפשרויות כלליות.',
    'admin_help_concept_marker' => 'סמן',
    'admin_help_concept_marker_text' => 'נקודה גאוגרפית על מפה עם שם, תיאור, כתובת ומידע נוסף אופציונלי.',
    'admin_help_concept_icon' => 'סמל',
    'admin_help_concept_icon_text' => 'תמונה אופציונלית שמחליפה את הסמן הרגיל של Google כדי להבדיל בין קטגוריות נקודות.',
    'admin_help_concept_users' => 'מפת משתמשים',
    'admin_help_concept_users_text' => 'מפה שנוצרת משדה המיקום בפרופיל Geeklog. הקואורדינטות נפתרות ונשמרות במטמון על ידי מערכת הגאוקידוד של מפות.',
    'admin_help_trouble_title' => 'פתרון בעיות מהיר',
    'profile_title'         => 'מיקום גאוגרפי',
    'buy_marker'            => 'רכישת סמן',
    'menu_label'            => 'ניהול מפות',
    'admin_home'            => 'בית', // In admin menu
    'user_home'             => 'כל המפות', //In user menu
    'maps'                  => 'מפות',
    'markers'               => 'סמנים',
    'maps_label'            => 'מפות', // For user  menu
    'create_map'            => 'יצירת מפה חדשה',
    'create_marker'         => 'יצירת סמן חדש',
    'map_edit'              => 'עריכת מפה',
    'marker_edit'           => 'עריכת סמן',
    'deletion_succes'       => 'המחיקה הצליחה',
    'deletion_fail'         => 'המחיקה נכשלה',
    'error'                 => 'שגיאה',
    'save_fail'             => 'השמירה נכשלה',
    'save_success'          => 'השמירה הצליחה',
    'missing_field'         => 'חסר שדה חובה…',
    'geocoder'              => 'גאוקודר',
    'geocoder_text'         => 'הזינו כתובת ולאחר מכן גררו את הסמן כדי לכוונן את המיקום. קו הרוחב/אורך יוצגו בחלון המידע לאחר כל גאוקידוד או גרירה.',
    'geocode_failed'         => 'לא ניתן לבצע גאוקידוד לכתובת. בדקו את מפתח Google Maps API, הפעלת Geocoding API והכתובת, ונסו שוב.',
    'go'                    => 'קדימה!',
    'name_label'            => 'שם המפה: ',
    'marker_name_label'     => 'שם הסמן: ',
    'description_label'     => 'תיאור:',
    'ok_button'             => 'אישור',
    'edit_button'           => 'עריכה',
    'save_button'           => 'שמירה',
    'delete_button'         => 'מחיקה',
    'yes'                   => 'כן',
    'no'                    => 'לא',
    'required_field'        => 'מציין שדה חובה',
    'address_label'         => 'כתובת: ',
    'message'               => 'הודעה',
    'general_settings'      => 'הגדרות כלליות',
    'map_width'             => 'רוחב מפה (% או px, לפחות 550px): ',
    'map_height'             => 'גובה מפה (px בלבד, לפחות 350px): ',
    'map_zoom'              => 'תקריב מפה (0-21): ',
    'map_type'              => 'סוג מפה: ',
    'active'                => 'המפה פעילה: ',
    'hidden'                => 'המפה מוסתרת: ',
    'marker_active'         => 'הסמן פעיל: ',
    'marker_hidden'         => 'הסמן מוסתר: ',
    'free_marker'           => 'המפה מקבלת סמנים חינמיים: ',
    'paid_marker'           => 'המפה מקבלת סמנים בתשלום: ',
    'error_address_empty'   => 'הזינו תחילה כתובת תקינה.',
    'error_invalid_address' => 'הכתובת אינה תקינה. ודאו שהזנתם גם מספר רחוב ועיר.',
    'error_google_error'    => 'אירעה בעיה בעיבוד הבקשה. נסו שוב.',
    'error_no_map_info'     => 'מידע מפה אינו זמין לכתובת זו.',
    'need_directions'       => 'צריכים הוראות הגעה? הזינו כתובת:',
    'directions_title'     => 'תכנון מסלול',
    'directions_start'     => 'נקודת התחלה',
    'get_directions'        => '  קבלת הוראות הגעה  ',
    'maps_list'             => 'רשימת מפות',
    'you_can'               => 'אפשר ',
    'user_maps_list'        => 'עיון במפות שלנו',
    'markers_list'          => 'רשימת סמנים',
    'map_markers_heading'   => 'סמנים במפה זו',
    'marker_singular'      => 'סמן',
    'marker_plural'        => 'סמנים',
    'views_label'          => 'צפיות',
    'no_map'                => 'אין מפה במסד הנתונים. יש ליצור מפה כדי להוסיף סמנים.',
    'no_map_user'           => 'אין מפה פעילה במסד הנתונים.',
    'value_directions'      => 'לדוגמה: מספר, רחוב, עיר, מדינה', // No quote here please
    'id'                    => 'ID',
    'name'                  => 'שם',
    'description'           => 'תיאור',
    'active_field'          => 'פעיל',
    'hidden_field'          => 'מוסתר',
    'marker_count'          => 'סמנים',
    'status_active'         => 'פעיל',
    'status_inactive'       => 'לא פעיל',
    'status_visible'        => 'גלוי',
    'status_hidden'         => 'מוסתר',
    'title_display'         => 'הצגת עמוד המפה',
    'map_header_label'      => 'כותרת מפה אופציונלית',
    'map_footer_label'      => 'תחתית מפה אופציונלית',
    'header_footer'         => 'כותרת ותחתית',
    'informations'          => 'מידע',
    'must_belong_to'        => 'כדי לגשת למפה זו עליך להשתייך לקבוצה:',
    'private_access'        => 'גישה פרטית',
    'marker_label'          => 'סמן',
    'primary_color_label'   => 'צבע ראשי',
    'stroke_color_label'    => 'צבע קו',
    'label'                 => 'תווית',
    'label_color'           => 'צבע תווית',
    'black'                 => 'שחור',
    'white'                 => 'לבן',
    'payed'                 => 'סמן בתשלום:',
    'lat'                   => 'קו רוחב:',
    'lng'                   => 'קו אורך:',
    'ressources_tab'        => 'לשונית משאבים',
    'presentation'          => 'תצוגה',
    'ressources'            => 'משאבים',
    'presentation_tab'      => 'לשונית תצוגה',
    'empty_ressources'      => 'תוויות המשאבים ריקות. יש להגדיר לפחות אחת כדי להשתמש במשאבים. ראו הגדרות.',
    'empty_for_geo'         => 'השאירו קו רוחב ואורך ריקים אם נדרש מיקום אוטומטי לפי הכתובת שמעל.',
    'select_marker_map'     => 'בחרו את המפה שבה הסמן יופיע.',
    'remark'                => 'הערות',
    'marker_created'        => 'הסמן נוצר בתאריך:',
    'map_created'           => 'המפה נוצרה בתאריך:',
    'modified'              => 'שינוי אחרון:',
    'marker_validity'       => 'שימוש בתאריך תוקף:',
    'maps_empty'            => 'צרו תחילה מפה.',
    'from'                  => 'מ:',
    'to'                    => 'עד:',
    'date_issue'            => 'סיום התוקף קודם להתחלה. בדקו את הנתונים.',
    'max_char'              => 'תווים לכל היותר.',
    'street_label'          => 'רחוב:',
    'code_label'            => 'מיקוד:',
    'city_label'            => 'עיר:',
    'state_label'           => 'מדינה/אזור:',
    'country_label'         => 'מדינה:',
    'tel_label'             => 'טלפון:',
    'fax_label'             => 'איש קשר נוסף:',
    'web_label'             => 'אתר:',
    'not_use_see_config'    => 'לא בשימוש. ראו הגדרות',
    //global maps
    'global_map'            => 'מפה גלובלית',
    'info_global_map'       => 'כל המפות במפה אחת.',
    'users_map'             => 'מפת משתמשי האתר',
    'info_users_map'        => 'זו מפת משתמשי האתר. אפשר להוסיף את עצמכם על ידי הגדרת המיקום בפרופיל.',
    //Submission
    'address'               => 'כתובת',
    'created'               => 'תאריך',
    'submit_marker'         => 'שליחת סמן',
    'submit_marker_text'    => '<p><ol><li>הגדירו את מיקום הסמן<li>מלאו את כל השדות<li>אשרו</ol></p>',
    'markers_submissions'   => 'הגשות סמנים',
    'submission_disabled'   => 'תור ההגשות לסמנים מושבת',
    'go'                    => 'הצגת כתובת זו',
    //date and hits
    'last_modification'     => 'שינוי אחרון:',
    'hits'                  => 'כניסות',
    //user marker
    'member'                => 'חבר',
    'location'              => 'מיקום: ',
    'regdate'               => 'חבר מאז: ',
    'about'                 => 'אודות',
    'my_markers'            => 'הסמנים שלי',
    'payed_label'           => 'בתשלום',
    'from_label'            => 'תוקף מ',
    'to_label'              => 'תוקף עד',
    'no_marker'             => 'אין לך סמנים או שהם עדיין לא אושרו. אם זו טעות, אפשר לפנות למנהל האתר.',
    'marker_detail'         => 'פרטי סמן',
    'admin_can'             => 'כמנהל מפות אפשר',
    'create_map'            => 'יצירת מפה חדשה',
    'set_user_geo'          => 'הגדרת מיקום משתמשים',
    'set_geo_location'      => 'המערכת תבדוק ותגדיר את כל המיקומים.',
    'records'               => 'רשומות',
    'report'                => 'דיווח על סמן זה',
    'report_subject'        => 'דיווח על סמן ',
    'edit_marker_text'      => '<p><ol><li>הגדירו את מיקום הסמן<li>מלאו את כל שדות החובה<li>אשרו</ol></p>',
    'admin'                 => 'ניהול',
    'category_label'        => 'קטגוריה:',
    'choose_category'       => '-- בחירת קטגוריה --',
    'categories'            => 'קטגוריות',
    'categories_list'       => 'רשימת קטגוריות',
    'cat_edit'              => 'עריכת קטגוריה:',
    'cat_name_label'        => 'שם קטגוריה:',
    'create_cat'            => 'יצירת קטגוריה חדשה',
    'field_list'            => 'רשימת שדות',
    'addfield'              => 'הוספת שדה',
    'field_name'            => 'שם שדה',
    'field_order'           => 'סדר',
    'field_autotag'         => 'תג אוטומטי',
    'field_rights'          => 'הרשאות',
    'field_edit'            => 'עריכה',
    'valid'                 => 'תקין',
    'editing_field'         => 'עריכת שדה',
    'category'              => 'קטגוריה',
    'map_label'             => 'מפה',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => 'הצגת מפה',
    'view_markers'          => 'הצגת רשימת סמנים',
    'code'                  => 'מיקוד',
    'city'                  => 'עיר',
    'viewing_markers'       => 'הצגת רשימת הסמנים',
    'details'               => 'פרטים',
    'view_details'          => 'הצגת פרטים',
    'print'                 => 'הדפסה',
	'to_complete'           => 'להשלמה',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - מציג את המפה עם id=XX. האפשרויות הן רמת תקריב (0 עד 21) ומרכז המפה לפי location.',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - מציג מפה ממורכזת לפי שם מקום או כתובת. פרמטרים אופציונליים: zoom, width ו-height. התחביר ההיסטורי [geo: map ...] עדיין נתמך.',
	'autotag_desc_marker'   => '[marker: xx] - מציג את הסמן עם id=XX',
	//v1.1
	'marker_customisation'  => 'התאמת סמן',
	'mk_default'            => 'שימוש בסמן ברירת מחדל',
	'overlays'              => 'שכבות-על',
	'overlays_list'         => 'רשימת שכבות-על',
	'create_overlay'        => 'יצירת שכבת-על חדשה',
	'edit_overlay_text'     => 'עריכת שכבת-על:',
	'overlay_edit'          => 'עריכת שכבת-על',
	'overlay_name_label'    => 'שם שכבת-על:',
	'overlay_presentation'  => 'שכבות-על הן אובייקטים במפה המקושרים לקואורדינטות קו רוחב/אורך, ולכן נעים כאשר גוררים או מקרבים את המפה. הן מייצגות אובייקטים שנוספו לציון נקודות, קווים או אזורים. כאן ניתן להוסיף תמונה כשכבת-על.',
	'overlay_active'        => 'שכבת-על זו פעילה:',
	'zoom_min_label'        => 'תקריב מינימלי:',
	'zoom_max_label'        => 'תקריב מקסימלי:',
	'image_message'         => 'בחרו תמונה מהכונן.',
	'image_replace'         => 'העלאת תמונה חדשה תחליף את זו:',
	'image'                 => 'תמונה',
	'sw_lat'                => 'קו רוחב דרום-מערב:',
    'sw_lng'                => 'קו אורך דרום-מערב:',
	'ne_lat'                => 'קו רוחב צפון-מזרח:',
    'ne_lng'                => 'קו אורך צפון-מזרח:',
	'overlay_not_writable'  => 'תיקיית שכבות-העל אינה ניתנת לכתיבה. צרו אותה ואפשרו כתיבה לפני השימוש בתכונה.',
	'map_tab'               => 'מפה',
	'overlays_tab'          => 'שכבות-על',
	'add_overlay'           => 'הוספת שכבת-על',
	'remove_overlay'        => 'הסרת שכבת-על',
	'overlay_label'         => 'שכבת-על',
	'import_export'         => 'ייבוא/ייצוא',
	'import'                => 'ייבוא',
	'export'                => 'ייצוא',
	'select_file'           => 'בחירת קובץ .csv',
	'import_message'        => 'בחרו את המפה שאליה יתווספו סמנים, את קובץ ה-CSV מהכונן, את מפריד הנתונים ואת השדות לייבוא.',
	'markers_added'         => 'סמנים שנוספו למפה:',
	'export_message'        => 'בחרו את המפה שממנה ייוצאו הסמנים, את מפריד הנתונים ואת השדות לייצוא.',
	'no_marker_to_export'   => 'אין סמנים לייצוא מהמפה הזו.',
	'icons'                 => 'סמלים',
	'icons_not_writable'    => 'תיקיית הסמלים אינה ניתנת לכתיבה. צרו אותה ואפשרו כתיבה לפני השימוש בתכונה.',
	'icons_list'            => 'רשימת סמלים',
	'create_icon'           => 'יצירת סמל חדש',
	'icon_edit'             => 'עריכת סמל',
	'icon_presentation'     => 'כאן ניתן להעלות סמל חדש לשימוש עם סמנים', 
	'icon_name_label'       => 'שם סמל',
	'xmarkers'              => 'סמנים',
	'1marker'               => 'סמן',
	'choose_icon'           => 'ניתן לבחור סמל עבור סמן זה. סמלי העדיפות מופיעים מעל הצבעים.',
	'no_icon'               => 'ללא סמל',
	'no_custom_icons'        => 'עדיין לא נרשם סמל מותאם.',
	'manage_icons'           => 'ניהול סמלים',
	'separator'             => 'בחירת מפריד',
	'markers_to_add'        => 'בדקו את כל זוגות השדה/ערך ואשרו שברצונכם להוסיף את כל הסמנים הבאים למפה:',
	'choose_fields_import'  => 'בחירת שדות לייבוא',
	'choose_fields_export'  => 'בחירת שדות לייצוא',
	'checkall'              => 'בחירת הכול',
    'import_step_1' => 'הכנת הייבוא',
    'import_step_1_text' => 'בחרו מפת יעד, קובץ CSV, מפריד וסדר עמודות.',
    'import_step_2' => 'בדיקת הנתונים',
    'import_step_2_text' => 'מפות מאמת, מנרמל ומבצע גאוקידוד לשורות לפני כתיבת נתונים.',
    'import_step_3' => 'אישור הייבוא',
    'import_step_3_text' => 'בדקו יעד, בעלים והרשאות ולאחר מכן אשרו את האצווה.',
    'import_minimum' => 'שדות מינימליים',
    'import_minimum_help' => 'name + address, או name + lat + lng. הקביעה המינימלית משתמשת באפשרות מבוססת כתובת.',
    'import_recommended' => 'שדות מומלצים',
    'import_recommended_help' => 'name, address, lat, lng, description, street, code, city, state, country, tel ו-web.',
    'import_order_help' => 'עמודות CSV חייבות להיות באותו סדר כמו השדות שנבחרו למטה.',
    'import_select_minimum' => 'שדות מינימליים',
    'import_select_recommended' => 'שדות מומלצים',
    'import_clear_fields' => 'ניקוי בחירה',
    'import_preview_title' => 'בדיקת הנתונים',
    'import_preview_text' => 'אלה הערכים המנורמלים שייכתבו אם הייבוא יאושר.',
    'import_summary_rows' => 'שורות מוכנות',
    'import_summary_coordinates' => 'קואורדינטות סופקו',
    'import_summary_geocoded' => 'גאוקידוד אוטומטי',
    'import_summary_partial' => 'עם פרטי כתובת חלקיים',
    'import_status' => 'מצב',
    'import_status_ready' => 'מוכן',
    'import_status_partial' => 'מוכן · פרטים חלקיים',
    'import_status_geocoded' => 'בוצע גאוקידוד',
    'import_confirm_title' => 'אישור הייבוא',
    'import_confirm_text' => 'בדקו את הגדרות האצווה לפני יצירת הסמנים.',
    'import_confirm_button' => 'ייבוא %d סמנים',
    'import_cancel_button' => 'ביטול',
	'order'                 => 'סדר',
	'move'                  => 'הזזה',
	'name_missing'          => 'חסר לפחות שם אחד. בדקו את קובץ ה-CSV.',
	'need_address'          => 'נדרשת לפחות כתובת או קואורדינטות כדי ליצור סמן. בדקו את קובץ ה-CSV; חסר מידע.',
	'manage_groups'         => 'ניהול קבוצות שכבות-על',
	'create_group'          => 'יצירת קבוצת שכבות-על חדשה',
	'group_edit'            => 'עריכת קבוצת שכבות-על',
	'group_overlay_presentation' => 'כאן ניתן לבחור או לערוך את שם קבוצת שכבות-העל',
	'group_overlay_name_label'   => 'שם הקבוצה',
	'group_label'           => 'קבוצה (אופציונלי)',
	'choose_group'          => 'בחירת קבוצה',
	'group'                 => 'קבוצה',
	
	//v1.3
	'geo_fail'              => 'הכתובת שהוזנה אינה נראית תקינה',
	'on_map'                => 'על המפה',
	'read_more'             => 'מידע נוסף',
	'from_map'              => 'מפה:',
	'show_hide_overlays'    => 'הצגה / הסתרה של שכבות-על',
	'fields_presentation'   => 'ערכו קטגוריה קיימת כדי להוסיף או לערוך שדה.',
	'overlays_added'        => 'שכבות-על במפה זו',
	'overlays_to_add'       => 'שכבות-על שניתן להוסיף למפה',
	'marker_modification'   => 'שינוי סמן',
	'from_owner'            => 'נוסף על ידי:',
	'marker_limited'        => 'הגישה לסמן זה מוגבלת…',
	'events_map'            => 'מפת האירועים הקרובים',
	'info_events_map'       => '',
	'from_cal'              => 'מ',
	'to_cal'                => 'עד',
	'on_cal'                => 'פעיל',
    //v1.4
    'admin_menu_maps' => 'מפות',
    'admin_menu_markers' => 'סמנים',
    'admin_menu_icons' => 'סמלים',
    'admin_menu_overlays' => 'שכבות-על',
    'admin_menu_import_export' => 'ייבוא/ייצוא',
    'admin_menu_geocoder' => 'גאוקודר',
    'admin_menu_geolocation' => 'מיקום גאוגרפי',
    'admin_menu_configuration' => 'הגדרות',
    'section_location' => 'מיקום',
    'section_content_contact' => 'תוכן ופרטי קשר',
    'section_appearance' => 'מראה',
    'section_publication' => 'פרסום',
    'section_resources' => 'משאבים',
    'section_ownership' => 'בעלים',
    'section_permissions' => 'הרשאות',
    'delete_confirm' => 'למחוק סמן זה לצמיתות?',
    'marker_not_found' => 'הסמן לא נמצא או שאין לך הרשאה לצפות בו.',
    'delete_map_confirm' => 'למחוק מפה זו לצמיתות?',
    'technical_coordinates' => 'קואורדינטות טכניות',
    'configuration'         => 'הגדרות',
    // Maps 1.5.6 map editor
    'map_section_display' => 'תצוגה',
    'map_section_center' => 'מרכז ותקריב',
    'map_section_markers' => 'סמנים',
    'map_section_advanced' => 'אפשרויות מתקדמות',
    'map_center_search' => 'חיפוש כתובת',
    'map_center_search_button' => 'איתור',
    'map_center_use_button' => 'שימוש במרכז המוצג',
    'map_center_help' => 'חפשו כתובת, לחצו על המפה או גררו את הסמן כדי לבחור במדויק את מרכז המפה.',
    'latitude_label' => 'קו רוחב',
    'longitude_label' => 'קו אורך',
    'map_center_marker' => 'מרכז המפה',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'הודעת מערכת',
    'add_new_field'         => 'השדה החדש נוצר בהצלחה',
    'save_field'            => 'השדה נשמר בהצלחה',
    'delete_field'          => 'השדה נמחק בהצלחה'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => 'שלום מנהל,',
    'new_marker'            => 'סמן חדש ממתין לאישור.',
    'name'                  => 'שם:',
    'on_map'                => 'במפה:',
    'submissions'           => 'הגשות: ',
    'marker_submissions'    => 'הגשות סמנים',
	'marker_modification'   => 'שינוי סמן',
	'description'           => 'תיאור:',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "תודה על שליחת סמן אל {$_CONF['site_name']}. הוא הועבר לצוות לאישור.";
$PLG_maps_MESSAGE2  = "שליחת סמנים סגורה.";
$PLG_maps_MESSAGE3  = "אירעה שגיאה ולא ניתן לשמור את הסמן.";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'מפות',
    'title' => 'הגדרות מפות'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'הסתרת תפריט מפות',
    'maps_login_required'   => 'נדרשת התחברות למפות',
    'autofill_coord'        => 'מילוי אוטומטי של קואורדינטות חסרות',
    'display_geo_profile'   => 'מיקום גאוגרפי בפרופיל',
    'map_type_profile'      => 'סוג מפת פרופיל',
    'map_type_geotag'       => 'סוג מפה של תג geo',
    'show_directions_geo'   => 'הצגת הוראות הגעה בתג geo',
    'show_directions_profile' => 'הצגת הוראות הגעה בפרופיל',
    'map_width_geotag'      => 'רוחב מפה בתג geo (עם % או px)',
    'map_height_geotag'     => 'גובה מפה בתג geo (px בלבד)',
    'map_zoom_geotag'       => 'תקריב תג geo (0-21)',
    'map_width_profile'     => 'רוחב מפת פרופיל (עם % או px)',
    'map_height_profile'    => 'גובה מפת פרופיל (px בלבד)',
    'show_map'              => 'הצגת מפת Google',
    'google_api_key'        => 'מפתח API של Google Maps',
    'url_geocode'           => 'כתובת URL של שירות Google Geocoding',
    'map_width'             => 'רוחב מפות כברירת מחדל (עם % או px)',
    'map_height'            => 'גובה מפות כברירת מחדל (px בלבד)',
    'map_zoom'              => 'תקריב מפות כברירת מחדל (0-21)',
    'map_type'              => 'סוג מפה כברירת מחדל',
    'default_permissions'   => 'הרשאות ברירת מחדל',
    'map_main_header'       => 'כותרת עמוד ראשי, תג welcome',
    'map_main_footer'       => 'תחתית עמוד ראשי, גם תג welcome',
    'map_geo'               => 'יצירת מפה עם כל הפרופילים',
    'map_markers'           => 'יצירת מפה עם כל הסמנים',
    'map_active'            => 'המפה פעילה',
    'map_hidden'            => 'המפה מוסתרת',
    'free_markers'          => 'המפה מקבלת סמנים חינמיים',
    'paid_markers'          => 'המפה מקבלת סמנים בתשלום (נדרש תוסף PayPal)',
    'street'                => 'שימוש בפרטי רחוב',
    'code'                  => 'שימוש במיקוד',
    'city'                  => 'שימוש בעיר',
    'state'                 => 'שימוש במדינה/אזור',
    'country'               => 'שימוש במדינה',
    'tel'                   => 'שימוש בטלפון',
    'fax'                   => 'שימוש באיש קשר נוסף',
    'web'                   => 'שימוש באתר',
    'item_1'                => 'תווית שדה מותאם 1',
    'item_2'                => 'תווית שדה מותאם 2',
    'item_3'                => 'תווית שדה מותאם 3',
    'item_4'                => 'תווית שדה מותאם 4',
    'item_5'                => 'תווית שדה מותאם 5',
    'item_6'                => 'תווית שדה מותאם 6',
    'item_7'                => 'תווית שדה מותאם 7',
    'item_8'                => 'תווית שדה מותאם 8',
    'item_9'                => 'תווית שדה מותאם 9',
    'item_10'               => 'תווית שדה מותאם 10',
    'label_color'           => 'צבע תווית',
    'star_primary_color'    => 'צבע ראשי של כוכב',
    'star_stroke_color'     => 'צבע קו של כוכב',
    'marker_active'         => 'הסמן פעיל כברירת מחדל',
    'marker_hidden'         => 'הסמן מוסתר כברירת מחדל',
    'marker_payed'          => 'הסמן בתשלום כברירת מחדל',
    'marker_validity'       => 'תוקף סמן כברירת מחדל',
    'marker_submission'     => 'אפשר שליחת סמנים',
    'users_map'             => 'מפה פעילה של משתמשי האתר',
    'global_map' 	        => 'מפה גלובלית פעילה',
    'global_type'           => 'סוג מפה גלובלית',	
    'global_width'  	    => 'רוחב מפה גלובלית',
    'global_height' 	    => 'גובה מפה גלובלית',
    'global_zoom'           => 'תקריב מפה גלובלית (0-21)',
    'detail_zoom'           => 'תקריב פרטי סמן (0-21)',
    'submit_login_required' => 'נדרשת התחברות לשליחת סמנים',
    'marker_edition'        => 'עריכת סמן',
	'use_cluster'           => 'שימוש באשכול סמנים',
	'zoom_profile'          => 'תקריב מפה בפרופיל המשתמש (0-21)',
	'display_events_map'    => 'הצגת מפת אירועים',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'הגדרות ראשיות',
    'sg_display' => 'הגדרות תצוגה'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => 'כללי',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'מפות',
    'tab_markers' => 'סמנים',
    'tab_fields' => 'שדות סמן',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => 'כללי',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'מפות',
    'tab_markers' => 'סמנים',
    'tab_fields' => 'שדות סמן',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => 'הגדרות כלליות',
    'fs_ads'             => 'הגדרות Google Ads',
    'fs_google'          => 'הגדרות Google API',
    'fs_permissions'     => 'הרשאות ברירת מחדל',
    'fs_display'         => 'מפות',
    'fs_global_map'      => 'מפות גלובליות',
    'fs_display_profile' => 'פרופיל',
    'fs_display_geo'     => 'תג geo',
    'fs_map_default'     => 'הגדרות ברירת מחדל למפה',
    'fs_marker_default'  => 'הגדרות ברירת מחדל לסמן',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('אמת' => 1, 'שקר' => 0),
    1 => array('אמת' => TRUE, 'שקר' => FALSE),
    3 => array('כן' => 1, 'לא' => 0),
    4 => array('פעיל' => 1, 'כבוי' => 0),
    5 => array('ראש העמוד' => 1, 'מתחת למאמר המומלץ' => 2, 'תחתית העמוד' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('מיילים' => 'מייל', 'קילומטרים' => 'km'),
    12 => array('אין גישה' => 0, 'קריאה בלבד' => 2, 'קריאה וכתיבה' => 3),
	// changed in v1.3
    20 => array('מפת רחובות רגילה' => 'ROADMAP', 'תמונות לוויין' => 'SATELLITE', 'מפת שטח' => 'TERRAIN', 'שכבה שקופה של רחובות מרכזיים מעל תמונות לוויין' => 'HYBRID'),
    30 => array('לבן' => 1, 'שחור' => 0),
    31 => array('זמני' => 1, 'קבוע' => 0),
);

$LANG_MAPS_1['location_search_label'] = 'חיפוש כתובת';
$LANG_MAPS_1['location_search_help'] = 'חפשו כתובת, לחצו על המפה או גררו את הסמן כדי לכוונן את המיקום.';
$LANG_MAPS_1['use_map_click_help'] = 'לחצו על המפה כדי להזיז את הסמן.';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = 'ראשי';
$LANG_configtabs['maps']['tab_general'] = 'כללי';
$LANG_tab['maps']['tab_general'] = 'כללי';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = 'מפות';
$LANG_tab['maps']['tab_maps'] = 'מפות';
$LANG_configtabs['maps']['tab_markers'] = 'סמנים';
$LANG_tab['maps']['tab_markers'] = 'סמנים';
$LANG_configtabs['maps']['tab_fields'] = 'שדות סמן';
$LANG_tab['maps']['tab_fields'] = 'שדות סמן';

$LANG_fs['maps']['fs_main'] = 'גישה ותכונות';
$LANG_fs['maps']['fs_permissions'] = 'הרשאות ברירת מחדל';
$LANG_fs['maps']['fs_uploads'] = 'תמונות והעלאות';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = 'תצוגה כללית';
$LANG_fs['maps']['fs_global_map'] = 'מפה גלובלית ומפת משתמשים';
$LANG_fs['maps']['fs_display_profile'] = 'מפת פרופיל משתמש';
$LANG_fs['maps']['fs_display_geo'] = 'תג Geo';
$LANG_fs['maps']['fs_map_defaults'] = 'ברירות מחדל למפה חדשה';
$LANG_fs['maps']['fs_events_map'] = 'מפת אירועים';
$LANG_fs['maps']['fs_marker_defaults'] = 'ברירות מחדל לסמן';
$LANG_fs['maps']['fs_marker_editor'] = 'מפת עורך סמן';
$LANG_fs['maps']['fs_marker_detail'] = 'מפת פרטי סמן';
$LANG_fs['maps']['fs_marker_popup'] = 'חלונות מידע של סמנים';
$LANG_fs['maps']['fs_marker_fields'] = 'שדות ותוויות של סמנים';

$LANG_confignames['maps']['max_image_width'] = 'רוחב תמונה מרבי (px)';
$LANG_confignames['maps']['max_image_height'] = 'גובה תמונה מרבי (px)';
$LANG_confignames['maps']['max_image_size'] = 'גודל תמונה מרבי (בתים)';
$LANG_confignames['maps']['google_api_key'] = 'מפתח API של Google Maps לדפדפן';
$LANG_confignames['maps']['google_server_api_key'] = 'מפתח API של Google Geocoding לשרת';
$LANG_confignames['maps']['google_map_id'] = 'מזהה Google Map (הכנה ל-Advanced Markers)';
$LANG_confignames['maps']['google_language'] = 'שפת Google Maps (אופציונלי, למשל he)';
$LANG_confignames['maps']['google_region'] = 'אזור Google Maps (אופציונלי, למשל IL)';
$LANG_confignames['maps']['url_geocode'] = 'כתובת URL של שירות Google Geocoding';
$LANG_confignames['maps']['map_primary_color'] = 'צבע ראשי ברירת מחדל של המפה';
$LANG_confignames['maps']['map_stroke_color'] = 'צבע קו ברירת מחדל של המפה';
$LANG_confignames['maps']['map_label'] = 'תווית סמן ברירת מחדל של המפה';
$LANG_confignames['maps']['map_label_color'] = 'צבע תווית ברירת מחדל של המפה';
$LANG_confignames['maps']['events_map_zoom'] = 'תקריב מפת אירועים';
$LANG_confignames['maps']['events_map_height'] = 'גובה מפת אירועים';
$LANG_confignames['maps']['users_map_lat'] = 'קו רוחב מרכז מפת משתמשים (ריק = אוטומטי)';
$LANG_confignames['maps']['users_map_lng'] = 'קו אורך מרכז מפת משתמשים (ריק = אוטומטי)';
$LANG_confignames['maps']['users_map_zoom'] = 'תקריב מפת משתמשים (ריק = מפה גלובלית)';
$LANG_confignames['maps']['users_map_type'] = 'סוג מפת משתמשים (ריק = מפה גלובלית)';
$LANG_confignames['maps']['users_map_width'] = 'רוחב מפת משתמשים (ריק = מפה גלובלית)';
$LANG_confignames['maps']['users_map_height'] = 'גובה מפת משתמשים (ריק = מפה גלובלית)';
$LANG_confignames['maps']['marker_editor_type'] = 'סוג מפת עורך סמן';
$LANG_confignames['maps']['marker_editor_zoom'] = 'תקריב התחלתי בעורך סמן';
$LANG_confignames['maps']['marker_editor_width'] = 'רוחב מפת עורך סמן';
$LANG_confignames['maps']['marker_editor_height'] = 'גובה מפת עורך סמן';
$LANG_confignames['maps']['detail_width'] = 'רוחב מפת פרטי סמן';
$LANG_confignames['maps']['detail_height'] = 'גובה מפת פרטי סמן';
$LANG_confignames['maps']['detail_zoom'] = 'תקריב מפת פרטי סמן';
$LANG_confignames['maps']['popup_width'] = 'רוחב חלון מידע';
$LANG_confignames['maps']['popup_height'] = 'גובה חלון מידע';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = 'SEO של עמוד הנחיתה';
$LANG_confignames['maps']['maps_page_title'] = 'כותרת SEO לעמוד הנחיתה של מפות';
$LANG_confignames['maps']['maps_page_h1'] = 'כותרת H1 לעמוד הנחיתה של מפות';
$LANG_confignames['maps']['maps_meta_description'] = 'תיאור מטא לעמוד הנחיתה של מפות';
$LANG_confignames['maps']['map_main_header'] = 'תוכן מבוא לעמוד הנחיתה של מפות (תמיכה בתגים אוטומטיים)';

$LANG_MAPS_1['server_geocode_key_missing'] = 'חיפוש קואורדינטות בצד השרת פעיל, אך לא מוגדר מפתח API ייעודי של Google Geocoding לשרת. מפתח הדפדפן לעולם אינו משמש לגאוקידוד בצד השרת.';
$LANG_MAPS_1['api_diag_title'] = 'הגדרת Google Maps Platform';
$LANG_MAPS_1['api_diag_maps_js'] = 'Maps JavaScript API';
$LANG_MAPS_1['api_diag_geocoding'] = 'Geocoding API';
$LANG_MAPS_1['api_diag_directions'] = 'Directions API';
$LANG_MAPS_1['api_diag_browser_key'] = 'מפתח API לדפדפן';
$LANG_MAPS_1['api_diag_server_key'] = 'מפתח API לשרת';
$LANG_MAPS_1['api_diag_map_id'] = 'מזהה מפה';
$LANG_MAPS_1['api_diag_configured'] = 'המפתח מוגדר — ה-API לא אומת';
$LANG_MAPS_1['api_diag_browser_verify'] = 'המפתח מוגדר — אמתו באמצעות בדיקת הדפדפן למטה';
$LANG_MAPS_1['api_diag_referrer_hint'] = 'להגבלות HTTP referrer של מפתח הדפדפן, אשרו את האתר הזה (לדוגמה: %s/*).';
$LANG_MAPS_1['api_diag_missing'] = 'חסר';
$LANG_MAPS_1['api_diag_optional'] = 'אופציונלי / לא מוגדר';
$LANG_MAPS_1['integrations_title'] = 'שילובים';
$LANG_MAPS_1['integrations_intro'] = 'מפות משתמש ב-API ובשירותים של Geeklog כך שתוספים קשורים יכולים לגלות את מפות ללא תלות ישירה במסד הנתונים.';
$LANG_MAPS_1['integration_active'] = 'פעיל';
$LANG_MAPS_1['integration_missing'] = 'תוסף חסר';
$LANG_MAPS_1['integration_native'] = 'תמיכה מובנית';
$LANG_MAPS_1['integration_xmlsitemap'] = 'מפת אתר XML';
$LANG_MAPS_1['integration_documents'] = 'מסמכים';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'הזנות RSS / Atom';
$LANG_MAPS_1['use_my_location'] = 'שימוש במיקום שלי';
$LANG_MAPS_1['geolocation_unavailable'] = 'מיקום הדפדפן אינו זמין. הזינו כתובת התחלה ידנית.';
$LANG_MAPS_1['geolocation_denied'] = 'לא ניתן לקבל את המיקום שלך. אפשרו גישה למיקום או הזינו כתובת התחלה ידנית.';
$LANG_MAPS_1['geolocation_https'] = 'מיקום בדפדפן דורש בדרך כלל HTTPS.';
?>
