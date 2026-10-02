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
    'plugin_name'           => '地图',
    'plugin_conf'           => '插件配置',
    '地图'                   => '地图',
    'need_google_api'       => '未配置 Google Maps 浏览器 API 密钥。添加此密钥之前无法显示 Google 地图。',
    'api_status_title' => 'Google Maps API 状态',
    'api_status_missing' => '未配置 Google Maps 浏览器 API 密钥。',
    'api_status_testing' => '正在测试 Google Maps JavaScript API…',
    'api_status_key' => '浏览器密钥',
    'api_status_ok' => 'Google Maps JavaScript API 已成功加载：浏览器密钥可用于此页面。',
    'api_status_auth' => 'Google Maps 拒绝了浏览器密钥或其配置。请在浏览器控制台查看 Google 的确切错误代码，然后检查 HTTP 引荐来源限制、已启用的 API 和 Google Cloud 结算。',
    'api_status_load' => '无法加载 Google Maps JavaScript API 脚本。请检查网络、CSP 策略、内容拦截器和浏览器控制台。',
    'api_status_timeout' => 'Google Maps JavaScript API 未响应。请检查浏览器控制台和网络面板。',
    'admin_help_title'      => '地图入门',
    'admin_help_intro'      => '地图插件可创建多个地图、添加标记，并在需要时使用自定义图标或叠加层。',
    'admin_help_google'     => '配置 Google Maps',
    'admin_help_google_1'   => '打开 Google Cloud Console，创建或选择项目，并为生产使用关联结算账号。',
    'admin_help_google_2'   => '至少启用 Maps JavaScript API。如果使用地址自动转换为经纬度，还应启用 Geocoding API。',
    'admin_help_google_3'   => '创建用于浏览器的 API 密钥。将其限制为授权网站（HTTP 引荐来源），例如 https://www.example.com/*，然后将此密钥限制为 Maps JavaScript API。',
    'admin_help_google_4'   => '对于服务器端地理编码，建议创建第二个密钥。将其限制为服务器 IP 地址并仅允许 Geocoding API。',
    'admin_help_google_5'   => '将浏览器密钥复制到 Geeklog 地图配置中的 Google Maps API 密钥，如使用服务器密钥，则复制到 Google Maps 服务器 API 密钥。',
    'admin_help_security'   => '生产环境中切勿让 Google Maps 密钥不受限制。分离浏览器和服务器密钥可降低未经授权使用的风险。',
    'admin_help_create'     => '创建第一个地图',
    'admin_help_create_1'   => '点击“创建新地图”，为其命名，并选择中心点、缩放级别和显示类型。',
    'admin_help_create_2'   => '保存地图，然后在地图管理中添加标记。每个标记可使用地址或精确坐标。',
    'admin_help_create_3'   => '图标和叠加层是可选的。先用简单地图和少量标记验证 Google Maps 配置。',
    'admin_help_trouble'    => '如果地图变灰或显示“仅供开发使用”，请检查 Google Cloud 结算、已启用的 API 和 API 密钥限制。',
    'admin_help_official'   => 'Google Maps Platform 官方文档',
    'admin_help_geo_title'  => '“检查用户地理位置”有什么作用？',
    'admin_help_geo_intro'  => '此命令扫描在个人资料中填写了“位置”字段的成员，并为用户地图准备坐标。',
    'admin_help_geo_1'      => '地图插件会将每个尚未解析的文本位置发送到 Google Geocoding API，例如“法国南特”。',
    'admin_help_geo_2'      => "返回的纬度和经度会缓存在地图地理编码表中，不会修改成员的 Geeklog 个人资料。",
    'admin_help_geo_3'      => '这主要用于安装、迁移之后，或许多成员新增或更改位置时。它需要 Geocoding API 以及允许服务器端地理编码的密钥。',
    'admin_help_overlays_title' => '叠加层有什么用途？',
    'admin_help_overlays_intro' => '叠加层是放置在 Google 地图上的地理参考图像，其范围由西南和东北坐标确定。它可添加 Google Maps 基础图层中没有的视觉信息。',
    'admin_help_overlays_1' => '在真实地图上显示场地、营地、公园、庄园、建筑或节庆平面图。',
    'admin_help_overlays_2' => '叠加历史、地籍、地质或旅游地图，或旧平面图，以便比较。',
    'admin_help_overlays_3' => '显示主题区域，例如图示路线、施工区、自然区域、项目范围或其他图形信息。',
    'admin_help_overlays_4' => '设置最小和最大缩放级别，使叠加层仅在有用时显示。',
    'admin_help_overlays_how' => '创建叠加层：准备合适的图像，打开“叠加层”，输入其西南和东北边界，然后在地图编辑器的“叠加层”标签中将其附加到地图。叠加层是可选的：普通点位地图只需标记即可。',
    'admin_help_concepts_title' => '地图概念一览',
    'admin_help_concept_map' => '地图',
    'admin_help_concept_map_text' => '主要容器：中心、缩放、显示类型、尺寸、权限和常规选项。',
    'admin_help_concept_marker' => '标记',
    'admin_help_concept_marker_text' => '地图上的地理点，包含名称、描述、地址和可选附加信息。',
    'admin_help_concept_icon' => '图标',
    'admin_help_concept_icon_text' => '用于替代标准 Google 标记的可选图像，以区分不同点位类别。',
    'admin_help_concept_users' => '用户地图',
    'admin_help_concept_users_text' => '根据 Geeklog 个人资料中的“位置”字段生成的地图。坐标由地图地理编码系统解析并缓存。',
    'admin_help_trouble_title' => '快速故障排查',
    'profile_title'         => '地理定位',
    'buy_marker'            => '购买标记',
    'menu_label'            => '地图管理',
    'admin_home'            => '首页', // In admin menu
    'user_home'             => '所有地图', //In user menu
    'maps'                  => '地图',
    '标记'               => '标记',
    'maps_label'            => '地图', // For user  menu
    'create_map'            => '创建新地图',
    'create_marker'         => '创建新标记',
    'map_edit'              => '编辑地图',
    'marker_edit'           => '编辑标记',
    'deletion_succes'       => '删除成功',
    'deletion_fail'         => '删除失败',
    'error'                 => '错误',
    'save_fail'             => '保存失败',
    'save_success'          => '保存成功',
    'missing_field'         => '缺少必填字段…',
    'geocoder'              => '地理编码器',
    'geocoder_text'         => '输入地址，然后拖动标记调整位置。每次地理编码或拖动后，纬度/经度会显示在信息窗口中。',
    'geocode_failed'         => '无法对地址进行地理编码。请检查 Google Maps API 密钥、Geocoding API 是否启用以及地址，然后重试。',
    'go'                    => '前往！',
    'name_label'            => '地图名称：',
    'marker_name_label'     => '标记名称：',
    'description_label'     => '描述：',
    'ok_button'             => '确定',
    'edit_button'           => '编辑',
    'save_button'           => '保存',
    'delete_button'         => '删除',
    'yes'                   => '是',
    'no'                    => '否',
    'required_field'        => '表示必填字段',
    'address_label'         => '地址：',
    'message'               => '消息',
    'general_settings'      => '常规设置',
    'map_width'             => '地图宽度（% 或 px，最小 550px）：',
    'map_height'             => '地图高度（仅 px，最小 350px）：',
    'map_zoom'              => '地图缩放（0-21）：',
    'map_type'              => '地图类型：',
    'active'                => '地图已启用：',
    'hidden'                => '地图已隐藏：',
    'marker_active'         => '标记已启用：',
    'marker_hidden'         => '标记已隐藏：',
    'free_marker'           => '地图接受免费标记：',
    'paid_marker'           => '地图接受付费标记：',
    'error_address_empty'   => '请先输入有效地址。',
    'error_invalid_address' => '此地址无效。请确保同时输入门牌号和城市。',
    'error_google_error'    => '处理请求时出现问题，请重试。',
    'error_no_map_info'     => '抱歉，此地址没有可用的地图信息。',
    'need_directions'       => '需要路线？请输入您的地址：',
    'directions_title'     => '规划路线',
    'directions_start'     => '起点',
    'get_directions'        => '  获取路线  ',
    'maps_list'             => '地图列表',
    'you_can'               => '您可以 ',
    'user_maps_list'        => '浏览我们的地图',
    'markers_list'          => '标记列表',
    'map_markers_heading'   => '此地图上的标记',
    'marker_singular'      => '标记',
    'marker_plural'        => '标记',
    'views_label'          => '浏览次数',
    'no_map'                => '数据库中没有地图。必须先创建地图才能添加标记。',
    'no_map_user'           => '哎呀… 数据库中没有启用的地图。',
    'value_directions'      => '例如：门牌号、街道、城市、国家', // No quote here please
    'id'                    => 'ID',
    'name'                  => '名称',
    'description'           => '描述',
    'active_field'          => '启用',
    'hidden_field'          => '隐藏',
    'marker_count'          => '标记',
    'status_active'         => '启用',
    'status_inactive'       => '未启用',
    'status_visible'        => '可见',
    'status_hidden'         => '隐藏',
    'title_display'         => '显示地图页面',
    'map_header_label'      => '可选地图页眉',
    'map_footer_label'      => '可选地图页脚',
    'header_footer'         => '页眉和页脚',
    'informations'          => '信息',
    'must_belong_to'        => '要访问此地图，您必须属于组：',
    'private_access'        => '私人访问',
    'marker_label'          => '标记',
    'primary_color_label'   => '主色',
    'stroke_color_label'    => '描边颜色',
    'label'                 => '标签',
    'label_color'           => '标签颜色',
    'black'                 => '黑色',
    'white'                 => '白色',
    'payed'                 => '付费标记：',
    'lat'                   => '纬度：',
    'lng'                   => '经度：',
    'ressources_tab'        => '资源标签页',
    'presentation'          => '展示',
    'ressources'            => '资源',
    'presentation_tab'      => '展示标签页',
    'empty_ressources'      => '资源标签为空。至少需要设置一个才能使用资源。请参阅配置。',
    'empty_for_geo'         => '如果需要根据上面的地址自动定位，请将纬度和经度留空。',
    'select_marker_map'     => '选择希望标记出现的地图。',
    'remark'                => '备注',
    'marker_created'        => '标记创建于：',
    'map_created'           => '地图创建于：',
    'modified'              => '最后修改：',
    'marker_validity'       => '使用有效期：',
    'maps_empty'            => '请先创建地图。',
    'from'                  => '从：',
    '到'                    => '到：',
    'date_issue'            => '结束有效期早于开始有效期，请检查。',
    'max_char'              => '最大字符数。',
    'street_label'          => '街道：',
    'code_label'            => '邮政编码：',
    'city_label'            => '城市：',
    'state_label'           => '州/地区：',
    'country_label'         => '国家：',
    'tel_label'             => '电话：',
    'fax_label'             => '其他联系方式：',
    'web_label'             => '网站：',
    'not_use_see_config'    => '不使用。请参阅配置',
    //global maps
    'global_map'            => '全局地图',
    'info_global_map'       => '这是所有地图的汇总。',
    'users_map'             => '网站用户地图',
    'info_users_map'        => '这是网站用户地图。您可以在个人资料中设置位置，将自己添加到地图。',
    //Submission
    'address'               => '地址',
    'created'               => '日期',
    'submit_marker'         => '提交标记',
    'submit_marker_text'    => '<p><ol><li>设置标记位置<li>填写所有字段<li>确认</ol></p>',
    'markers_submissions'   => '标记提交',
    'submission_disabled'   => '标记提交队列已禁用',
    'go'                    => '显示此地址',
    //date and hits
    'last_modification'     => '最后修改：',
    '访问次数'                  => '访问次数',
    //user marker
    'member'                => '成员',
    'location'              => '位置：',
    'regdate'               => '加入时间：',
    'about'                 => '关于',
    'my_markers'            => '我的标记',
    'payed_label'           => '付费',
    'from_label'            => '有效期开始',
    'to_label'              => '有效期结束',
    'no_marker'             => '您没有任何标记，或者标记尚未获批。如果认为有误，请联系网站管理员。',
    'marker_detail'         => '标记详情',
    'admin_can'             => '作为地图管理员，您可以',
    'create_map'            => '创建新地图',
    'set_user_geo'          => '设置用户地理位置',
    'set_geo_location'      => '系统将检查并设置所有地理位置。',
    '记录'               => '记录',
    'report'                => '举报此标记',
    'report_subject'        => '关于标记的举报 ',
    'edit_marker_text'      => '<p><ol><li>设置标记位置<li>填写所有必填字段<li>然后确认</ol></p>',
    'admin'                 => '管理',
    'category_label'        => '类别：',
    'choose_category'       => '-- 选择类别 --',
    'categories'            => '类别',
    'categories_list'       => '类别列表',
    'cat_edit'              => '编辑类别：',
    'cat_name_label'        => '类别名称：',
    'create_cat'            => '创建新类别',
    'field_list'            => '字段列表',
    'addfield'              => '添加字段',
    'field_name'            => '字段名称',
    'field_order'           => '顺序',
    'field_autotag'         => '自动标签',
    'field_rights'          => '权限',
    'field_edit'            => '编辑',
    'valid'                 => '有效',
    'editing_field'         => '编辑字段',
    'category'              => '类别',
    'map_label'             => '地图',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => '查看地图',
    'view_markers'          => '显示标记列表',
    'code'                  => '邮政编码',
    'city'                  => '城市',
    'viewing_markers'       => '显示标记列表',
    'details'               => '详情',
    'view_details'          => '查看详情',
    'print'                 => '打印',
	'to_complete'           => '待完成',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - 显示 id=XX 的地图。选项包括缩放级别（0 到 21）以及将地图中心设为 location。',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - 显示以地点名称或地址为中心的地图。可选参数：zoom、width 和 height。仍支持历史语法 [geo: map ...]。',
	'autotag_desc_marker'   => '[marker: xx] - 显示 id=XX 的标记',
	//v1.1
	'marker_customisation'  => '标记自定义',
	'mk_default'            => '使用默认标记',
	'overlays'              => '叠加层',
	'overlays_list'         => '叠加层列表',
	'create_overlay'        => '创建新叠加层',
	'edit_overlay_text'     => '编辑叠加层：',
	'overlay_edit'          => '编辑叠加层',
	'overlay_name_label'    => '叠加层名称：',
	'overlay_presentation'  => '叠加层是绑定到经纬度坐标的地图对象，因此拖动或缩放地图时会随之移动。叠加层表示添加到地图中的点、线或区域。这里可以将图像添加为叠加层。',
	'overlay_active'        => '此叠加层已启用：',
	'zoom_min_label'        => '最小缩放：',
	'zoom_max_label'        => '最大缩放：',
	'image_message'         => '从硬盘选择图像。',
	'image_replace'         => '上传新图像将替换当前图像：',
	'image'                 => '图像',
	'sw_lat'                => '西南纬度：',
    'sw_lng'                => '西南经度：',
	'ne_lat'                => '东北纬度：',
    'ne_lng'                => '东北经度：',
	'overlay_not_writable'  => '叠加层文件夹不可写。请先创建此文件夹并赋予写入权限，然后再使用此功能。',
	'map_tab'               => '地图',
	'overlays_tab'          => '叠加层',
	'add_overlay'           => '添加叠加层',
	'remove_overlay'        => '移除叠加层',
	'overlay_label'         => '叠加层',
	'import_export'         => '导入/导出',
	'import'                => '导入',
	'export'                => '导出',
	'select_file'           => '选择 .csv 文件',
	'import_message'        => '选择要添加标记的地图、硬盘上的 CSV 文件、数据分隔符以及要导入的字段。',
	'markers_added'         => '已添加到地图的标记：',
	'export_message'        => '选择要导出标记的地图、数据分隔符以及要导出的字段。',
	'no_marker_to_export'   => '抱歉，此地图没有可导出的标记。',
	'icons'                 => '图标',
	'icons_not_writable'    => '图标文件夹不可写。请先创建此文件夹并赋予写入权限，然后再使用此功能。',
	'icons_list'            => '图标列表',
	'create_icon'           => '创建新图标',
	'icon_edit'             => '编辑图标',
	'icon_presentation'     => '这里可以上传用于标记的新图标', 
	'icon_name_label'       => '图标名称',
	'xmarkers'              => '标记',
	'1marker'               => '标记',
	'choose_icon'           => '可以为此标记选择图标。优先图标显示在颜色选项之上。',
	'no_icon'               => '无图标',
	'no_custom_icons'        => '尚未注册自定义图标。',
	'manage_icons'           => '管理图标',
	'separator'             => '选择分隔符',
	'markers_to_add'        => '请检查所有字段/值对，并确认要将以下所有标记添加到地图：',
	'choose_fields_import'  => '选择导入字段',
	'choose_fields_export'  => '选择导出字段',
	'checkall'              => '全选',
    'import_step_1' => '准备导入',
    'import_step_1_text' => '选择目标地图、CSV 文件、分隔符和列顺序。',
    'import_step_2' => '检查数据',
    'import_step_2_text' => '地图插件会在写入任何内容之前验证、规范化并对各行进行地理编码。',
    'import_step_3' => '确认导入',
    'import_step_3_text' => '检查目标、所有者和权限，然后确认批次。',
    'import_minimum' => '最少字段',
    'import_minimum_help' => 'name + address，或 name + lat + lng。最少字段预设使用基于地址的方式。',
    'import_recommended' => '推荐字段',
    'import_recommended_help' => 'name、address、lat、lng、description、street、code、city、state、country、tel 和 web。',
    'import_order_help' => 'CSV 列必须与下面所选字段保持相同顺序。',
    'import_select_minimum' => '最少字段',
    'import_select_recommended' => '推荐字段',
    'import_clear_fields' => '清除选择',
    'import_preview_title' => '检查数据',
    'import_preview_text' => '确认导入后，将写入这些规范化值。',
    'import_summary_rows' => '行已就绪',
    'import_summary_coordinates' => '已提供坐标',
    'import_summary_geocoded' => '已自动地理编码',
    'import_summary_partial' => '包含部分地址详情',
    'import_status' => '状态',
    'import_status_ready' => '就绪',
    'import_status_partial' => '就绪 · 部分详情',
    'import_status_geocoded' => '已地理编码',
    'import_confirm_title' => '确认导入',
    'import_confirm_text' => '创建标记前请检查批次设置。',
    'import_confirm_button' => '导入 %d 个标记',
    'import_cancel_button' => '取消',
	'order'                 => '顺序',
	'move'                  => '移动',
	'name_missing'          => '至少缺少一个名称。请检查 CSV 文件。',
	'need_address'          => '创建标记至少需要地址或坐标。请检查 CSV 文件，存在缺失数据。',
	'manage_groups'         => '管理叠加层组',
	'create_group'          => '创建新叠加层组',
	'group_edit'            => '编辑叠加层组',
	'group_overlay_presentation' => '这里可以选择或编辑叠加层组的名称',
	'group_overlay_name_label'   => '组名称',
	'group_label'           => '组（可选）',
	'choose_group'          => '选择组',
	'group'                 => '组',
	
	//v1.3
	'geo_fail'              => '输入的地址似乎无效',
	'on_map'                => '在地图上',
	'read_more'             => '阅读更多',
	'from_map'              => '地图：',
	'show_hide_overlays'    => '显示 / 隐藏叠加层',
	'fields_presentation'   => '编辑现有类别以添加或编辑字段。',
	'overlays_added'        => '此地图上的叠加层',
	'overlays_to_add'       => '可添加到此地图的叠加层',
	'marker_modification'   => '修改标记',
	'from_owner'            => '添加者：',
	'marker_limited'        => '抱歉，此标记的访问受限…',
	'events_map'            => '近期活动地图',
	'info_events_map'       => '',
	'from_cal'              => '从',
	'to_cal'                => '到',
	'on_cal'                => '开',
    //v1.4
    'admin_menu_maps' => '地图',
    'admin_menu_markers' => '标记',
    'admin_menu_icons' => '图标',
    'admin_menu_overlays' => '叠加层',
    'admin_menu_import_export' => '导入/导出',
    'admin_menu_geocoder' => '地理编码器',
    'admin_menu_geolocation' => '地理位置',
    'admin_menu_configuration' => '配置',
    'section_location' => '位置',
    'section_content_contact' => '内容和联系方式',
    'section_appearance' => '外观',
    'section_publication' => '发布',
    'section_resources' => '资源',
    'section_ownership' => '所有者',
    'section_permissions' => '权限',
    'delete_confirm' => '永久删除此标记？',
    'marker_not_found' => '未找到标记，或您没有查看权限。',
    'delete_map_confirm' => '永久删除此地图？',
    'technical_coordinates' => '技术坐标',
    'configuration'         => '配置',
    // Maps 1.5.6 map editor
    'map_section_display' => '显示',
    'map_section_center' => '中心和缩放',
    'map_section_markers' => '标记',
    'map_section_advanced' => '高级选项',
    'map_center_search' => '搜索地址',
    'map_center_search_button' => '定位',
    'map_center_use_button' => '使用显示的中心',
    'map_center_help' => '搜索地址，点击地图或拖动标记以精确选择地图中心。',
    'latitude_label' => '纬度',
    'longitude_label' => '经度',
    'map_center_marker' => '地图中心',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => '系统消息',
    'add_new_field'         => '新字段已成功创建',
    'save_field'            => '字段已成功保存',
    'delete_field'          => '字段已成功删除'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => '管理员您好，',
    'new_marker'            => '有一个新标记等待审核。',
    'name'                  => '名称：',
    'on_map'                => '所在地图：',
    'submissions'           => '提交：',
    'marker_submissions'    => '标记提交',
	'marker_modification'   => '修改标记',
	'description'           => '描述：',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "感谢您向 {$_CONF['site_name']} 提交标记。该标记已提交给工作人员审核。";
$PLG_maps_MESSAGE2  = "标记提交已关闭。";
$PLG_maps_MESSAGE3  = "哎呀… 发生错误，无法保存您的标记。";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => '地图',
    'title' => '地图配置'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => '隐藏地图菜单',
    'maps_login_required'   => '地图需要登录',
    'autofill_coord'        => '自动填写未定义坐标',
    'display_geo_profile'   => '个人资料地理定位',
    'map_type_profile'      => '个人资料地图类型',
    'map_type_geotag'       => 'geo 自动标签地图类型',
    'show_directions_geo'   => 'geo 自动标签显示路线',
    'show_directions_profile' => '个人资料显示路线',
    'map_width_geotag'      => 'geo 自动标签地图宽度（% 或 px）',
    'map_height_geotag'     => 'geo 自动标签地图高度（仅 px）',
    'map_zoom_geotag'       => 'geo 自动标签缩放（0-21）',
    'map_width_profile'     => '个人资料地图宽度（% 或 px）',
    'map_height_profile'    => '个人资料地图高度（仅 px）',
    'show_map'              => '显示 Google 地图',
    'google_api_key'        => 'Google Maps API 密钥',
    'url_geocode'           => 'Google Geocoding 服务 URL',
    'map_width'             => '默认地图宽度（% 或 px）',
    'map_height'            => '默认地图高度（仅 px）',
    'map_zoom'              => '默认地图缩放（0-21）',
    'map_type'              => '默认地图类型',
    'default_permissions'   => '默认权限',
    'map_main_header'       => '主页页眉，autotag welcome',
    'map_main_footer'       => '主页页脚，也支持 autotag welcome',
    'map_geo'               => '创建包含所有个人资料的地图',
    'map_markers'           => '创建包含所有标记的地图',
    'map_active'            => '地图已启用',
    'map_hidden'            => '地图已隐藏',
    'free_markers'          => '地图接受免费标记',
    'paid_markers'          => '地图接受付费标记（需要 PayPal 插件）',
    'street'                => '使用街道信息',
    'code'                  => '使用邮政编码信息',
    'city'                  => '使用城市信息',
    'state'                 => '使用州/地区信息',
    'country'               => '使用国家信息',
    'tel'                   => '使用电话信息',
    'fax'                   => '使用其他联系方式',
    'web'                   => '使用网站信息',
    'item_1'                => '自定义字段 1 标签',
    'item_2'                => '自定义字段 2 标签',
    'item_3'                => '自定义字段 3 标签',
    'item_4'                => '自定义字段 4 标签',
    'item_5'                => '自定义字段 5 标签',
    'item_6'                => '自定义字段 6 标签',
    'item_7'                => '自定义字段 7 标签',
    'item_8'                => '自定义字段 8 标签',
    'item_9'                => '自定义字段 9 标签',
    'item_10'               => '自定义字段 10 标签',
    'label_color'           => '标签颜色',
    'star_primary_color'    => '星形主色',
    'star_stroke_color'     => '星形描边颜色',
    'marker_active'         => '标记默认启用',
    'marker_hidden'         => '标记默认隐藏',
    'marker_payed'          => '标记默认付费',
    'marker_validity'       => '标记默认有效期',
    'marker_submission'     => '允许提交标记',
    'users_map'             => '启用网站用户地图',
    'global_map' 	        => '启用全局地图',
    'global_type'           => '全局地图类型',	
    'global_width'  	    => '全局地图宽度',
    'global_height' 	    => '全局地图高度',
    'global_zoom'           => '全局地图缩放（0-21）',
    'detail_zoom'           => '标记详情缩放（0-21）',
    'submit_login_required' => '提交标记需要登录',
    'marker_edition'        => '编辑标记',
	'use_cluster'           => '使用标记聚合',
	'zoom_profile'          => '用户个人资料地图缩放（0-21）',
	'display_events_map'    => '显示活动地图',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => '主要设置',
    'sg_display' => '显示设置'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => '常规',
    'tab_google' => 'Google Maps',
    'tab_maps' => '地图',
    'tab_markers' => '标记',
    'tab_fields' => '标记字段',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => '常规',
    'tab_google' => 'Google Maps',
    'tab_maps' => '地图',
    'tab_markers' => '标记',
    'tab_fields' => '标记字段',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => '常规设置',
    'fs_ads'             => 'Google Ads 设置',
    'fs_google'          => 'Google API 设置',
    'fs_permissions'     => '默认权限',
    'fs_display'         => '地图',
    'fs_global_map'      => '全局地图',
    'fs_display_profile' => '个人资料',
    'fs_display_geo'     => 'geo 自动标签',
    'fs_map_default'     => '地图默认设置',
    'fs_marker_default'  => '标记默认设置',
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
    5 => array('页面顶部' => 1, '精选文章下方' => 2, '页面底部' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('英里' => '英里', '公里' => 'km'),
    12 => array('无访问权限' => 0, '只读' => 2, '读写' => 3),
	// changed in v1.3
    20 => array('普通街道地图' => 'ROADMAP', '卫星图像' => 'SATELLITE', '地形图' => 'TERRAIN', '卫星图像上的主要街道透明图层' => 'HYBRID'),
    30 => array('白色' => 1, '黑色' => 0),
    31 => array('临时' => 1, '永久' => 0),
);

$LANG_MAPS_1['location_search_label'] = '搜索地址';
$LANG_MAPS_1['location_search_help'] = '搜索地址，点击地图或拖动标记以精细调整位置。';
$LANG_MAPS_1['use_map_click_help'] = '点击地图移动标记。';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = '主要';
$LANG_configtabs['maps']['tab_general'] = '常规';
$LANG_tab['maps']['tab_general'] = '常规';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = '地图';
$LANG_tab['maps']['tab_maps'] = '地图';
$LANG_configtabs['maps']['tab_markers'] = '标记';
$LANG_tab['maps']['tab_markers'] = '标记';
$LANG_configtabs['maps']['tab_fields'] = '标记字段';
$LANG_tab['maps']['tab_fields'] = '标记字段';

$LANG_fs['maps']['fs_main'] = '访问和功能';
$LANG_fs['maps']['fs_permissions'] = '默认权限';
$LANG_fs['maps']['fs_uploads'] = '图像和上传';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = '常规显示';
$LANG_fs['maps']['fs_global_map'] = '全局地图和用户地图';
$LANG_fs['maps']['fs_display_profile'] = '用户个人资料地图';
$LANG_fs['maps']['fs_display_geo'] = 'Geo 自动标签';
$LANG_fs['maps']['fs_map_defaults'] = '新地图默认设置';
$LANG_fs['maps']['fs_events_map'] = '活动地图';
$LANG_fs['maps']['fs_marker_defaults'] = '标记默认设置';
$LANG_fs['maps']['fs_marker_editor'] = '标记编辑器地图';
$LANG_fs['maps']['fs_marker_detail'] = '标记详情地图';
$LANG_fs['maps']['fs_marker_popup'] = '标记信息窗口';
$LANG_fs['maps']['fs_marker_fields'] = '标记字段和标签';

$LANG_confignames['maps']['max_image_width'] = '最大图像宽度（px）';
$LANG_confignames['maps']['max_image_height'] = '最大图像高度（px）';
$LANG_confignames['maps']['max_image_size'] = '最大图像大小（字节）';
$LANG_confignames['maps']['google_api_key'] = 'Google Maps 浏览器 API 密钥';
$LANG_confignames['maps']['google_server_api_key'] = 'Google Geocoding 服务器 API 密钥';
$LANG_confignames['maps']['google_map_id'] = 'Google 地图 ID（为 Advanced Markers 准备）';
$LANG_confignames['maps']['google_language'] = 'Google Maps 语言（可选，例如 zh-CN）';
$LANG_confignames['maps']['google_region'] = 'Google Maps 地区（可选，例如 CN）';
$LANG_confignames['maps']['url_geocode'] = 'Google Geocoding 服务 URL';
$LANG_confignames['maps']['map_primary_color'] = '默认地图主色';
$LANG_confignames['maps']['map_stroke_color'] = '默认地图描边颜色';
$LANG_confignames['maps']['map_label'] = '默认地图标记标签';
$LANG_confignames['maps']['map_label_color'] = '默认地图标签颜色';
$LANG_confignames['maps']['events_map_zoom'] = '活动地图缩放';
$LANG_confignames['maps']['events_map_height'] = '活动地图高度';
$LANG_confignames['maps']['users_map_lat'] = '用户地图中心纬度（空白 = 自动）';
$LANG_confignames['maps']['users_map_lng'] = '用户地图中心经度（空白 = 自动）';
$LANG_confignames['maps']['users_map_zoom'] = '用户地图缩放（空白 = 全局地图）';
$LANG_confignames['maps']['users_map_type'] = '用户地图类型（空白 = 全局地图）';
$LANG_confignames['maps']['users_map_width'] = '用户地图宽度（空白 = 全局地图）';
$LANG_confignames['maps']['users_map_height'] = '用户地图高度（空白 = 全局地图）';
$LANG_confignames['maps']['marker_editor_type'] = '标记编辑器地图类型';
$LANG_confignames['maps']['marker_editor_zoom'] = '标记编辑器初始缩放';
$LANG_confignames['maps']['marker_editor_width'] = '标记编辑器地图宽度';
$LANG_confignames['maps']['marker_editor_height'] = '标记编辑器地图高度';
$LANG_confignames['maps']['detail_width'] = '标记详情地图宽度';
$LANG_confignames['maps']['detail_height'] = '标记详情地图高度';
$LANG_confignames['maps']['detail_zoom'] = '标记详情地图缩放';
$LANG_confignames['maps']['popup_width'] = '信息窗口宽度';
$LANG_confignames['maps']['popup_height'] = '信息窗口高度';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = '入口页面 SEO';
$LANG_confignames['maps']['maps_page_title'] = '地图入口页面 SEO 标题';
$LANG_confignames['maps']['maps_page_h1'] = '地图入口页面 H1 标题';
$LANG_confignames['maps']['maps_meta_description'] = '地图入口页面元描述';
$LANG_confignames['maps']['map_main_header'] = '地图入口页面介绍内容（支持自动标签）';

$LANG_MAPS_1['server_geocode_key_missing'] = '服务器端坐标查询已启用，但未配置专用的 Google Geocoding 服务器 API 密钥。浏览器密钥绝不会用于服务器端地理编码。';
$LANG_MAPS_1['api_diag_title'] = 'Google Maps Platform 配置';
$LANG_MAPS_1['api_diag_maps_js'] = 'Maps JavaScript API';
$LANG_MAPS_1['api_diag_geocoding'] = 'Geocoding API';
$LANG_MAPS_1['api_diag_directions'] = 'Directions API';
$LANG_MAPS_1['api_diag_browser_key'] = '浏览器 API 密钥';
$LANG_MAPS_1['api_diag_server_key'] = '服务器 API 密钥';
$LANG_MAPS_1['api_diag_map_id'] = '地图 ID';
$LANG_MAPS_1['api_diag_configured'] = '密钥已配置 — API 未验证';
$LANG_MAPS_1['api_diag_browser_verify'] = '密钥已配置 — 请使用下面的浏览器测试进行验证';
$LANG_MAPS_1['api_diag_referrer_hint'] = '对于浏览器密钥的 HTTP 引荐来源限制，请授权此网站（例如：%s/*）。';
$LANG_MAPS_1['api_diag_missing'] = '缺少';
$LANG_MAPS_1['api_diag_optional'] = '可选 / 未配置';
$LANG_MAPS_1['integrations_title'] = '集成';
$LANG_MAPS_1['integrations_intro'] = '地图插件使用 Geeklog API 和服务，使相关插件无需直接耦合数据库即可发现地图功能。';
$LANG_MAPS_1['integration_active'] = '启用';
$LANG_MAPS_1['integration_missing'] = '缺少插件';
$LANG_MAPS_1['integration_native'] = '原生支持';
$LANG_MAPS_1['integration_xmlsitemap'] = 'XML 站点地图';
$LANG_MAPS_1['integration_documents'] = '文档';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'RSS / Atom 订阅源';
$LANG_MAPS_1['use_my_location'] = '使用我的位置';
$LANG_MAPS_1['geolocation_unavailable'] = '浏览器地理定位不可用。请手动输入起始地址。';
$LANG_MAPS_1['geolocation_denied'] = '无法获取您的位置。请允许位置访问或手动输入起始地址。';
$LANG_MAPS_1['geolocation_https'] = '浏览器地理定位通常需要 HTTPS。';
?>
