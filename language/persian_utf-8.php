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
    'plugin_name'           => 'نقشه‌ها',
    'plugin_conf'           => 'پیکربندی افزونه',
    'map'                   => 'نقشه',
    'need_google_api'       => 'هیچ کلید API مرورگر Google Maps پیکربندی نشده است. تا زمانی که این کلید افزوده نشود، نقشه‌های Google نمایش داده نمی‌شوند.',
    'api_status_title' => 'وضعیت API گوگل مپس',
    'api_status_missing' => 'هیچ کلید API مرورگر Google Maps پیکربندی نشده است.',
    'api_status_testing' => 'در حال آزمایش Google Maps JavaScript API…',
    'api_status_key' => 'کلید مرورگر',
    'api_status_ok' => 'Google Maps JavaScript API با موفقیت بارگذاری شد: کلید مرورگر برای این صفحه پذیرفته شده است.',
    'api_status_auth' => 'Google Maps کلید مرورگر یا پیکربندی آن را رد کرد. کد دقیق خطای Google را در کنسول مرورگر بررسی کنید، سپس محدودیت‌های HTTP referrer، APIهای فعال و صورتحساب Google Cloud را بررسی کنید.',
    'api_status_load' => 'اسکریپت Google Maps JavaScript API بارگذاری نشد. شبکه، سیاست CSP، مسدودکننده‌های محتوا و کنسول مرورگر را بررسی کنید.',
    'api_status_timeout' => 'Google Maps JavaScript API پاسخ نداد. کنسول مرورگر و پنل Network را بررسی کنید.',
    'admin_help_title'      => 'شروع کار با نقشه‌ها',
    'admin_help_intro'      => 'نقشه‌ها به شما امکان می‌دهد چند نقشه بسازید، نشانگر اضافه کنید و در صورت نیاز از آیکون‌های سفارشی یا لایه‌های همپوشان استفاده کنید.',
    'admin_help_google'     => 'پیکربندی Google Maps',
    'admin_help_google_1'   => 'Google Cloud Console را باز کنید، یک پروژه بسازید یا انتخاب کنید و برای استفاده عملیاتی حساب صورتحساب متصل کنید.',
    'admin_help_google_2'   => 'حداقل Maps JavaScript API را فعال کنید. اگر تبدیل خودکار آدرس به عرض/طول جغرافیایی استفاده می‌شود، Geocoding API را نیز فعال کنید.',
    'admin_help_google_3'   => 'یک کلید API برای مرورگر بسازید. آن را به وب‌سایت‌های مجاز (HTTP referrers)، مانند https://www.example.com/* محدود کنید، سپس کلید را فقط به Maps JavaScript API محدود کنید.',
    'admin_help_google_4'   => 'برای ژئوکدینگ سمت سرور، ساخت کلید دوم توصیه می‌شود. آن را به IP سرور و فقط Geocoding API محدود کنید.',
    'admin_help_google_5'   => 'کلید مرورگر را در Google Maps API key و در صورت استفاده، کلید سرور را در Google Maps server API key در پیکربندی نقشه‌های Geeklog وارد کنید.',
    'admin_help_security'   => 'در محیط عملیاتی هرگز کلید Google Maps را بدون محدودیت رها نکنید. جدا کردن کلید مرورگر و سرور خطر استفاده غیرمجاز را کاهش می‌دهد.',
    'admin_help_create'     => 'اولین نقشه را بسازید',
    'admin_help_create_1'   => 'روی «ساخت نقشه جدید» کلیک کنید، نامی بدهید و مرکز، سطح بزرگ‌نمایی و نوع نمایش را انتخاب کنید.',
    'admin_help_create_2'   => 'نقشه را ذخیره کنید، سپس از مدیریت نقشه‌ها نشانگر اضافه کنید. هر نشانگر می‌تواند از آدرس یا مختصات دقیق استفاده کند.',
    'admin_help_create_3'   => 'آیکون‌ها و لایه‌های همپوشان اختیاری هستند. با یک نقشه ساده و چند نشانگر شروع کنید تا پیکربندی Google Maps را بررسی کنید.',
    'admin_help_trouble'    => 'اگر نقشه خاکستری است یا «For development purposes only» نشان می‌دهد، صورتحساب Google Cloud، APIهای فعال و محدودیت‌های کلید API را بررسی کنید.',
    'admin_help_official'   => 'مستندات رسمی Google Maps Platform',
    'admin_help_geo_title'  => '«بررسی موقعیت جغرافیایی کاربران» چه کاری انجام می‌دهد؟',
    'admin_help_geo_intro'  => 'این فرمان اعضایی را که فیلد Location را در پروفایل پر کرده‌اند بررسی و مختصات نقشه کاربران را آماده می‌کند.',
    'admin_help_geo_1'      => 'نقشه‌ها هر موقعیت متنی حل‌نشده را به Google Geocoding API می‌فرستد، برای نمونه «نانت، فرانسه».',
    'admin_help_geo_2'      => "عرض و طول جغرافیایی بازگشتی در جدول ژئوکدینگ نقشه‌ها کش می‌شوند. پروفایل Geeklog عضو تغییر نمی‌کند.",
    'admin_help_geo_3'      => 'این قابلیت بیشتر پس از نصب، مهاجرت یا تغییر موقعیت تعداد زیادی از اعضا مفید است. به Geocoding API و کلید مجاز برای ژئوکدینگ سمت سرور نیاز دارد.',
    'admin_help_overlays_title' => 'لایه‌های همپوشان چه کاربردی دارند؟',
    'admin_help_overlays_intro' => 'لایه همپوشان یک تصویر زمین‌مرجع است که روی نقشه Google بین مختصات جنوب‌غربی و شمال‌شرقی قرار می‌گیرد و اطلاعات بصری خارج از لایه پایه Google Maps را اضافه می‌کند.',
    'admin_help_overlays_1' => 'نمایش طرح یک مکان، کمپ، پارک، ملک، ساختمان یا جشنواره روی نقشه واقعی.',
    'admin_help_overlays_2' => 'همپوشانی نقشه تاریخی، ثبتی، زمین‌شناسی یا گردشگری، یا یک نقشه قدیمی برای مقایسه.',
    'admin_help_overlays_3' => 'نمایش یک ناحیه موضوعی مانند مسیر تصویری، محدوده کاری، منطقه طبیعی، محدوده پروژه یا اطلاعات گرافیکی دیگر.',
    'admin_help_overlays_4' => 'حداقل و حداکثر بزرگ‌نمایی را تنظیم کنید تا لایه فقط زمانی نمایش داده شود که مفید است.',
    'admin_help_overlays_how' => 'برای ساخت لایه: تصویر مناسبی آماده کنید، بخش لایه‌ها را باز کنید، مرز جنوب‌غربی و شمال‌شرقی را وارد کنید و از زبانه لایه‌ها در ویرایشگر نقشه آن را به نقشه متصل کنید. لایه‌ها اختیاری‌اند؛ برای نقشه عادی با نقاط، نشانگرها کافی هستند.',
    'admin_help_concepts_title' => 'مفاهیم نقشه‌ها در یک نگاه',
    'admin_help_concept_map' => 'نقشه',
    'admin_help_concept_map_text' => 'محفظه اصلی: مرکز، بزرگ‌نمایی، نوع نمایش، ابعاد، مجوزها و گزینه‌های عمومی.',
    'admin_help_concept_marker' => 'نشانگر',
    'admin_help_concept_marker_text' => 'یک نقطه جغرافیایی روی نقشه با نام، توضیح، آدرس و اطلاعات اضافی اختیاری.',
    'admin_help_concept_icon' => 'آیکون',
    'admin_help_concept_icon_text' => 'تصویری اختیاری که جای نشانگر استاندارد Google را می‌گیرد تا دسته‌های نقاط متمایز شوند.',
    'admin_help_concept_users' => 'نقشه کاربران',
    'admin_help_concept_users_text' => 'نقشه‌ای که از فیلد Location پروفایل Geeklog ساخته می‌شود. مختصات توسط سامانه ژئوکدینگ نقشه‌ها حل و کش می‌شوند.',
    'admin_help_trouble_title' => 'عیب‌یابی سریع',
    'profile_title'         => 'موقعیت جغرافیایی',
    'buy_marker'            => 'خرید نشانگر',
    'menu_label'            => 'مدیریت نقشه‌ها',
    'admin_home'            => 'خانه', // In admin menu
    'user_home'             => 'همه نقشه‌ها', //In user menu
    'maps'                  => 'نقشه‌ها',
    'markers'               => 'نشانگرها',
    'maps_label'            => 'نقشه‌ها', // For user  menu
    'create_map'            => 'ساخت نقشه جدید',
    'create_marker'         => 'ساخت نشانگر جدید',
    'map_edit'              => 'ویرایش نقشه',
    'marker_edit'           => 'ویرایش نشانگر',
    'deletion_succes'       => 'حذف موفق بود',
    'deletion_fail'         => 'حذف ناموفق بود',
    'error'                 => 'خطا',
    'save_fail'             => 'ذخیره ناموفق بود',
    'save_success'          => 'ذخیره شد',
    'missing_field'         => 'فیلد الزامی وارد نشده…',
    'geocoder'              => 'ژئوکدر',
    'geocoder_text'         => 'یک آدرس وارد کنید و سپس نشانگر را برای تنظیم موقعیت بکشید. عرض/طول جغرافیایی پس از هر ژئوکد یا جابه‌جایی در پنجره اطلاعات نمایش داده می‌شود.',
    'geocode_failed'         => 'آدرس ژئوکد نشد. کلید Google Maps API، فعال‌بودن Geocoding API و آدرس را بررسی کنید و دوباره تلاش کنید.',
    'go'                    => 'برو!',
    'name_label'            => 'نام نقشه: ',
    'marker_name_label'     => 'نام نشانگر: ',
    'description_label'     => 'توضیح:',
    'ok_button'             => 'تأیید',
    'edit_button'           => 'ویرایش',
    'save_button'           => 'ذخیره',
    'delete_button'         => 'حذف',
    'yes'                   => 'بله',
    'no'                    => 'خیر',
    'required_field'        => 'نشان‌دهنده فیلد الزامی',
    'address_label'         => 'آدرس: ',
    'message'               => 'پیام',
    'general_settings'      => 'تنظیمات عمومی',
    'map_width'             => 'عرض نقشه (% یا px، حداقل 550px): ',
    'map_height'             => 'ارتفاع نقشه (فقط px، حداقل 350px): ',
    'map_zoom'              => 'بزرگ‌نمایی نقشه (0-21): ',
    'map_type'              => 'نوع نقشه: ',
    'active'                => 'نقشه فعال است: ',
    'hidden'                => 'نقشه پنهان است: ',
    'marker_active'         => 'نشانگر فعال است: ',
    'marker_hidden'         => 'نشانگر پنهان است: ',
    'free_marker'           => 'نقشه نشانگر رایگان می‌پذیرد: ',
    'paid_marker'           => 'نقشه نشانگر پولی می‌پذیرد: ',
    'error_address_empty'   => 'ابتدا آدرس معتبر وارد کنید.',
    'error_invalid_address' => 'این آدرس نامعتبر است. شماره ساختمان و شهر را نیز وارد کنید.',
    'error_google_error'    => 'در پردازش درخواست مشکلی رخ داد، دوباره تلاش کنید.',
    'error_no_map_info'     => 'اطلاعات نقشه برای این آدرس موجود نیست.',
    'need_directions'       => 'مسیر می‌خواهید؟ آدرس را وارد کنید:',
    'directions_title'     => 'برنامه‌ریزی مسیر',
    'directions_start'     => 'نقطه شروع',
    'get_directions'        => '  دریافت مسیر  ',
    'maps_list'             => 'فهرست نقشه‌ها',
    'you_can'               => 'می‌توانید ',
    'user_maps_list'        => 'نقشه‌های ما را ببینید',
    'markers_list'          => 'فهرست نشانگرها',
    'map_markers_heading'   => 'نشانگرهای این نقشه',
    'marker_singular'      => 'نشانگر',
    'marker_plural'        => 'نشانگرها',
    'views_label'          => 'بازدید',
    'no_map'                => 'هیچ نقشه‌ای در پایگاه داده نیست. برای افزودن نشانگر باید یک نقشه بسازید.',
    'no_map_user'           => 'هیچ نقشه فعالی در پایگاه داده نیست.',
    'value_directions'      => 'مثلاً شماره، نام خیابان، شهر، کشور', // No quote here please
    'id'                    => 'ID',
    'name'                  => 'نام',
    'description'           => 'توضیح',
    'active_field'          => 'فعال',
    'hidden_field'          => 'پنهان',
    'marker_count'          => 'نشانگرها',
    'status_active'         => 'فعال',
    'status_inactive'       => 'غیرفعال',
    'status_visible'        => 'قابل‌مشاهده',
    'status_hidden'         => 'پنهان',
    'title_display'         => 'نمایش صفحه نقشه',
    'map_header_label'      => 'سربرگ اختیاری نقشه',
    'map_footer_label'      => 'پابرگ اختیاری نقشه',
    'header_footer'         => 'سربرگ و پابرگ',
    'informations'          => 'اطلاعات',
    'must_belong_to'        => 'برای دسترسی به این نقشه باید عضو گروه زیر باشید:',
    'private_access'        => 'دسترسی خصوصی',
    'marker_label'          => 'نشانگر',
    'primary_color_label'   => 'رنگ اصلی',
    'stroke_color_label'    => 'رنگ حاشیه',
    'label'                 => 'برچسب',
    'label_color'           => 'رنگ برچسب',
    'black'                 => 'سیاه',
    'white'                 => 'سفید',
    'payed'                 => 'نشانگر پولی:',
    'lat'                   => 'عرض جغرافیایی:',
    'lng'                   => 'طول جغرافیایی:',
    'ressources_tab'        => 'زبانه منابع',
    'presentation'          => 'ارائه',
    'ressources'            => 'منابع',
    'presentation_tab'      => 'زبانه ارائه',
    'empty_ressources'      => 'برچسب منابع خالی است. برای استفاده از منابع حداقل یکی را تنظیم کنید. تنظیمات را ببینید.',
    'empty_for_geo'         => 'اگر موقعیت‌یابی خودکار از آدرس بالا لازم است، عرض و طول جغرافیایی را خالی بگذارید.',
    'select_marker_map'     => 'نقشه‌ای را انتخاب کنید که نشانگر باید روی آن ظاهر شود.',
    'remark'                => 'یادداشت‌ها',
    'marker_created'        => 'نشانگر ایجاد شد:',
    'map_created'           => 'نقشه ایجاد شد:',
    'modified'              => 'آخرین تغییر:',
    'marker_validity'       => 'استفاده از تاریخ اعتبار:',
    'maps_empty'            => 'ابتدا یک نقشه بسازید.',
    'from'                  => 'از:',
    'to'                    => 'تا:',
    'date_issue'            => 'پایان اعتبار پیش از شروع است. اطلاعات را بررسی کنید.',
    'max_char'              => 'حداکثر نویسه.',
    'street_label'          => 'خیابان:',
    'code_label'            => 'کد پستی:',
    'city_label'            => 'شهر:',
    'state_label'           => 'استان/منطقه:',
    'country_label'         => 'کشور:',
    'tel_label'             => 'تلفن:',
    'fax_label'             => 'راه تماس اضافی:',
    'web_label'             => 'وب:',
    'not_use_see_config'    => 'استفاده نشود. تنظیمات را ببینید',
    //global maps
    'global_map'            => 'نقشه سراسری',
    'info_global_map'       => 'همه نقشه‌ها در یک نقشه.',
    'users_map'             => 'نقشه کاربران سایت',
    'info_users_map'        => 'این نقشه کاربران سایت است. با تنظیم موقعیت در پروفایل می‌توانید خود را اضافه کنید.',
    //Submission
    'address'               => 'آدرس',
    'created'               => 'تاریخ',
    'submit_marker'         => 'ارسال نشانگر',
    'submit_marker_text'    => '<p><ol><li>موقعیت نشانگر را تعیین کنید<li>همه فیلدها را پر کنید<li>تأیید کنید</ol></p>',
    'markers_submissions'   => 'ارسال‌های نشانگر',
    'submission_disabled'   => 'صف ارسال نشانگر غیرفعال است',
    'go'                    => 'نمایش این آدرس',
    //date and hits
    'last_modification'     => 'آخرین تغییر:',
    'hits'                  => 'بازدید',
    //user marker
    'member'                => 'عضو',
    'location'              => 'موقعیت: ',
    'regdate'               => 'عضو از: ',
    'about'                 => 'درباره',
    'my_markers'            => 'نشانگرهای من',
    'payed_label'           => 'پولی',
    'from_label'            => 'اعتبار از',
    'to_label'              => 'اعتبار تا',
    'no_marker'             => 'شما نشانگری ندارید یا هنوز تأیید نشده‌اند. اگر فکر می‌کنید خطاست با مدیر سایت تماس بگیرید.',
    'marker_detail'         => 'جزئیات نشانگر',
    'admin_can'             => 'به‌عنوان مدیر نقشه می‌توانید',
    'create_map'            => 'ساخت نقشه جدید',
    'set_user_geo'          => 'تنظیم موقعیت کاربران',
    'set_geo_location'      => 'سامانه همه موقعیت‌ها را بررسی و تنظیم می‌کند.',
    'records'               => 'رکورد',
    'report'                => 'گزارش این نشانگر',
    'report_subject'        => 'گزارش درباره نشانگر ',
    'edit_marker_text'      => '<p><ol><li>موقعیت نشانگر را تعیین کنید<li>همه فیلدهای الزامی را پر کنید<li>سپس تأیید کنید</ol></p>',
    'admin'                 => 'مدیریت',
    'category_label'        => 'دسته:',
    'choose_category'       => '-- انتخاب دسته --',
    'categories'            => 'دسته‌ها',
    'categories_list'       => 'فهرست دسته‌ها',
    'cat_edit'              => 'ویرایش دسته:',
    'cat_name_label'        => 'نام دسته:',
    'create_cat'            => 'ساخت دسته جدید',
    'field_list'            => 'فهرست فیلدها',
    'addfield'              => 'افزودن فیلد',
    'field_name'            => 'نام فیلد',
    'field_order'           => 'ترتیب',
    'field_autotag'         => 'برچسب خودکار',
    'field_rights'          => 'مجوزها',
    'field_edit'            => 'ویرایش',
    'valid'                 => 'معتبر',
    'editing_field'         => 'ویرایش فیلد',
    'category'              => 'دسته',
    'map_label'             => 'نقشه',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => 'نمایش نقشه',
    'view_markers'          => 'نمایش فهرست نشانگرها',
    'code'                  => 'کد پستی',
    'city'                  => 'شهر',
    'viewing_markers'       => 'نمایش فهرست نشانگرها',
    'details'               => 'جزئیات',
    'view_details'          => 'نمایش جزئیات',
    'print'                 => 'چاپ',
	'to_complete'           => 'برای تکمیل',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - نقشه با id=XX را نمایش می‌دهد. گزینه‌ها شامل سطح zoom (بین 0 تا 21) و مرکز کردن روی location هستند.',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - نقشه‌ای را بر نام مکان یا آدرس مرکز می‌کند. پارامترهای اختیاری: zoom، width و height. نحو تاریخی [geo: map ...] همچنان پشتیبانی می‌شود.',
	'autotag_desc_marker'   => '[marker: xx] - نشانگر با id=XX را نمایش می‌دهد',
	//v1.1
	'marker_customisation'  => 'سفارشی‌سازی نشانگر',
	'mk_default'            => 'استفاده از نشانگر پیش‌فرض',
	'overlays'              => 'لایه‌های همپوشان',
	'overlays_list'         => 'فهرست لایه‌ها',
	'create_overlay'        => 'ساخت لایه جدید',
	'edit_overlay_text'     => 'ویرایش لایه:',
	'overlay_edit'          => 'ویرایش لایه',
	'overlay_name_label'    => 'نام لایه:',
	'overlay_presentation'  => 'لایه‌های همپوشان اشیایی روی نقشه‌اند که به مختصات عرض/طول جغرافیایی متصل‌اند و با جابه‌جایی یا بزرگ‌نمایی نقشه حرکت می‌کنند. اینجا می‌توانید یک تصویر را به‌عنوان لایه اضافه کنید.',
	'overlay_active'        => 'این لایه فعال است:',
	'zoom_min_label'        => 'حداقل بزرگ‌نمایی:',
	'zoom_max_label'        => 'حداکثر بزرگ‌نمایی:',
	'image_message'         => 'یک تصویر از دیسک انتخاب کنید.',
	'image_replace'         => 'بارگذاری تصویر جدید، این تصویر را جایگزین می‌کند:',
	'image'                 => 'تصویر',
	'sw_lat'                => 'عرض جنوب‌غربی:',
    'sw_lng'                => 'طول جنوب‌غربی:',
	'ne_lat'                => 'عرض شمال‌شرقی:',
    'ne_lng'                => 'طول شمال‌شرقی:',
	'overlay_not_writable'  => 'پوشه لایه‌ها قابل نوشتن نیست. ابتدا پوشه را بسازید و مجوز نوشتن بدهید.',
	'map_tab'               => 'نقشه',
	'overlays_tab'          => 'لایه‌های همپوشان',
	'add_overlay'           => 'افزودن لایه',
	'remove_overlay'        => 'حذف لایه',
	'overlay_label'         => 'لایه',
	'import_export'         => 'درون‌ریزی/برون‌ریزی',
	'import'                => 'درون‌ریزی',
	'export'                => 'برون‌ریزی',
	'select_file'           => 'انتخاب فایل .csv',
	'import_message'        => 'نقشه مقصد، فایل CSV روی دیسک، جداکننده داده و فیلدهای موردنظر برای درون‌ریزی را انتخاب کنید.',
	'markers_added'         => 'نشانگرهای افزوده‌شده به نقشه:',
	'export_message'        => 'نقشه موردنظر برای برون‌ریزی نشانگرها، جداکننده و فیلدها را انتخاب کنید.',
	'no_marker_to_export'   => 'نشانگری برای برون‌ریزی از این نقشه وجود ندارد.',
	'icons'                 => 'آیکون‌ها',
	'icons_not_writable'    => 'پوشه آیکون‌ها قابل نوشتن نیست. ابتدا پوشه را بسازید و مجوز نوشتن بدهید.',
	'icons_list'            => 'فهرست آیکون‌ها',
	'create_icon'           => 'ساخت آیکون جدید',
	'icon_edit'             => 'ویرایش آیکون',
	'icon_presentation'     => 'اینجا می‌توانید آیکون جدید برای نشانگرها بارگذاری کنید', 
	'icon_name_label'       => 'نام آیکون',
	'xmarkers'              => 'نشانگرها',
	'1marker'               => 'نشانگر',
	'choose_icon'           => 'می‌توانید برای این نشانگر آیکون انتخاب کنید. آیکون‌های اولویت‌دار بالاتر از رنگ‌ها هستند.',
	'no_icon'               => 'بدون آیکون',
	'no_custom_icons'        => 'هنوز آیکون سفارشی ثبت نشده است.',
	'manage_icons'           => 'مدیریت آیکون‌ها',
	'separator'             => 'انتخاب جداکننده',
	'markers_to_add'        => 'همه جفت‌های فیلد/مقدار را بررسی و افزودن همه نشانگرهای زیر را تأیید کنید:',
	'choose_fields_import'  => 'انتخاب فیلدهای درون‌ریزی',
	'choose_fields_export'  => 'انتخاب فیلدهای برون‌ریزی',
	'checkall'              => 'انتخاب همه',
    'import_step_1' => 'آماده‌سازی درون‌ریزی',
    'import_step_1_text' => 'نقشه مقصد، فایل CSV، جداکننده و ترتیب ستون‌ها را انتخاب کنید.',
    'import_step_2' => 'بررسی داده‌ها',
    'import_step_2_text' => 'نقشه‌ها پیش از نوشتن داده، سطرها را اعتبارسنجی، نرمال و ژئوکد می‌کند.',
    'import_step_3' => 'تأیید درون‌ریزی',
    'import_step_3_text' => 'مقصد، مالک و مجوزها را بررسی و سپس دسته را تأیید کنید.',
    'import_minimum' => 'حداقل فیلدها',
    'import_minimum_help' => 'name + address یا name + lat + lng. تنظیم حداقل از روش مبتنی بر آدرس استفاده می‌کند.',
    'import_recommended' => 'فیلدهای پیشنهادی',
    'import_recommended_help' => 'name، address، lat، lng، description، street، code، city، state، country، tel و web.',
    'import_order_help' => 'ستون‌های CSV باید همان ترتیب فیلدهای انتخاب‌شده زیر را داشته باشند.',
    'import_select_minimum' => 'حداقل فیلدها',
    'import_select_recommended' => 'فیلدهای پیشنهادی',
    'import_clear_fields' => 'پاک‌کردن انتخاب',
    'import_preview_title' => 'بررسی داده‌ها',
    'import_preview_text' => 'این مقادیر نرمال‌شده پس از تأیید درون‌ریزی نوشته می‌شوند.',
    'import_summary_rows' => 'سطر آماده',
    'import_summary_coordinates' => 'مختصات ارائه‌شده',
    'import_summary_geocoded' => 'ژئوکد خودکار',
    'import_summary_partial' => 'با جزئیات ناقص آدرس',
    'import_status' => 'وضعیت',
    'import_status_ready' => 'آماده',
    'import_status_partial' => 'آماده · جزئیات ناقص',
    'import_status_geocoded' => 'ژئوکد شده',
    'import_confirm_title' => 'تأیید درون‌ریزی',
    'import_confirm_text' => 'پیش از ساخت نشانگرها تنظیمات دسته را بررسی کنید.',
    'import_confirm_button' => 'درون‌ریزی %d نشانگر',
    'import_cancel_button' => 'انصراف',
	'order'                 => 'ترتیب',
	'move'                  => 'جابه‌جایی',
	'name_missing'          => 'حداقل یک نام وارد نشده است. فایل CSV را بررسی کنید.',
	'need_address'          => 'برای ساخت نشانگر حداقل آدرس یا مختصات لازم است. فایل CSV را بررسی کنید؛ اطلاعاتی کم است.',
	'manage_groups'         => 'مدیریت گروه‌های لایه',
	'create_group'          => 'ساخت گروه لایه جدید',
	'group_edit'            => 'ویرایش گروه لایه',
	'group_overlay_presentation' => 'اینجا می‌توانید نام گروه لایه را انتخاب یا ویرایش کنید',
	'group_overlay_name_label'   => 'نام گروه',
	'group_label'           => 'گروه (اختیاری)',
	'choose_group'          => 'انتخاب گروه',
	'group'                 => 'گروه',
	
	//v1.3
	'geo_fail'              => 'آدرس واردشده معتبر به نظر نمی‌رسد',
	'on_map'                => 'روی نقشه',
	'read_more'             => 'بیشتر بخوانید',
	'from_map'              => 'نقشه:',
	'show_hide_overlays'    => 'نمایش / پنهان‌کردن لایه‌ها',
	'fields_presentation'   => 'یک دسته موجود را برای افزودن یا ویرایش فیلد، ویرایش کنید.',
	'overlays_added'        => 'لایه‌های موجود روی این نقشه',
	'overlays_to_add'       => 'لایه‌هایی که می‌توانید به این نقشه اضافه کنید',
	'marker_modification'   => 'تغییر نشانگر',
	'from_owner'            => 'افزوده توسط:',
	'marker_limited'        => 'دسترسی به این نشانگر محدود است…',
	'events_map'            => 'نقشه رویدادهای آینده',
	'info_events_map'       => '',
	'from_cal'              => 'از',
	'to_cal'                => 'تا',
	'on_cal'                => 'روشن',
    //v1.4
    'admin_menu_maps' => 'نقشه‌ها',
    'admin_menu_markers' => 'نشانگرها',
    'admin_menu_icons' => 'آیکون‌ها',
    'admin_menu_overlays' => 'لایه‌های همپوشان',
    'admin_menu_import_export' => 'درون‌ریزی/برون‌ریزی',
    'admin_menu_geocoder' => 'ژئوکدر',
    'admin_menu_geolocation' => 'موقعیت جغرافیایی',
    'admin_menu_configuration' => 'پیکربندی',
    'section_location' => 'موقعیت',
    'section_content_contact' => 'محتوا و تماس',
    'section_appearance' => 'ظاهر',
    'section_publication' => 'انتشار',
    'section_resources' => 'منابع',
    'section_ownership' => 'مالک',
    'section_permissions' => 'مجوزها',
    'delete_confirm' => 'این نشانگر برای همیشه حذف شود؟',
    'marker_not_found' => 'نشانگر یافت نشد یا اجازه مشاهده آن را ندارید.',
    'delete_map_confirm' => 'این نقشه برای همیشه حذف شود؟',
    'technical_coordinates' => 'مختصات فنی',
    'configuration'         => 'پیکربندی',
    // Maps 1.5.6 map editor
    'map_section_display' => 'نمایش',
    'map_section_center' => 'مرکز و بزرگ‌نمایی',
    'map_section_markers' => 'نشانگرها',
    'map_section_advanced' => 'گزینه‌های پیشرفته',
    'map_center_search' => 'جستجوی آدرس',
    'map_center_search_button' => 'مکان‌یابی',
    'map_center_use_button' => 'استفاده از مرکز نمایش‌داده‌شده',
    'map_center_help' => 'آدرس را جستجو کنید، روی نقشه کلیک کنید یا نشانگر را بکشید تا مرکز نقشه دقیق تعیین شود.',
    'latitude_label' => 'عرض جغرافیایی',
    'longitude_label' => 'طول جغرافیایی',
    'map_center_marker' => 'مرکز نقشه',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'پیام سامانه',
    'add_new_field'         => 'فیلد جدید با موفقیت ساخته شد',
    'save_field'            => 'فیلد با موفقیت ذخیره شد',
    'delete_field'          => 'فیلد با موفقیت حذف شد'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => 'سلام مدیر،',
    'new_marker'            => 'یک نشانگر جدید منتظر تأیید است.',
    'name'                  => 'نام:',
    'on_map'                => 'روی نقشه:',
    'submissions'           => 'ارسال‌ها: ',
    'marker_submissions'    => 'ارسال نشانگرها',
	'marker_modification'   => 'تغییر نشانگر',
	'description'           => 'توضیح:',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "از ارسال نشانگر به {$_CONF['site_name']} سپاسگزاریم. برای تأیید به کارکنان ارسال شد.";
$PLG_maps_MESSAGE2  = "ارسال نشانگر بسته است.";
$PLG_maps_MESSAGE3  = "خطایی رخ داد و نشانگر ذخیره نشد.";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'نقشه‌ها',
    'title' => 'پیکربندی نقشه‌ها'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'پنهان‌کردن منوی نقشه‌ها',
    'maps_login_required'   => 'ورود برای نقشه‌ها الزامی است',
    'autofill_coord'        => 'پرکردن خودکار مختصات تعریف‌نشده',
    'display_geo_profile'   => 'موقعیت جغرافیایی پروفایل',
    'map_type_profile'      => 'نوع نقشه پروفایل',
    'map_type_geotag'       => 'نوع نقشه برچسب خودکار geo',
    'show_directions_geo'   => 'نمایش مسیر در برچسب geo',
    'show_directions_profile' => 'نمایش مسیر در پروفایل',
    'map_width_geotag'      => 'عرض نقشه برچسب geo (با % یا px)',
    'map_height_geotag'     => 'ارتفاع نقشه برچسب geo (فقط px)',
    'map_zoom_geotag'       => 'بزرگ‌نمایی برچسب geo (0-21)',
    'map_width_profile'     => 'عرض نقشه پروفایل (با % یا px)',
    'map_height_profile'    => 'ارتفاع نقشه پروفایل (فقط px)',
    'show_map'              => 'نمایش نقشه Google',
    'google_api_key'        => 'کلید API گوگل مپس',
    'url_geocode'           => 'URL سرویس Google Geocoding',
    'map_width'             => 'عرض پیش‌فرض نقشه‌ها (با % یا px)',
    'map_height'            => 'ارتفاع پیش‌فرض نقشه‌ها (فقط px)',
    'map_zoom'              => 'بزرگ‌نمایی پیش‌فرض نقشه‌ها (0-21)',
    'map_type'              => 'نوع پیش‌فرض نقشه',
    'default_permissions'   => 'مجوزهای پیش‌فرض',
    'map_main_header'       => 'سربرگ صفحه اصلی، برچسب welcome',
    'map_main_footer'       => 'پابرگ صفحه اصلی، همچنین برچسب welcome',
    'map_geo'               => 'ساخت نقشه با همه پروفایل‌ها',
    'map_markers'           => 'ساخت نقشه با همه نشانگرها',
    'map_active'            => 'نقشه فعال است',
    'map_hidden'            => 'نقشه پنهان است',
    'free_markers'          => 'نقشه نشانگر رایگان می‌پذیرد',
    'paid_markers'          => 'نقشه نشانگر پولی می‌پذیرد (افزونه PayPal لازم است)',
    'street'                => 'استفاده از اطلاعات خیابان',
    'code'                  => 'استفاده از کد پستی',
    'city'                  => 'استفاده از شهر',
    'state'                 => 'استفاده از استان/منطقه',
    'country'               => 'استفاده از کشور',
    'tel'                   => 'استفاده از تلفن',
    'fax'                   => 'استفاده از تماس اضافی',
    'web'                   => 'استفاده از وب',
    'item_1'                => 'برچسب فیلد سفارشی 1',
    'item_2'                => 'برچسب فیلد سفارشی 2',
    'item_3'                => 'برچسب فیلد سفارشی 3',
    'item_4'                => 'برچسب فیلد سفارشی 4',
    'item_5'                => 'برچسب فیلد سفارشی 5',
    'item_6'                => 'برچسب فیلد سفارشی 6',
    'item_7'                => 'برچسب فیلد سفارشی 7',
    'item_8'                => 'برچسب فیلد سفارشی 8',
    'item_9'                => 'برچسب فیلد سفارشی 9',
    'item_10'               => 'برچسب فیلد سفارشی 10',
    'label_color'           => 'رنگ برچسب',
    'star_primary_color'    => 'رنگ اصلی ستاره',
    'star_stroke_color'     => 'رنگ حاشیه ستاره',
    'marker_active'         => 'نشانگر به‌طور پیش‌فرض فعال است',
    'marker_hidden'         => 'نشانگر به‌طور پیش‌فرض پنهان است',
    'marker_payed'          => 'نشانگر به‌طور پیش‌فرض پولی است',
    'marker_validity'       => 'اعتبار پیش‌فرض نشانگر',
    'marker_submission'     => 'اجازه ارسال نشانگر',
    'users_map'             => 'نقشه فعال کاربران سایت',
    'global_map' 	        => 'نقشه سراسری فعال',
    'global_type'           => 'نوع نقشه سراسری',	
    'global_width'  	    => 'عرض نقشه سراسری',
    'global_height' 	    => 'ارتفاع نقشه سراسری',
    'global_zoom'           => 'بزرگ‌نمایی نقشه سراسری (0-21)',
    'detail_zoom'           => 'بزرگ‌نمایی جزئیات نشانگر (0-21)',
    'submit_login_required' => 'ورود برای ارسال نشانگر الزامی است',
    'marker_edition'        => 'ویرایش نشانگر',
	'use_cluster'           => 'استفاده از خوشه‌بندی نشانگرها',
	'zoom_profile'          => 'بزرگ‌نمایی نقشه در پروفایل کاربر (0-21)',
	'display_events_map'    => 'نمایش نقشه رویدادها',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'تنظیمات اصلی',
    'sg_display' => 'تنظیمات نمایش'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => 'عمومی',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'نقشه‌ها',
    'tab_markers' => 'نشانگرها',
    'tab_fields' => 'فیلدهای نشانگر',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => 'عمومی',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'نقشه‌ها',
    'tab_markers' => 'نشانگرها',
    'tab_fields' => 'فیلدهای نشانگر',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => 'تنظیمات عمومی',
    'fs_ads'             => 'تنظیمات Google Ads',
    'fs_google'          => 'تنظیمات Google API',
    'fs_permissions'     => 'مجوزهای پیش‌فرض',
    'fs_display'         => 'نقشه‌ها',
    'fs_global_map'      => 'نقشه‌های سراسری',
    'fs_display_profile' => 'پروفایل',
    'fs_display_geo'     => 'برچسب geo',
    'fs_map_default'     => 'تنظیمات پیش‌فرض نقشه',
    'fs_marker_default'  => 'تنظیمات پیش‌فرض نشانگر',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('درست' => 1, 'نادرست' => 0),
    1 => array('درست' => TRUE, 'نادرست' => FALSE),
    3 => array('بله' => 1, 'خیر' => 0),
    4 => array('روشن' => 1, 'خاموش' => 0),
    5 => array('بالای صفحه' => 1, 'زیر مقاله ویژه' => 2, 'پایین صفحه' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('مایل' => 'مایل', 'کیلومتر' => 'km'),
    12 => array('بدون دسترسی' => 0, 'فقط خواندنی' => 2, 'خواندن و نوشتن' => 3),
	// changed in v1.3
    20 => array('نقشه خیابانی معمولی' => 'ROADMAP', 'تصاویر ماهواره‌ای' => 'SATELLITE', 'نقشه عوارض' => 'TERRAIN', 'لایه شفاف خیابان‌های اصلی روی تصاویر ماهواره‌ای' => 'HYBRID'),
    30 => array('سفید' => 1, 'سیاه' => 0),
    31 => array('موقت' => 1, 'دائمی' => 0),
);

$LANG_MAPS_1['location_search_label'] = 'جستجوی آدرس';
$LANG_MAPS_1['location_search_help'] = 'آدرس را جستجو کنید، روی نقشه کلیک کنید یا نشانگر را بکشید تا موقعیت دقیق تنظیم شود.';
$LANG_MAPS_1['use_map_click_help'] = 'برای جابه‌جایی نشانگر روی نقشه کلیک کنید.';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = 'اصلی';
$LANG_configtabs['maps']['tab_general'] = 'عمومی';
$LANG_tab['maps']['tab_general'] = 'عمومی';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = 'نقشه‌ها';
$LANG_tab['maps']['tab_maps'] = 'نقشه‌ها';
$LANG_configtabs['maps']['tab_markers'] = 'نشانگرها';
$LANG_tab['maps']['tab_markers'] = 'نشانگرها';
$LANG_configtabs['maps']['tab_fields'] = 'فیلدهای نشانگر';
$LANG_tab['maps']['tab_fields'] = 'فیلدهای نشانگر';

$LANG_fs['maps']['fs_main'] = 'دسترسی و امکانات';
$LANG_fs['maps']['fs_permissions'] = 'مجوزهای پیش‌فرض';
$LANG_fs['maps']['fs_uploads'] = 'تصاویر و بارگذاری‌ها';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = 'نمایش عمومی';
$LANG_fs['maps']['fs_global_map'] = 'نقشه سراسری و کاربران';
$LANG_fs['maps']['fs_display_profile'] = 'نقشه پروفایل کاربر';
$LANG_fs['maps']['fs_display_geo'] = 'برچسب Geo';
$LANG_fs['maps']['fs_map_defaults'] = 'پیش‌فرض‌های نقشه جدید';
$LANG_fs['maps']['fs_events_map'] = 'نقشه رویدادها';
$LANG_fs['maps']['fs_marker_defaults'] = 'پیش‌فرض‌های نشانگر';
$LANG_fs['maps']['fs_marker_editor'] = 'نقشه ویرایشگر نشانگر';
$LANG_fs['maps']['fs_marker_detail'] = 'نقشه جزئیات نشانگر';
$LANG_fs['maps']['fs_marker_popup'] = 'پنجره‌های اطلاعات نشانگر';
$LANG_fs['maps']['fs_marker_fields'] = 'فیلدها و برچسب‌های نشانگر';

$LANG_confignames['maps']['max_image_width'] = 'حداکثر عرض تصویر (px)';
$LANG_confignames['maps']['max_image_height'] = 'حداکثر ارتفاع تصویر (px)';
$LANG_confignames['maps']['max_image_size'] = 'حداکثر اندازه تصویر (بایت)';
$LANG_confignames['maps']['google_api_key'] = 'کلید API مرورگر Google Maps';
$LANG_confignames['maps']['google_server_api_key'] = 'کلید API سرور Google Geocoding';
$LANG_confignames['maps']['google_map_id'] = 'Google Map ID (آماده‌سازی Advanced Markers)';
$LANG_confignames['maps']['google_language'] = 'زبان Google Maps (اختیاری، مثلاً fa)';
$LANG_confignames['maps']['google_region'] = 'منطقه Google Maps (اختیاری، مثلاً IR)';
$LANG_confignames['maps']['url_geocode'] = 'URL سرویس Google Geocoding';
$LANG_confignames['maps']['map_primary_color'] = 'رنگ اصلی پیش‌فرض نقشه';
$LANG_confignames['maps']['map_stroke_color'] = 'رنگ حاشیه پیش‌فرض نقشه';
$LANG_confignames['maps']['map_label'] = 'برچسب پیش‌فرض نشانگر نقشه';
$LANG_confignames['maps']['map_label_color'] = 'رنگ پیش‌فرض برچسب نقشه';
$LANG_confignames['maps']['events_map_zoom'] = 'بزرگ‌نمایی نقشه رویدادها';
$LANG_confignames['maps']['events_map_height'] = 'ارتفاع نقشه رویدادها';
$LANG_confignames['maps']['users_map_lat'] = 'عرض مرکز نقشه کاربران (خالی = خودکار)';
$LANG_confignames['maps']['users_map_lng'] = 'طول مرکز نقشه کاربران (خالی = خودکار)';
$LANG_confignames['maps']['users_map_zoom'] = 'بزرگ‌نمایی نقشه کاربران (خالی = نقشه سراسری)';
$LANG_confignames['maps']['users_map_type'] = 'نوع نقشه کاربران (خالی = نقشه سراسری)';
$LANG_confignames['maps']['users_map_width'] = 'عرض نقشه کاربران (خالی = نقشه سراسری)';
$LANG_confignames['maps']['users_map_height'] = 'ارتفاع نقشه کاربران (خالی = نقشه سراسری)';
$LANG_confignames['maps']['marker_editor_type'] = 'نوع نقشه ویرایشگر نشانگر';
$LANG_confignames['maps']['marker_editor_zoom'] = 'بزرگ‌نمایی اولیه ویرایشگر نشانگر';
$LANG_confignames['maps']['marker_editor_width'] = 'عرض نقشه ویرایشگر نشانگر';
$LANG_confignames['maps']['marker_editor_height'] = 'ارتفاع نقشه ویرایشگر نشانگر';
$LANG_confignames['maps']['detail_width'] = 'عرض نقشه جزئیات نشانگر';
$LANG_confignames['maps']['detail_height'] = 'ارتفاع نقشه جزئیات نشانگر';
$LANG_confignames['maps']['detail_zoom'] = 'بزرگ‌نمایی نقشه جزئیات نشانگر';
$LANG_confignames['maps']['popup_width'] = 'عرض پنجره اطلاعات';
$LANG_confignames['maps']['popup_height'] = 'ارتفاع پنجره اطلاعات';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = 'SEO صفحه فرود';
$LANG_confignames['maps']['maps_page_title'] = 'عنوان SEO صفحه فرود نقشه‌ها';
$LANG_confignames['maps']['maps_page_h1'] = 'عنوان H1 صفحه فرود نقشه‌ها';
$LANG_confignames['maps']['maps_meta_description'] = 'توضیح متای صفحه فرود نقشه‌ها';
$LANG_confignames['maps']['map_main_header'] = 'محتوای معرفی صفحه فرود نقشه‌ها (پشتیبانی از برچسب خودکار)';

$LANG_MAPS_1['server_geocode_key_missing'] = 'جستجوی مختصات سمت سرور فعال است، اما کلید API اختصاصی Google Geocoding برای سرور تنظیم نشده است. کلید مرورگر هرگز برای ژئوکدینگ سمت سرور استفاده نمی‌شود.';
$LANG_MAPS_1['api_diag_title'] = 'پیکربندی Google Maps Platform';
$LANG_MAPS_1['api_diag_maps_js'] = 'Maps JavaScript API';
$LANG_MAPS_1['api_diag_geocoding'] = 'Geocoding API';
$LANG_MAPS_1['api_diag_directions'] = 'Directions API';
$LANG_MAPS_1['api_diag_browser_key'] = 'کلید API مرورگر';
$LANG_MAPS_1['api_diag_server_key'] = 'کلید API سرور';
$LANG_MAPS_1['api_diag_map_id'] = 'شناسه نقشه';
$LANG_MAPS_1['api_diag_configured'] = 'کلید تنظیم شد — API تأیید نشده';
$LANG_MAPS_1['api_diag_browser_verify'] = 'کلید تنظیم شد — با آزمایش مرورگر زیر بررسی کنید';
$LANG_MAPS_1['api_diag_referrer_hint'] = 'برای محدودیت HTTP referrer کلید مرورگر، این سایت را مجاز کنید (مثلاً: %s/*).';
$LANG_MAPS_1['api_diag_missing'] = 'موجود نیست';
$LANG_MAPS_1['api_diag_optional'] = 'اختیاری / پیکربندی نشده';
$LANG_MAPS_1['integrations_title'] = 'یکپارچگی‌ها';
$LANG_MAPS_1['integrations_intro'] = 'نقشه‌ها از APIها و سرویس‌های Geeklog استفاده می‌کند تا افزونه‌های مرتبط بدون اتصال مستقیم به پایگاه داده بتوانند آن را شناسایی کنند.';
$LANG_MAPS_1['integration_active'] = 'فعال';
$LANG_MAPS_1['integration_missing'] = 'افزونه موجود نیست';
$LANG_MAPS_1['integration_native'] = 'پشتیبانی داخلی';
$LANG_MAPS_1['integration_xmlsitemap'] = 'نقشه سایت XML';
$LANG_MAPS_1['integration_documents'] = 'اسناد';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'خوراک‌های RSS / Atom';
$LANG_MAPS_1['use_my_location'] = 'استفاده از موقعیت من';
$LANG_MAPS_1['geolocation_unavailable'] = 'موقعیت‌یابی مرورگر در دسترس نیست. آدرس شروع را دستی وارد کنید.';
$LANG_MAPS_1['geolocation_denied'] = 'موقعیت شما دریافت نشد. دسترسی موقعیت را مجاز کنید یا آدرس شروع را دستی وارد کنید.';
$LANG_MAPS_1['geolocation_https'] = 'موقعیت‌یابی مرورگر معمولاً به HTTPS نیاز دارد.';
?>
