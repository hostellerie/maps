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
    'plugin_name'           => '地圖',
    'plugin_conf'           => '插件設定',
    '地圖'                   => '地圖',
    'need_google_api'       => '未設定 Google Maps 瀏覽器 API 密鑰。新增此密鑰之前无法顯示 Google 地圖。',
    'api_status_title' => 'Google Maps API 狀態',
    'api_status_missing' => '未設定 Google Maps 瀏覽器 API 密鑰。',
    'api_status_testing' => '正在測試 Google Maps JavaScript API…',
    'api_status_key' => '瀏覽器密鑰',
    'api_status_ok' => 'Google Maps JavaScript API 已成功載入：瀏覽器密鑰可用于此頁面。',
    'api_status_auth' => 'Google Maps 拒絕了瀏覽器密鑰或其設定。请在瀏覽器控制台查看 Google 的确切錯誤代碼，然后檢查 HTTP 引荐來源限制、已啟用的 API 和 Google Cloud 結算。',
    'api_status_load' => '无法載入 Google Maps JavaScript API 脚本。请檢查網路、CSP 政策、內容拦截器和瀏覽器控制台。',
    'api_status_timeout' => 'Google Maps JavaScript API 未回應。请檢查瀏覽器控制台和網路面板。',
    'admin_help_title'      => '地圖入門',
    'admin_help_intro'      => '地圖插件可建立多個地圖、新增標記，并在需要时使用自訂圖標或疊加層。',
    'admin_help_google'     => '設定 Google Maps',
    'admin_help_google_1'   => '開啟 Google Cloud Console，建立或選擇專案，并为生产使用連結結算帳戶。',
    'admin_help_google_2'   => '至少啟用 Maps JavaScript API。如果使用地址自動轉換为經緯度，还应啟用 Geocoding API。',
    'admin_help_google_3'   => '建立用于瀏覽器的 API 密鑰。将其限制为授权網站（HTTP 引荐來源），例如 https://www.example.com/*，然后将此密鑰限制为 Maps JavaScript API。',
    'admin_help_google_4'   => '对于伺服器端地理编码，建议建立第二个密鑰。将其限制为伺服器 IP 地址并仅允許 Geocoding API。',
    'admin_help_google_5'   => '将瀏覽器密鑰複製到 Geeklog 地圖設定中的 Google Maps API 密鑰，如使用伺服器密鑰，则複製到 Google Maps 伺服器 API 密鑰。',
    'admin_help_security'   => '生产环境中切勿让 Google Maps 密鑰不受限制。分離瀏覽器和伺服器密鑰可降低未经授权使用的風險。',
    'admin_help_create'     => '建立第一个地圖',
    'admin_help_create_1'   => '點擊“建立新地圖”，为其命名，并選擇中心點、縮放級別和顯示類型。',
    'admin_help_create_2'   => '儲存地圖，然后在地圖管理中新增標記。每个標記可使用地址或精確坐標。',
    'admin_help_create_3'   => '圖標和疊加層是可選的。先用簡單地圖和少量標記驗證 Google Maps 設定。',
    'admin_help_trouble'    => '如果地圖變灰或顯示“僅供開發使用”，请檢查 Google Cloud 結算、已啟用的 API 和 API 密鑰限制。',
    'admin_help_official'   => 'Google Maps Platform 官方文件',
    'admin_help_geo_title'  => '“檢查使用者地理位置”有什麼作用？',
    'admin_help_geo_intro'  => '此命令掃描在個人資料中填寫了“位置”欄位的成員，并为使用者地圖準備坐標。',
    'admin_help_geo_1'      => '地圖插件会将每个尚未解析的文本位置傳送到 Google Geocoding API，例如“法国南特”。',
    'admin_help_geo_2'      => "傳回的緯度和經度会快取在地圖地理编码表中，不会修改成員的 Geeklog 個人資料。",
    'admin_help_geo_3'      => '这主要用于安裝、移轉之后，或許多成員新增或變更位置时。它需要 Geocoding API 以及允許伺服器端地理编码的密鑰。',
    'admin_help_overlays_title' => '疊加層有什麼用途？',
    'admin_help_overlays_intro' => '疊加層是放置在 Google 地圖上的地理参考圖像，其範圍由西南和东北坐標確定。它可新增 Google Maps 基礎圖層中没有的視覺資訊。',
    'admin_help_overlays_1' => '在真实地圖上顯示場地、營地、公園、莊園、建築或節慶平面圖。',
    'admin_help_overlays_2' => '疊加歷史、地籍、地質或旅遊地圖，或旧平面圖，以便比較。',
    'admin_help_overlays_3' => '顯示主题區域，例如圖示路線、施工區、自然區域、專案範圍或其他圖形資訊。',
    'admin_help_overlays_4' => '設定最小和最大縮放級別，使疊加層仅在有用时顯示。',
    'admin_help_overlays_how' => '建立疊加層：準備合适的圖像，開啟“疊加層”，輸入其西南和东北边界，然后在地圖編輯器的“疊加層”標签中将其附加到地圖。疊加層是可選的：一般點位地圖只需標記即可。',
    'admin_help_concepts_title' => '地圖概念一覽',
    'admin_help_concept_map' => '地圖',
    'admin_help_concept_map_text' => '主要容器：中心、縮放、顯示類型、尺寸、權限和一般選項。',
    'admin_help_concept_marker' => '標記',
    'admin_help_concept_marker_text' => '地圖上的地理点，包含名稱、描述、地址和可選附加資訊。',
    'admin_help_concept_icon' => '圖標',
    'admin_help_concept_icon_text' => '用于替代標准 Google 標記的可選圖像，以区分不同點位類別。',
    'admin_help_concept_users' => '使用者地圖',
    'admin_help_concept_users_text' => '根据 Geeklog 個人資料中的“位置”欄位生成的地圖。坐標由地圖地理编码系統解析并快取。',
    'admin_help_trouble_title' => '快速故障排查',
    'profile_title'         => '地理定位',
    'buy_marker'            => '購買標記',
    'menu_label'            => '地圖管理',
    'admin_home'            => '首頁', // In admin menu
    'user_home'             => '所有地圖', //In user menu
    'maps'                  => '地圖',
    '標記'               => '標記',
    'maps_label'            => '地圖', // For user  menu
    'create_map'            => '建立新地圖',
    'create_marker'         => '建立新標記',
    'map_edit'              => '編輯地圖',
    'marker_edit'           => '編輯標記',
    'deletion_succes'       => '刪除成功',
    'deletion_fail'         => '刪除失敗',
    'error'                 => '錯誤',
    'save_fail'             => '儲存失敗',
    'save_success'          => '儲存成功',
    'missing_field'         => '缺少必填欄位…',
    'geocoder'              => '地理编码器',
    'geocoder_text'         => '輸入地址，然后拖曳標記調整位置。每次地理编码或拖曳后，緯度/經度会顯示在資訊視窗中。',
    'geocode_failed'         => '无法对地址进行地理编码。请檢查 Google Maps API 密鑰、Geocoding API 是否啟用以及地址，然后重試。',
    'go'                    => '前往！',
    'name_label'            => '地圖名稱：',
    'marker_name_label'     => '標記名稱：',
    'description_label'     => '描述：',
    'ok_button'             => '確定',
    'edit_button'           => '編輯',
    'save_button'           => '儲存',
    'delete_button'         => '刪除',
    'yes'                   => '是',
    'no'                    => '否',
    'required_field'        => '表示必填欄位',
    'address_label'         => '地址：',
    'message'               => '訊息',
    'general_settings'      => '一般設定',
    'map_width'             => '地圖寬度（% 或 px，最小 550px）：',
    'map_height'             => '地圖高度（仅 px，最小 350px）：',
    'map_zoom'              => '地圖縮放（0-21）：',
    'map_type'              => '地圖類型：',
    'active'                => '地圖已啟用：',
    'hidden'                => '地圖已隱藏：',
    'marker_active'         => '標記已啟用：',
    'marker_hidden'         => '標記已隱藏：',
    'free_marker'           => '地圖接受免費標記：',
    'paid_marker'           => '地圖接受付費標記：',
    'error_address_empty'   => '请先輸入有效地址。',
    'error_invalid_address' => '此地址无效。请确保同时輸入門牌号和城市。',
    'error_google_error'    => '處理請求时出现问题，请重試。',
    'error_no_map_info'     => '抱歉，此地址没有可用的地圖資訊。',
    'need_directions'       => '需要路線？请輸入您的地址：',
    'directions_title'     => '規劃路線',
    'directions_start'     => '起點',
    'get_directions'        => '  取得路線  ',
    'maps_list'             => '地圖清單',
    'you_can'               => '您可以 ',
    'user_maps_list'        => '瀏覽我们的地圖',
    'markers_list'          => '標記清單',
    'map_markers_heading'   => '此地圖上的標記',
    'marker_singular'      => '標記',
    'marker_plural'        => '標記',
    'views_label'          => '瀏覽次数',
    'no_map'                => '資料庫中没有地圖。必须先建立地圖才能新增標記。',
    'no_map_user'           => '哎呀… 資料庫中没有啟用的地圖。',
    'value_directions'      => '例如：門牌號、街道、城市、國家', // No quote here please
    'id'                    => 'ID',
    'name'                  => '名稱',
    'description'           => '描述',
    'active_field'          => '啟用',
    'hidden_field'          => '隱藏',
    'marker_count'          => '標記',
    'status_active'         => '啟用',
    'status_inactive'       => '未啟用',
    'status_visible'        => '可见',
    'status_hidden'         => '隱藏',
    'title_display'         => '顯示地圖頁面',
    'map_header_label'      => '可選地圖页眉',
    'map_footer_label'      => '可選地圖页脚',
    'header_footer'         => '页眉和页脚',
    'informations'          => '資訊',
    'must_belong_to'        => '要存取此地圖，您必须属于群組：',
    'private_access'        => '私人存取',
    'marker_label'          => '標記',
    'primary_color_label'   => '主色',
    'stroke_color_label'    => '描边顏色',
    'label'                 => '標签',
    'label_color'           => '標签顏色',
    'black'                 => '黑色',
    'white'                 => '白色',
    'payed'                 => '付費標記：',
    'lat'                   => '緯度：',
    'lng'                   => '經度：',
    'ressources_tab'        => '資源標签页',
    'presentation'          => '展示',
    'ressources'            => '資源',
    'presentation_tab'      => '展示標签页',
    'empty_ressources'      => '資源標签为空。至少需要設定一个才能使用資源。请参阅設定。',
    'empty_for_geo'         => '如果需要根据上面的地址自動定位，请将緯度和經度留空。',
    'select_marker_map'     => '選擇希望標記出现的地圖。',
    'remark'                => '備註',
    'marker_created'        => '標記建立于：',
    'map_created'           => '地圖建立于：',
    'modified'              => '最后修改：',
    'marker_validity'       => '使用有效期：',
    'maps_empty'            => '请先建立地圖。',
    'from'                  => '从：',
    '到'                    => '到：',
    'date_issue'            => '結束有效期早于開始有效期，请檢查。',
    'max_char'              => '最大字元数。',
    'street_label'          => '街道：',
    'code_label'            => '郵遞區號：',
    'city_label'            => '城市：',
    'state_label'           => '州/區域：',
    'country_label'         => '國家：',
    'tel_label'             => '電話：',
    'fax_label'             => '其他聯絡方式：',
    'web_label'             => '網站：',
    'not_use_see_config'    => '不使用。请参阅設定',
    //global maps
    'global_map'            => '全域地圖',
    'info_global_map'       => '这是所有地圖的彙總。',
    'users_map'             => '網站使用者地圖',
    'info_users_map'        => '这是網站使用者地圖。您可以在個人資料中設定位置，将自己新增到地圖。',
    //Submission
    'address'               => '地址',
    'created'               => '日期',
    'submit_marker'         => '提交標記',
    'submit_marker_text'    => '<p><ol><li>設定標記位置<li>填寫所有欄位<li>确认</ol></p>',
    'markers_submissions'   => '標記提交',
    'submission_disabled'   => '標記提交佇列已禁用',
    'go'                    => '顯示此地址',
    //date and hits
    'last_modification'     => '最后修改：',
    '存取次数'                  => '存取次数',
    //user marker
    'member'                => '成員',
    'location'              => '位置：',
    'regdate'               => '加入时间：',
    'about'                 => '关于',
    'my_markers'            => '我的標記',
    'payed_label'           => '付費',
    'from_label'            => '有效期開始',
    'to_label'              => '有效期結束',
    'no_marker'             => '您没有任何標記，或者標記尚未获批。如果认为有误，请联系網站管理員。',
    'marker_detail'         => '標記詳細資料',
    'admin_can'             => '作为地圖管理員，您可以',
    'create_map'            => '建立新地圖',
    'set_user_geo'          => '設定使用者地理位置',
    'set_geo_location'      => '系統将檢查并設定所有地理位置。',
    '記录'               => '記录',
    'report'                => '檢舉此標記',
    'report_subject'        => '关于標記的檢舉 ',
    'edit_marker_text'      => '<p><ol><li>設定標記位置<li>填寫所有必填欄位<li>然后确认</ol></p>',
    'admin'                 => '管理',
    'category_label'        => '類別：',
    'choose_category'       => '-- 選擇類別 --',
    'categories'            => '類別',
    'categories_list'       => '類別清單',
    'cat_edit'              => '編輯類別：',
    'cat_name_label'        => '類別名稱：',
    'create_cat'            => '建立新類別',
    'field_list'            => '欄位清單',
    'addfield'              => '新增欄位',
    'field_name'            => '欄位名稱',
    'field_order'           => '順序',
    'field_autotag'         => '自動標签',
    'field_rights'          => '權限',
    'field_edit'            => '編輯',
    'valid'                 => '有效',
    'editing_field'         => '編輯欄位',
    'category'              => '類別',
    'map_label'             => '地圖',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => '查看地圖',
    'view_markers'          => '顯示標記清單',
    'code'                  => '郵遞區號',
    'city'                  => '城市',
    'viewing_markers'       => '顯示標記清單',
    'details'               => '詳細資料',
    'view_details'          => '查看詳細資料',
    'print'                 => '列印',
	'to_complete'           => '待完成',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - 顯示 id=XX 的地圖。選項包括縮放級別（0 到 21）以及将地圖中心设为 location。',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - 顯示以地点名稱或地址为中心的地圖。可選参数：zoom、width 和 height。仍支援歷史语法 [geo: map ...]。',
	'autotag_desc_marker'   => '[marker: xx] - 顯示 id=XX 的標記',
	//v1.1
	'marker_customisation'  => '標記自訂',
	'mk_default'            => '使用預設標記',
	'overlays'              => '疊加層',
	'overlays_list'         => '疊加層清單',
	'create_overlay'        => '建立新疊加層',
	'edit_overlay_text'     => '編輯疊加層：',
	'overlay_edit'          => '編輯疊加層',
	'overlay_name_label'    => '疊加層名稱：',
	'overlay_presentation'  => '疊加層是绑定到經緯度坐標的地圖对象，因此拖曳或縮放地圖时会随之移動。疊加層表示新增到地圖中的点、线或區域。这里可以将圖像新增为疊加層。',
	'overlay_active'        => '此疊加層已啟用：',
	'zoom_min_label'        => '最小縮放：',
	'zoom_max_label'        => '最大縮放：',
	'image_message'         => '从硬碟選擇圖像。',
	'image_replace'         => '上傳新圖像将替换当前圖像：',
	'image'                 => '圖像',
	'sw_lat'                => '西南緯度：',
    'sw_lng'                => '西南經度：',
	'ne_lat'                => '东北緯度：',
    'ne_lng'                => '东北經度：',
	'overlay_not_writable'  => '疊加層資料夾不可写。请先建立此資料夾并赋予寫入權限，然后再使用此功能。',
	'map_tab'               => '地圖',
	'overlays_tab'          => '疊加層',
	'add_overlay'           => '新增疊加層',
	'remove_overlay'        => '移除疊加層',
	'overlay_label'         => '疊加層',
	'import_export'         => '匯入/匯出',
	'import'                => '匯入',
	'export'                => '匯出',
	'select_file'           => '選擇 .csv 文件',
	'import_message'        => '選擇要新增標記的地圖、硬碟上的 CSV 文件、資料分隔符以及要匯入的欄位。',
	'markers_added'         => '已新增到地圖的標記：',
	'export_message'        => '選擇要匯出標記的地圖、資料分隔符以及要匯出的欄位。',
	'no_marker_to_export'   => '抱歉，此地圖没有可匯出的標記。',
	'icons'                 => '圖標',
	'icons_not_writable'    => '圖標資料夾不可写。请先建立此資料夾并赋予寫入權限，然后再使用此功能。',
	'icons_list'            => '圖標清單',
	'create_icon'           => '建立新圖標',
	'icon_edit'             => '編輯圖標',
	'icon_presentation'     => '这里可以上傳用于標記的新圖標', 
	'icon_name_label'       => '圖標名稱',
	'xmarkers'              => '標記',
	'1marker'               => '標記',
	'choose_icon'           => '可以为此標記選擇圖標。优先圖標顯示在顏色選項之上。',
	'no_icon'               => '无圖標',
	'no_custom_icons'        => '尚未注册自訂圖標。',
	'manage_icons'           => '管理圖標',
	'separator'             => '選擇分隔符',
	'markers_to_add'        => '请檢查所有欄位/值对，并确认要将以下所有標記新增到地圖：',
	'choose_fields_import'  => '選擇匯入欄位',
	'choose_fields_export'  => '選擇匯出欄位',
	'checkall'              => '全选',
    'import_step_1' => '準備匯入',
    'import_step_1_text' => '選擇目標地圖、CSV 文件、分隔符和列順序。',
    'import_step_2' => '檢查資料',
    'import_step_2_text' => '地圖插件会在寫入任何內容之前驗證、標準化并对各行进行地理编码。',
    'import_step_3' => '确认匯入',
    'import_step_3_text' => '檢查目標、擁有者和權限，然后确认批次。',
    'import_minimum' => '最少欄位',
    'import_minimum_help' => 'name + address，或 name + lat + lng。最少欄位预设使用基于地址的方式。',
    'import_recommended' => '建議欄位',
    'import_recommended_help' => 'name、address、lat、lng、description、street、code、city、state、country、tel 和 web。',
    'import_order_help' => 'CSV 列必须与下面所选欄位保持相同順序。',
    'import_select_minimum' => '最少欄位',
    'import_select_recommended' => '建議欄位',
    'import_clear_fields' => '清除選擇',
    'import_preview_title' => '檢查資料',
    'import_preview_text' => '确认匯入后，将寫入这些標準化值。',
    'import_summary_rows' => '行已就緒',
    'import_summary_coordinates' => '已提供坐標',
    'import_summary_geocoded' => '已自動地理编码',
    'import_summary_partial' => '包含部分地址詳細資料',
    'import_status' => '狀態',
    'import_status_ready' => '就緒',
    'import_status_partial' => '就緒 · 部分詳細資料',
    'import_status_geocoded' => '已地理编码',
    'import_confirm_title' => '确认匯入',
    'import_confirm_text' => '建立標記前请檢查批次設定。',
    'import_confirm_button' => '匯入 %d 个標記',
    'import_cancel_button' => '取消',
	'order'                 => '順序',
	'move'                  => '移動',
	'name_missing'          => '至少缺少一个名稱。请檢查 CSV 文件。',
	'need_address'          => '建立標記至少需要地址或坐標。请檢查 CSV 文件，存在缺失資料。',
	'manage_groups'         => '管理疊加層群組',
	'create_group'          => '建立新疊加層群組',
	'group_edit'            => '編輯疊加層群組',
	'group_overlay_presentation' => '这里可以選擇或編輯疊加層群組的名稱',
	'group_overlay_name_label'   => '群組名稱',
	'group_label'           => '群組（可選）',
	'choose_group'          => '選擇群組',
	'group'                 => '群組',
	
	//v1.3
	'geo_fail'              => '輸入的地址似乎无效',
	'on_map'                => '在地圖上',
	'read_more'             => '閱讀更多',
	'from_map'              => '地圖：',
	'show_hide_overlays'    => '顯示 / 隱藏疊加層',
	'fields_presentation'   => '編輯现有類別以新增或編輯欄位。',
	'overlays_added'        => '此地圖上的疊加層',
	'overlays_to_add'       => '可新增到此地圖的疊加層',
	'marker_modification'   => '修改標記',
	'from_owner'            => '新增者：',
	'marker_limited'        => '抱歉，此標記的存取受限…',
	'events_map'            => '近期活动地圖',
	'info_events_map'       => '',
	'from_cal'              => '从',
	'to_cal'                => '到',
	'on_cal'                => '开',
    //v1.4
    'admin_menu_maps' => '地圖',
    'admin_menu_markers' => '標記',
    'admin_menu_icons' => '圖標',
    'admin_menu_overlays' => '疊加層',
    'admin_menu_import_export' => '匯入/匯出',
    'admin_menu_geocoder' => '地理编码器',
    'admin_menu_geolocation' => '地理位置',
    'admin_menu_configuration' => '設定',
    'section_location' => '位置',
    'section_content_contact' => '內容和聯絡方式',
    'section_appearance' => '外观',
    'section_publication' => '發布',
    'section_resources' => '資源',
    'section_ownership' => '擁有者',
    'section_permissions' => '權限',
    'delete_confirm' => '永久刪除此標記？',
    'marker_not_found' => '未找到標記，或您没有查看權限。',
    'delete_map_confirm' => '永久刪除此地圖？',
    'technical_coordinates' => '技術坐標',
    'configuration'         => '設定',
    // Maps 1.5.6 map editor
    'map_section_display' => '顯示',
    'map_section_center' => '中心和縮放',
    'map_section_markers' => '標記',
    'map_section_advanced' => '進階選項',
    'map_center_search' => '搜尋地址',
    'map_center_search_button' => '定位',
    'map_center_use_button' => '使用顯示的中心',
    'map_center_help' => '搜尋地址，點擊地圖或拖曳標記以精確選擇地圖中心。',
    'latitude_label' => '緯度',
    'longitude_label' => '經度',
    'map_center_marker' => '地圖中心',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => '系統訊息',
    'add_new_field'         => '新欄位已成功建立',
    'save_field'            => '欄位已成功儲存',
    'delete_field'          => '欄位已成功刪除'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => '管理員您好，',
    'new_marker'            => '有一个新標記等待審核。',
    'name'                  => '名稱：',
    'on_map'                => '所在地圖：',
    'submissions'           => '提交：',
    'marker_submissions'    => '標記提交',
	'marker_modification'   => '修改標記',
	'description'           => '描述：',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "感谢您向 {$_CONF['site_name']} 提交標記。该標記已提交给工作人员審核。";
$PLG_maps_MESSAGE2  = "標記提交已關閉。";
$PLG_maps_MESSAGE3  = "哎呀… 发生錯誤，无法儲存您的標記。";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => '地圖',
    'title' => '地圖設定'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => '隱藏地圖菜单',
    'maps_login_required'   => '地圖需要登录',
    'autofill_coord'        => '自動填寫未定义坐標',
    'display_geo_profile'   => '個人資料地理定位',
    'map_type_profile'      => '個人資料地圖類型',
    'map_type_geotag'       => 'geo 自動標签地圖類型',
    'show_directions_geo'   => 'geo 自動標签顯示路線',
    'show_directions_profile' => '個人資料顯示路線',
    'map_width_geotag'      => 'geo 自動標签地圖寬度（% 或 px）',
    'map_height_geotag'     => 'geo 自動標签地圖高度（仅 px）',
    'map_zoom_geotag'       => 'geo 自動標签縮放（0-21）',
    'map_width_profile'     => '個人資料地圖寬度（% 或 px）',
    'map_height_profile'    => '個人資料地圖高度（仅 px）',
    'show_map'              => '顯示 Google 地圖',
    'google_api_key'        => 'Google Maps API 密鑰',
    'url_geocode'           => 'Google Geocoding 服務 URL',
    'map_width'             => '預設地圖寬度（% 或 px）',
    'map_height'            => '預設地圖高度（仅 px）',
    'map_zoom'              => '預設地圖縮放（0-21）',
    'map_type'              => '預設地圖類型',
    'default_permissions'   => '預設權限',
    'map_main_header'       => '主页页眉，autotag welcome',
    'map_main_footer'       => '主页页脚，也支援 autotag welcome',
    'map_geo'               => '建立包含所有個人資料的地圖',
    'map_markers'           => '建立包含所有標記的地圖',
    'map_active'            => '地圖已啟用',
    'map_hidden'            => '地圖已隱藏',
    'free_markers'          => '地圖接受免費標記',
    'paid_markers'          => '地圖接受付費標記（需要 PayPal 插件）',
    'street'                => '使用街道資訊',
    'code'                  => '使用郵遞區號資訊',
    'city'                  => '使用城市資訊',
    'state'                 => '使用州/區域資訊',
    'country'               => '使用國家資訊',
    'tel'                   => '使用電話資訊',
    'fax'                   => '使用其他聯絡方式',
    'web'                   => '使用網站資訊',
    'item_1'                => '自訂欄位 1 標签',
    'item_2'                => '自訂欄位 2 標签',
    'item_3'                => '自訂欄位 3 標签',
    'item_4'                => '自訂欄位 4 標签',
    'item_5'                => '自訂欄位 5 標签',
    'item_6'                => '自訂欄位 6 標签',
    'item_7'                => '自訂欄位 7 標签',
    'item_8'                => '自訂欄位 8 標签',
    'item_9'                => '自訂欄位 9 標签',
    'item_10'               => '自訂欄位 10 標签',
    'label_color'           => '標签顏色',
    'star_primary_color'    => '星形主色',
    'star_stroke_color'     => '星形描边顏色',
    'marker_active'         => '標記預設啟用',
    'marker_hidden'         => '標記預設隱藏',
    'marker_payed'          => '標記預設付費',
    'marker_validity'       => '標記預設有效期',
    'marker_submission'     => '允許提交標記',
    'users_map'             => '啟用網站使用者地圖',
    'global_map' 	        => '啟用全域地圖',
    'global_type'           => '全域地圖類型',	
    'global_width'  	    => '全域地圖寬度',
    'global_height' 	    => '全域地圖高度',
    'global_zoom'           => '全域地圖縮放（0-21）',
    'detail_zoom'           => '標記詳細資料縮放（0-21）',
    'submit_login_required' => '提交標記需要登录',
    'marker_edition'        => '編輯標記',
	'use_cluster'           => '使用標記叢集',
	'zoom_profile'          => '使用者個人資料地圖縮放（0-21）',
	'display_events_map'    => '顯示活动地圖',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => '主要設定',
    'sg_display' => '顯示設定'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => '一般',
    'tab_google' => 'Google Maps',
    'tab_maps' => '地圖',
    'tab_markers' => '標記',
    'tab_fields' => '標記欄位',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => '一般',
    'tab_google' => 'Google Maps',
    'tab_maps' => '地圖',
    'tab_markers' => '標記',
    'tab_fields' => '標記欄位',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => '一般設定',
    'fs_ads'             => 'Google Ads 設定',
    'fs_google'          => 'Google API 設定',
    'fs_permissions'     => '預設權限',
    'fs_display'         => '地圖',
    'fs_global_map'      => '全域地圖',
    'fs_display_profile' => '個人資料',
    'fs_display_geo'     => 'geo 自動標签',
    'fs_map_default'     => '地圖預設設定',
    'fs_marker_default'  => '標記預設設定',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('是' => 1, '否' => 0),
    1 => array('是' => TRUE, '否' => FALSE),
    3 => array('是' => 1, '否' => 0),
    4 => array('开' => 1, '关' => 0),
    5 => array('頁面顶部' => 1, '精选文章下方' => 2, '頁面底部' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('英里' => '英里', '公里' => 'km'),
    12 => array('无存取權限' => 0, '唯讀' => 2, '讀寫' => 3),
	// changed in v1.3
    20 => array('一般街道地圖' => 'ROADMAP', '衛星圖像' => 'SATELLITE', '地形圖' => 'TERRAIN', '衛星圖像上的主要街道透明圖層' => 'HYBRID'),
    30 => array('白色' => 1, '黑色' => 0),
    31 => array('暫時' => 1, '永久' => 0),
);

$LANG_MAPS_1['location_search_label'] = '搜尋地址';
$LANG_MAPS_1['location_search_help'] = '搜尋地址，點擊地圖或拖曳標記以精细調整位置。';
$LANG_MAPS_1['use_map_click_help'] = '點擊地圖移動標記。';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = '主要';
$LANG_configtabs['maps']['tab_general'] = '一般';
$LANG_tab['maps']['tab_general'] = '一般';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = '地圖';
$LANG_tab['maps']['tab_maps'] = '地圖';
$LANG_configtabs['maps']['tab_markers'] = '標記';
$LANG_tab['maps']['tab_markers'] = '標記';
$LANG_configtabs['maps']['tab_fields'] = '標記欄位';
$LANG_tab['maps']['tab_fields'] = '標記欄位';

$LANG_fs['maps']['fs_main'] = '存取和功能';
$LANG_fs['maps']['fs_permissions'] = '預設權限';
$LANG_fs['maps']['fs_uploads'] = '圖像和上傳';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = '一般顯示';
$LANG_fs['maps']['fs_global_map'] = '全域地圖和使用者地圖';
$LANG_fs['maps']['fs_display_profile'] = '使用者個人資料地圖';
$LANG_fs['maps']['fs_display_geo'] = 'Geo 自動標签';
$LANG_fs['maps']['fs_map_defaults'] = '新地圖預設設定';
$LANG_fs['maps']['fs_events_map'] = '活动地圖';
$LANG_fs['maps']['fs_marker_defaults'] = '標記預設設定';
$LANG_fs['maps']['fs_marker_editor'] = '標記編輯器地圖';
$LANG_fs['maps']['fs_marker_detail'] = '標記詳細資料地圖';
$LANG_fs['maps']['fs_marker_popup'] = '標記資訊視窗';
$LANG_fs['maps']['fs_marker_fields'] = '標記欄位和標签';

$LANG_confignames['maps']['max_image_width'] = '最大圖像寬度（px）';
$LANG_confignames['maps']['max_image_height'] = '最大圖像高度（px）';
$LANG_confignames['maps']['max_image_size'] = '最大圖像大小（字节）';
$LANG_confignames['maps']['google_api_key'] = 'Google Maps 瀏覽器 API 密鑰';
$LANG_confignames['maps']['google_server_api_key'] = 'Google Geocoding 伺服器 API 密鑰';
$LANG_confignames['maps']['google_map_id'] = 'Google 地圖 ID（为 Advanced Markers 準備）';
$LANG_confignames['maps']['google_language'] = 'Google Maps 語言（可選，例如 zh-CN）';
$LANG_confignames['maps']['google_region'] = 'Google Maps 區域（可選，例如 CN）';
$LANG_confignames['maps']['url_geocode'] = 'Google Geocoding 服務 URL';
$LANG_confignames['maps']['map_primary_color'] = '預設地圖主色';
$LANG_confignames['maps']['map_stroke_color'] = '預設地圖描边顏色';
$LANG_confignames['maps']['map_label'] = '預設地圖標記標签';
$LANG_confignames['maps']['map_label_color'] = '預設地圖標签顏色';
$LANG_confignames['maps']['events_map_zoom'] = '活动地圖縮放';
$LANG_confignames['maps']['events_map_height'] = '活动地圖高度';
$LANG_confignames['maps']['users_map_lat'] = '使用者地圖中心緯度（空白 = 自動）';
$LANG_confignames['maps']['users_map_lng'] = '使用者地圖中心經度（空白 = 自動）';
$LANG_confignames['maps']['users_map_zoom'] = '使用者地圖縮放（空白 = 全域地圖）';
$LANG_confignames['maps']['users_map_type'] = '使用者地圖類型（空白 = 全域地圖）';
$LANG_confignames['maps']['users_map_width'] = '使用者地圖寬度（空白 = 全域地圖）';
$LANG_confignames['maps']['users_map_height'] = '使用者地圖高度（空白 = 全域地圖）';
$LANG_confignames['maps']['marker_editor_type'] = '標記編輯器地圖類型';
$LANG_confignames['maps']['marker_editor_zoom'] = '標記編輯器初始縮放';
$LANG_confignames['maps']['marker_editor_width'] = '標記編輯器地圖寬度';
$LANG_confignames['maps']['marker_editor_height'] = '標記編輯器地圖高度';
$LANG_confignames['maps']['detail_width'] = '標記詳細資料地圖寬度';
$LANG_confignames['maps']['detail_height'] = '標記詳細資料地圖高度';
$LANG_confignames['maps']['detail_zoom'] = '標記詳細資料地圖縮放';
$LANG_confignames['maps']['popup_width'] = '資訊視窗寬度';
$LANG_confignames['maps']['popup_height'] = '資訊視窗高度';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = '入口頁面 SEO';
$LANG_confignames['maps']['maps_page_title'] = '地圖入口頁面 SEO 標题';
$LANG_confignames['maps']['maps_page_h1'] = '地圖入口頁面 H1 標题';
$LANG_confignames['maps']['maps_meta_description'] = '地圖入口頁面中繼資料描述';
$LANG_confignames['maps']['map_main_header'] = '地圖入口頁面介紹內容（支援自動標签）';

$LANG_MAPS_1['server_geocode_key_missing'] = '伺服器端坐標查詢已啟用，但未設定專用的 Google Geocoding 伺服器 API 密鑰。瀏覽器密鑰絕不會用于伺服器端地理编码。';
$LANG_MAPS_1['api_diag_title'] = 'Google Maps Platform 設定';
$LANG_MAPS_1['api_diag_maps_js'] = 'Maps JavaScript API';
$LANG_MAPS_1['api_diag_geocoding'] = 'Geocoding API';
$LANG_MAPS_1['api_diag_directions'] = 'Directions API';
$LANG_MAPS_1['api_diag_browser_key'] = '瀏覽器 API 密鑰';
$LANG_MAPS_1['api_diag_server_key'] = '伺服器 API 密鑰';
$LANG_MAPS_1['api_diag_map_id'] = '地圖 ID';
$LANG_MAPS_1['api_diag_configured'] = '密鑰已設定 — API 未驗證';
$LANG_MAPS_1['api_diag_browser_verify'] = '密鑰已設定 — 请使用下面的瀏覽器測試进行驗證';
$LANG_MAPS_1['api_diag_referrer_hint'] = '对于瀏覽器密鑰的 HTTP 引荐來源限制，请授权此網站（例如：%s/*）。';
$LANG_MAPS_1['api_diag_missing'] = '缺少';
$LANG_MAPS_1['api_diag_optional'] = '可選 / 未設定';
$LANG_MAPS_1['integrations_title'] = '整合';
$LANG_MAPS_1['integrations_intro'] = '地圖插件使用 Geeklog API 和服務，使相关插件无需直接耦合資料庫即可发现地圖功能。';
$LANG_MAPS_1['integration_active'] = '啟用';
$LANG_MAPS_1['integration_missing'] = '缺少插件';
$LANG_MAPS_1['integration_native'] = '原生支援';
$LANG_MAPS_1['integration_xmlsitemap'] = 'XML 站点地圖';
$LANG_MAPS_1['integration_documents'] = '文件';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'RSS / Atom 摘要';
$LANG_MAPS_1['use_my_location'] = '使用我的位置';
$LANG_MAPS_1['geolocation_unavailable'] = '瀏覽器地理定位不可用。请手動輸入起始地址。';
$LANG_MAPS_1['geolocation_denied'] = '无法取得您的位置。请允許位置存取或手動輸入起始地址。';
$LANG_MAPS_1['geolocation_https'] = '瀏覽器地理定位通常需要 HTTPS。';
?>
