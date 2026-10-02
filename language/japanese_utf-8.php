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
    'plugin_name'           => 'マップ',
    'plugin_conf'           => 'プラグイン設定',
    'map'                   => 'マップ',
    'need_google_api'       => 'Google Maps のブラウザー API キーが設定されていません。このキーを追加するまで Google マップを表示できません。',
    'api_status_title' => 'Google Maps API の状態',
    'api_status_missing' => 'Google Maps のブラウザー API キーが設定されていません。',
    'api_status_testing' => 'Google Maps JavaScript API をテストしています…',
    'api_status_key' => 'ブラウザーキー',
    'api_status_ok' => 'Google Maps JavaScript API を正常に読み込みました。ブラウザーキーはこのページで有効です。',
    'api_status_auth' => 'Google Maps がブラウザーキーまたはその設定を拒否しました。ブラウザーコンソールで Google の正確なエラーコードを確認し、HTTP リファラー制限、有効な API、Google Cloud の請求設定を確認してください。',
    'api_status_load' => 'Google Maps JavaScript API スクリプトを読み込めませんでした。ネットワーク、CSP ポリシー、コンテンツブロッカー、ブラウザーコンソールを確認してください。',
    'api_status_timeout' => 'Google Maps JavaScript API が応答しませんでした。ブラウザーコンソールとネットワークパネルを確認してください。',
    'admin_help_title'      => 'マップを使い始める',
    'admin_help_intro'      => 'マップでは複数の地図を作成し、マーカーを追加し、必要に応じてカスタムアイコンやオーバーレイを使用できます。',
    'admin_help_google'     => 'Google Maps を設定',
    'admin_help_google_1'   => 'Google Cloud Console を開き、プロジェクトを作成または選択し、本番利用のために請求先アカウントを関連付けます。',
    'admin_help_google_2'   => '少なくとも Maps JavaScript API を有効にしてください。住所を緯度・経度へ自動変換する場合は Geocoding API も有効にします。',
    'admin_help_google_3'   => 'ブラウザー用 API キーを作成します。許可した Web サイト（HTTP リファラー）、たとえば https://www.example.com/* に制限し、さらに Maps JavaScript API のみに制限します。',
    'admin_help_google_4'   => 'サーバー側ジオコーディングには 2 つ目のキーを作成することを推奨します。サーバーの IP アドレスと Geocoding API のみに制限してください。',
    'admin_help_google_5'   => 'ブラウザーキーを Geeklog マップ設定の Google Maps API キーへ、使用する場合はサーバーキーを Google Maps サーバー API キーへコピーします。',
    'admin_help_security'   => '本番環境では Google Maps キーを無制限のままにしないでください。ブラウザー用とサーバー用を分けることで不正利用のリスクを減らせます。',
    'admin_help_create'     => '最初のマップを作成',
    'admin_help_create_1'   => '「新しいマップを作成」をクリックし、名前を付けて中心、ズームレベル、表示タイプを選択します。',
    'admin_help_create_2'   => 'マップを保存し、マップ管理からマーカーを追加します。各マーカーには住所または正確な座標を使用できます。',
    'admin_help_create_3'   => 'アイコンとオーバーレイは任意です。まず簡単なマップと少数のマーカーで Google Maps の設定を確認してください。',
    'admin_help_trouble'    => 'マップが灰色表示になったり「開発目的のみ」と表示されたりする場合は、Google Cloud の請求、有効な API、API キー制限を確認してください。',
    'admin_help_official'   => 'Google Maps Platform 公式ドキュメント',
    'admin_help_geo_title'  => '「ユーザーの位置情報を確認」とは何ですか？',
    'admin_help_geo_intro'  => 'このコマンドはプロフィールの「場所」フィールドを入力したメンバーを確認し、ユーザーマップ用の座標を準備します。',
    'admin_help_geo_1'      => 'マップは未解決のテキスト位置を Google Geocoding API に送信します。例:「フランス、ナント」。',
    'admin_help_geo_2'      => "返された緯度と経度はマップのジオコーディングテーブルにキャッシュされます。メンバーの Geeklog プロフィールは変更されません。",
    'admin_help_geo_3'      => '主にインストールや移行後、または多くのメンバーが場所を追加・変更した場合に便利です。Geocoding API とサーバー側ジオコーディングを許可したキーが必要です。',
    'admin_help_overlays_title' => 'オーバーレイは何に使いますか？',
    'admin_help_overlays_intro' => 'オーバーレイは南西・北東の座標範囲で Google マップ上に配置する地理参照画像です。Google Maps のベースレイヤーにない視覚情報を追加します。',
    'admin_help_overlays_1' => '現実の地図上に敷地、キャンプ場、公園、不動産、建物、フェスティバルの配置図を表示します。',
    'admin_help_overlays_2' => '比較用に歴史地図、地籍図、地質図、観光地図、古い図面を重ねます。',
    'admin_help_overlays_3' => '図示ルート、作業区域、自然区域、プロジェクト範囲などのテーマ領域やその他の図形情報を表示します。',
    'admin_help_overlays_4' => '最小・最大ズームレベルを設定し、必要なときだけオーバーレイを表示します。',
    'admin_help_overlays_how' => '作成するには、適切な画像を用意し、オーバーレイを開いて南西・北東の境界を入力し、マップエディターのオーバーレイタブからマップに関連付けます。オーバーレイは任意で、通常のポイントマップならマーカーだけで十分です。',
    'admin_help_concepts_title' => 'マップの基本概念',
    'admin_help_concept_map' => 'マップ',
    'admin_help_concept_map_text' => '中心、ズーム、表示タイプ、サイズ、権限、一般オプションを持つ主要コンテナーです。',
    'admin_help_concept_marker' => 'マーカー',
    'admin_help_concept_marker_text' => '名前、説明、住所、任意の追加情報を持つ、マップ上の地理的ポイントです。',
    'admin_help_concept_icon' => 'アイコン',
    'admin_help_concept_icon_text' => 'ポイントのカテゴリを区別するために標準 Google マーカーを置き換える任意の画像です。',
    'admin_help_concept_users' => 'ユーザーマップ',
    'admin_help_concept_users_text' => 'Geeklog プロフィールの「場所」フィールドから生成されるマップです。座標はマップのジオコーディングシステムで解決・キャッシュされます。',
    'admin_help_trouble_title' => '簡単なトラブルシューティング',
    'profile_title'         => '位置情報',
    'buy_marker'            => 'マーカーを購入',
    'menu_label'            => 'マップ管理',
    'admin_home'            => 'ホーム', // In admin menu
    'user_home'             => 'すべてのマップ', //In user menu
    'maps'                  => 'マップ',
    'markers'               => 'マーカー',
    'maps_label'            => 'マップ', // For user  menu
    'create_map'            => '新しいマップを作成',
    'create_marker'         => '新しいマーカーを作成',
    'map_edit'              => 'マップを編集',
    'marker_edit'           => 'マーカーを編集',
    'deletion_succes'       => '削除しました',
    'deletion_fail'         => '削除に失敗しました',
    'error'                 => 'エラー',
    'save_fail'             => '保存に失敗しました',
    'save_success'          => '保存しました',
    'missing_field'         => '必須フィールドがありません…',
    'geocoder'              => 'ジオコーダー',
    'geocoder_text'         => '住所を入力し、マーカーをドラッグして位置を調整します。ジオコードやドラッグのたびに情報ウィンドウへ緯度・経度が表示されます。',
    'geocode_failed'         => '住所をジオコードできませんでした。Google Maps API キー、Geocoding API の有効化、住所を確認して再試行してください。',
    'go'                    => '実行！',
    'name_label'            => 'マップ名: ',
    'marker_name_label'     => 'マーカー名: ',
    'description_label'     => '説明:',
    'ok_button'             => 'OK',
    'edit_button'           => '編集',
    'save_button'           => '保存',
    'delete_button'         => '削除',
    'yes'                   => 'はい',
    'no'                    => 'いいえ',
    'required_field'        => '必須フィールドを示します',
    'address_label'         => '住所: ',
    'message'               => 'メッセージ',
    'general_settings'      => '一般設定',
    'map_width'             => 'マップ幅（% または px、最小 550px）: ',
    'map_height'             => 'マップ高さ（px のみ、最小 350px）: ',
    'map_zoom'              => 'マップズーム（0-21）: ',
    'map_type'              => 'マップタイプ: ',
    'active'                => 'マップを有効化: ',
    'hidden'                => 'マップを非表示: ',
    'marker_active'         => 'マーカーを有効化: ',
    'marker_hidden'         => 'マーカーを非表示: ',
    'free_marker'           => '無料マーカーを許可: ',
    'paid_marker'           => '有料マーカーを許可: ',
    'error_address_empty'   => '先に有効な住所を入力してください。',
    'error_invalid_address' => 'この住所は無効です。番地と市区町村も入力してください。',
    'error_google_error'    => 'リクエストの処理中に問題が発生しました。再試行してください。',
    'error_no_map_info'     => 'この住所のマップ情報は利用できません。',
    'need_directions'       => '経路が必要ですか？住所を入力してください:',
    'directions_title'     => '経路を計画',
    'directions_start'     => '出発地点',
    'get_directions'        => '  経路を取得  ',
    'maps_list'             => 'マップ一覧',
    'you_can'               => '次の操作ができます: ',
    'user_maps_list'        => 'マップを見る',
    'markers_list'          => 'マーカー一覧',
    'map_markers_heading'   => 'このマップ上のマーカー',
    'marker_singular'      => 'マーカー',
    'marker_plural'        => 'マーカー',
    'views_label'          => '閲覧',
    'no_map'                => 'データベースにマップがありません。マーカーを追加するにはマップを作成する必要があります。',
    'no_map_user'           => 'データベースに有効なマップがありません。',
    'value_directions'      => '例: 番地、通り、市、国', // No quote here please
    'id'                    => 'ID',
    'name'                  => '名前',
    'description'           => '説明',
    'active_field'          => '有効',
    'hidden_field'          => '非表示',
    'marker_count'          => 'マーカー',
    'status_active'         => '有効',
    'status_inactive'       => '無効',
    'status_visible'        => '表示',
    'status_hidden'         => '非表示',
    'title_display'         => 'マップページを表示',
    'map_header_label'      => '任意のマップヘッダー',
    'map_footer_label'      => '任意のマップフッター',
    'header_footer'         => 'ヘッダーとフッター',
    'informations'          => '情報',
    'must_belong_to'        => 'このマップへアクセスするには次のグループに所属する必要があります:',
    'private_access'        => '限定アクセス',
    'marker_label'          => 'マーカー',
    'primary_color_label'   => '主要色',
    'stroke_color_label'    => '線の色',
    'label'                 => 'ラベル',
    'label_color'           => 'ラベル色',
    'black'                 => '黒',
    'white'                 => '白',
    'payed'                 => '有料マーカー:',
    'lat'                   => '緯度:',
    'lng'                   => '経度:',
    'ressources_tab'        => 'リソースタブ',
    'presentation'          => 'プレゼンテーション',
    'ressources'            => 'リソース',
    'presentation_tab'      => 'プレゼンテーションタブ',
    'empty_ressources'      => 'リソースのラベルが空です。リソースを使用するには少なくとも 1 つ設定してください。設定画面を確認してください。',
    'empty_for_geo'         => '上の住所から自動で位置情報を取得する場合は、緯度と経度を空欄にしてください。',
    'select_marker_map'     => 'マーカーを表示するマップを選択してください。',
    'remark'                => 'メモ',
    'marker_created'        => 'マーカー作成日:',
    'map_created'           => 'マップ作成日:',
    'modified'              => '最終更新:',
    'marker_validity'       => '有効期限を使用:',
    'maps_empty'            => '先にマップを作成してください。',
    'from'                  => '開始:',
    'to'                    => '終了:',
    'date_issue'            => '有効期限の終了が開始より前です。確認してください。',
    'max_char'              => '文字まで。',
    'street_label'          => '住所:',
    'code_label'            => '郵便番号:',
    'city_label'            => '市区町村:',
    'state_label'           => '都道府県/州:',
    'country_label'         => '国:',
    'tel_label'             => '電話:',
    'fax_label'             => '追加連絡先:',
    'web_label'             => 'Web:',
    'not_use_see_config'    => '使用しない。設定を参照',
    //global maps
    'global_map'            => 'グローバルマップ',
    'info_global_map'       => 'すべてのマップを 1 つにまとめたものです。',
    'users_map'             => 'サイトユーザーのマップ',
    'info_users_map'        => 'サイトユーザーのマップです。プロフィールに場所を設定すると自分を追加できます。',
    //Submission
    'address'               => '住所',
    'created'               => '日付',
    'submit_marker'         => 'マーカーを送信',
    'submit_marker_text'    => '<p><ol><li>マーカーの位置を設定<li>すべてのフィールドを入力<li>確認</ol></p>',
    'markers_submissions'   => 'マーカー送信',
    'submission_disabled'   => 'マーカー送信キューは無効です',
    'go'                    => 'この住所を表示',
    //date and hits
    'last_modification'     => '最終更新:',
    'hits'                  => 'ヒット',
    //user marker
    'member'                => 'メンバー',
    'location'              => '場所: ',
    'regdate'               => '登録日: ',
    'about'                 => '概要',
    'my_markers'            => '自分のマーカー',
    'payed_label'           => '有料',
    'from_label'            => '有効開始',
    'to_label'              => '有効終了',
    'no_marker'             => 'マーカーがないか、まだ承認されていません。誤りと思われる場合はサイト管理者に連絡してください。',
    'marker_detail'         => 'マーカー詳細',
    'admin_can'             => 'マップ管理者として次の操作ができます',
    'create_map'            => '新しいマップを作成',
    'set_user_geo'          => 'ユーザー位置情報を設定',
    'set_geo_location'      => 'システムがすべての位置情報を確認して設定します。',
    'records'               => '件',
    'report'                => 'このマーカーを報告',
    'report_subject'        => 'マーカーに関する報告 ',
    'edit_marker_text'      => '<p><ol><li>マーカーの位置を設定<li>すべての必須フィールドを入力<li>確認</ol></p>',
    'admin'                 => '管理',
    'category_label'        => 'カテゴリ:',
    'choose_category'       => '-- カテゴリを選択 --',
    'categories'            => 'カテゴリ',
    'categories_list'       => 'カテゴリ一覧',
    'cat_edit'              => 'カテゴリ編集:',
    'cat_name_label'        => 'カテゴリ名:',
    'create_cat'            => '新しいカテゴリを作成',
    'field_list'            => 'フィールド一覧',
    'addfield'              => 'フィールドを追加',
    'field_name'            => 'フィールド名',
    'field_order'           => '順序',
    'field_autotag'         => 'オートタグ',
    'field_rights'          => '権限',
    'field_edit'            => '編集',
    'valid'                 => '有効',
    'editing_field'         => 'フィールドを編集',
    'category'              => 'カテゴリ',
    'map_label'             => 'マップ',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => 'マップを表示',
    'view_markers'          => 'マーカー一覧を表示',
    'code'                  => '郵便番号',
    'city'                  => '市区町村',
    'viewing_markers'       => 'マーカー一覧を表示',
    'details'               => '詳細',
    'view_details'          => '詳細を表示',
    'print'                 => '印刷',
	'to_complete'           => '要入力',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - id=XX のマップを表示します。オプションはズームレベル（0～21）と location を中心にする指定です。',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - 場所名または住所を中心にマップを表示します。任意パラメーター: zoom、width、height。従来の [geo: map ...] 構文もサポートされます。',
	'autotag_desc_marker'   => '[marker: xx] - id=XX のマーカーを表示します',
	//v1.1
	'marker_customisation'  => 'マーカーのカスタマイズ',
	'mk_default'            => '標準マーカーを使用',
	'overlays'              => 'オーバーレイ',
	'overlays_list'         => 'オーバーレイ一覧',
	'create_overlay'        => '新しいオーバーレイを作成',
	'edit_overlay_text'     => 'オーバーレイを編集:',
	'overlay_edit'          => 'オーバーレイを編集',
	'overlay_name_label'    => 'オーバーレイ名:',
	'overlay_presentation'  => 'オーバーレイは緯度・経度座標に結び付いたマップ上のオブジェクトで、マップをドラッグまたはズームすると一緒に移動します。ポイント、線、区域を示すためにマップへ追加するオブジェクトです。ここでは画像をオーバーレイとして追加できます。',
	'overlay_active'        => 'このオーバーレイは有効です:',
	'zoom_min_label'        => '最小ズーム:',
	'zoom_max_label'        => '最大ズーム:',
	'image_message'         => 'ディスクから画像を選択してください。',
	'image_replace'         => '新しい画像をアップロードすると現在の画像が置き換わります:',
	'image'                 => '画像',
	'sw_lat'                => '南西の緯度:',
    'sw_lng'                => '南西の経度:',
	'ne_lat'                => '北東の緯度:',
    'ne_lng'                => '北東の経度:',
	'overlay_not_writable'  => 'オーバーレイフォルダーに書き込めません。この機能を使う前にフォルダーを作成し、書き込み可能にしてください。',
	'map_tab'               => 'マップ',
	'overlays_tab'          => 'オーバーレイ',
	'add_overlay'           => 'オーバーレイを追加',
	'remove_overlay'        => 'オーバーレイを削除',
	'overlay_label'         => 'オーバーレイ',
	'import_export'         => 'インポート/エクスポート',
	'import'                => 'インポート',
	'export'                => 'エクスポート',
	'select_file'           => '.csv ファイルを選択',
	'import_message'        => 'マーカーを追加するマップ、ディスク上の CSV ファイル、データの区切り文字、インポートするフィールドを選択してください。',
	'markers_added'         => 'マップに追加されたマーカー:',
	'export_message'        => 'マーカーをエクスポートするマップ、データの区切り文字、エクスポートするフィールドを選択してください。',
	'no_marker_to_export'   => 'このマップにはエクスポートできるマーカーがありません。',
	'icons'                 => 'アイコン',
	'icons_not_writable'    => 'アイコンフォルダーに書き込めません。この機能を使う前にフォルダーを作成し、書き込み可能にしてください。',
	'icons_list'            => 'アイコン一覧',
	'create_icon'           => '新しいアイコンを作成',
	'icon_edit'             => 'アイコンを編集',
	'icon_presentation'     => 'ここでマーカー用の新しいアイコンをアップロードできます', 
	'icon_name_label'       => 'アイコン名',
	'xmarkers'              => 'マーカー',
	'1marker'               => 'マーカー',
	'choose_icon'           => 'このマーカーにアイコンを選択できます。優先アイコンは色より上に表示されます。',
	'no_icon'               => 'アイコンなし',
	'no_custom_icons'        => 'カスタムアイコンはまだ登録されていません。',
	'manage_icons'           => 'アイコンを管理',
	'separator'             => '区切り文字を選択',
	'markers_to_add'        => 'すべてのフィールド/値の組み合わせを確認し、以下のマーカーをすべてマップに追加することを確認してください:',
	'choose_fields_import'  => 'インポートするフィールドを選択',
	'choose_fields_export'  => 'エクスポートするフィールドを選択',
	'checkall'              => 'すべて選択',
    'import_step_1' => 'インポートを準備',
    'import_step_1_text' => '対象マップ、CSV ファイル、区切り文字、列順を選択します。',
    'import_step_2' => 'データを確認',
    'import_step_2_text' => 'マップは書き込み前に各行を検証、正規化、ジオコードします。',
    'import_step_3' => 'インポートを確認',
    'import_step_3_text' => '対象、所有者、権限を確認してからバッチを確定します。',
    'import_minimum' => '最小フィールド',
    'import_minimum_help' => 'name + address、または name + lat + lng。最小プリセットは住所ベースの方式を使用します。',
    'import_recommended' => '推奨フィールド',
    'import_recommended_help' => 'name、address、lat、lng、description、street、code、city、state、country、tel、web。',
    'import_order_help' => 'CSV 列は下に表示される選択フィールドと同じ順序にする必要があります。',
    'import_select_minimum' => '最小フィールド',
    'import_select_recommended' => '推奨フィールド',
    'import_clear_fields' => '選択を解除',
    'import_preview_title' => 'データを確認',
    'import_preview_text' => 'インポートを確定すると、これらの正規化された値が書き込まれます。',
    'import_summary_rows' => '行が準備完了',
    'import_summary_coordinates' => '座標あり',
    'import_summary_geocoded' => '自動ジオコード済み',
    'import_summary_partial' => '住所詳細が一部のみ',
    'import_status' => '状態',
    'import_status_ready' => '準備完了',
    'import_status_partial' => '準備完了 · 一部詳細',
    'import_status_geocoded' => 'ジオコード済み',
    'import_confirm_title' => 'インポートを確認',
    'import_confirm_text' => 'マーカーを作成する前にバッチ設定を確認してください。',
    'import_confirm_button' => '%d 個のマーカーをインポート',
    'import_cancel_button' => 'キャンセル',
	'order'                 => '順序',
	'move'                  => '移動',
	'name_missing'          => '少なくとも名前が 1 つ不足しています。CSV ファイルを確認してください。',
	'need_address'          => 'マーカーを作成するには少なくとも住所または座標が必要です。CSV ファイルを確認してください。',
	'manage_groups'         => 'オーバーレイグループを管理',
	'create_group'          => '新しいオーバーレイグループを作成',
	'group_edit'            => 'オーバーレイグループを編集',
	'group_overlay_presentation' => 'ここでオーバーレイグループ名を選択または編集できます',
	'group_overlay_name_label'   => 'グループ名',
	'group_label'           => 'グループ（任意）',
	'choose_group'          => 'グループを選択',
	'group'                 => 'グループ',
	
	//v1.3
	'geo_fail'              => '入力した住所は有効ではないようです',
	'on_map'                => 'マップ上',
	'read_more'             => '続きを読む',
	'from_map'              => 'マップ:',
	'show_hide_overlays'    => 'オーバーレイを表示 / 非表示',
	'fields_presentation'   => '既存カテゴリを編集してフィールドを追加または編集します。',
	'overlays_added'        => 'このマップにあるオーバーレイ',
	'overlays_to_add'       => 'このマップへ追加できるオーバーレイ',
	'marker_modification'   => 'マーカー変更',
	'from_owner'            => '追加者:',
	'marker_limited'        => 'このマーカーへのアクセスは制限されています…',
	'events_map'            => '次のイベントのマップ',
	'info_events_map'       => '',
	'from_cal'              => '開始',
	'to_cal'                => '～',
	'on_cal'                => 'オン',
    //v1.4
    'admin_menu_maps' => 'マップ',
    'admin_menu_markers' => 'マーカー',
    'admin_menu_icons' => 'アイコン',
    'admin_menu_overlays' => 'オーバーレイ',
    'admin_menu_import_export' => 'インポート/エクスポート',
    'admin_menu_geocoder' => 'ジオコーダー',
    'admin_menu_geolocation' => '位置情報',
    'admin_menu_configuration' => '設定',
    'section_location' => '場所',
    'section_content_contact' => '内容と連絡先',
    'section_appearance' => '外観',
    'section_publication' => '公開',
    'section_resources' => 'リソース',
    'section_ownership' => '所有者',
    'section_permissions' => '権限',
    'delete_confirm' => 'このマーカーを完全に削除しますか？',
    'marker_not_found' => 'マーカーが見つからないか、表示権限がありません。',
    'delete_map_confirm' => 'このマップを完全に削除しますか？',
    'technical_coordinates' => '技術座標',
    'configuration'         => '設定',
    // Maps 1.5.6 map editor
    'map_section_display' => '表示',
    'map_section_center' => '中心とズーム',
    'map_section_markers' => 'マーカー',
    'map_section_advanced' => '詳細オプション',
    'map_center_search' => '住所を検索',
    'map_center_search_button' => '位置を特定',
    'map_center_use_button' => '表示中の中心を使用',
    'map_center_help' => '住所を検索し、マップをクリックするかマーカーをドラッグして中心を正確に選択します。',
    'latitude_label' => '緯度',
    'longitude_label' => '経度',
    'map_center_marker' => 'マップ中心',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'システムメッセージ',
    'add_new_field'         => '新しいフィールドを作成しました',
    'save_field'            => 'フィールドを保存しました',
    'delete_field'          => 'フィールドを削除しました'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => '管理者様、',
    'new_marker'            => '承認待ちの新しいマーカーがあります。',
    'name'                  => '名前:',
    'on_map'                => 'マップ:',
    'submissions'           => '送信: ',
    'marker_submissions'    => 'マーカー送信',
	'marker_modification'   => 'マーカー変更',
	'description'           => '説明:',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "{$_CONF['site_name']} へマーカーを送信いただきありがとうございます。スタッフの承認待ちとして登録されました。";
$PLG_maps_MESSAGE2  = "マーカー送信は停止しています。";
$PLG_maps_MESSAGE3  = "エラーが発生し、マーカーを保存できませんでした。";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'マップ',
    'title' => 'マップ設定'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'マップメニューを非表示',
    'maps_login_required'   => 'マップの利用にログインが必要',
    'autofill_coord'        => '未定義の座標を自動入力',
    'display_geo_profile'   => 'プロフィール位置情報',
    'map_type_profile'      => 'プロフィールのマップタイプ',
    'map_type_geotag'       => 'geo オートタグのマップタイプ',
    'show_directions_geo'   => 'geo オートタグで経路を表示',
    'show_directions_profile' => 'プロフィールで経路を表示',
    'map_width_geotag'      => 'geo オートタグのマップ幅（% または px）',
    'map_height_geotag'     => 'geo オートタグのマップ高さ（px のみ）',
    'map_zoom_geotag'       => 'geo オートタグのズーム（0-21）',
    'map_width_profile'     => 'プロフィールのマップ幅（% または px）',
    'map_height_profile'    => 'プロフィールのマップ高さ（px のみ）',
    'show_map'              => 'Google マップを表示',
    'google_api_key'        => 'Google Maps API キー',
    'url_geocode'           => 'Google Geocoding サービス URL',
    'map_width'             => '標準マップ幅（% または px）',
    'map_height'            => '標準マップ高さ（px のみ）',
    'map_zoom'              => '標準マップズーム（0-21）',
    'map_type'              => '標準マップタイプ',
    'default_permissions'   => '標準権限',
    'map_main_header'       => 'メインページヘッダー、welcome オートタグ',
    'map_main_footer'       => 'メインページフッター、welcome オートタグも使用可',
    'map_geo'               => 'すべてのプロフィールを含むマップを作成',
    'map_markers'           => 'すべてのマーカーを含むマップを作成',
    'map_active'            => 'マップを有効化',
    'map_hidden'            => 'マップを非表示',
    'free_markers'          => '無料マーカーを許可',
    'paid_markers'          => '有料マーカーを許可（PayPal プラグインが必要）',
    'street'                => '通り情報を使用',
    'code'                  => '郵便番号情報を使用',
    'city'                  => '市区町村情報を使用',
    'state'                 => '都道府県/州情報を使用',
    'country'               => '国情報を使用',
    'tel'                   => '電話情報を使用',
    'fax'                   => '追加連絡先を使用',
    'web'                   => 'Web 情報を使用',
    'item_1'                => 'カスタムフィールド 1 のラベル',
    'item_2'                => 'カスタムフィールド 2 のラベル',
    'item_3'                => 'カスタムフィールド 3 のラベル',
    'item_4'                => 'カスタムフィールド 4 のラベル',
    'item_5'                => 'カスタムフィールド 5 のラベル',
    'item_6'                => 'カスタムフィールド 6 のラベル',
    'item_7'                => 'カスタムフィールド 7 のラベル',
    'item_8'                => 'カスタムフィールド 8 のラベル',
    'item_9'                => 'カスタムフィールド 9 のラベル',
    'item_10'               => 'カスタムフィールド 10 のラベル',
    'label_color'           => 'ラベル色',
    'star_primary_color'    => '星の主要色',
    'star_stroke_color'     => '星の線色',
    'marker_active'         => 'マーカーを標準で有効',
    'marker_hidden'         => 'マーカーを標準で非表示',
    'marker_payed'          => 'マーカーを標準で有料',
    'marker_validity'       => 'マーカーの標準有効期限',
    'marker_submission'     => 'マーカー送信を許可',
    'users_map'             => 'サイトユーザーマップを有効化',
    'global_map' 	        => 'グローバルマップを有効化',
    'global_type'           => 'グローバルマップタイプ',	
    'global_width'  	    => 'グローバルマップ幅',
    'global_height' 	    => 'グローバルマップ高さ',
    'global_zoom'           => 'グローバルマップズーム（0-21）',
    'detail_zoom'           => 'マーカー詳細ズーム（0-21）',
    'submit_login_required' => 'マーカー送信にログインが必要',
    'marker_edition'        => 'マーカー編集',
	'use_cluster'           => 'マーカークラスターを使用',
	'zoom_profile'          => 'ユーザープロフィールのマップズーム（0-21）',
	'display_events_map'    => 'イベントマップを表示',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'メイン設定',
    'sg_display' => '表示設定'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => '一般',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'マップ',
    'tab_markers' => 'マーカー',
    'tab_fields' => 'マーカーフィールド',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => '一般',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'マップ',
    'tab_markers' => 'マーカー',
    'tab_fields' => 'マーカーフィールド',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => '一般設定',
    'fs_ads'             => 'Google Ads 設定',
    'fs_google'          => 'Google API 設定',
    'fs_permissions'     => '標準権限',
    'fs_display'         => 'マップ',
    'fs_global_map'      => 'グローバルマップ',
    'fs_display_profile' => 'プロフィール',
    'fs_display_geo'     => 'geo オートタグ',
    'fs_map_default'     => 'マップ標準設定',
    'fs_marker_default'  => 'マーカー標準設定',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('有効' => 1, '無効' => 0),
    1 => array('有効' => TRUE, '無効' => FALSE),
    3 => array('はい' => 1, 'いいえ' => 0),
    4 => array('オン' => 1, 'オフ' => 0),
    5 => array('ページ上部' => 1, '注目記事の下' => 2, 'ページ下部' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('マイル' => 'マイル', 'キロメートル' => 'km'),
    12 => array('アクセス不可' => 0, '読み取り専用' => 2, '読み書き' => 3),
	// changed in v1.3
    20 => array('通常の道路地図' => 'ROADMAP', '衛星画像' => 'SATELLITE', '地形図' => 'TERRAIN', '衛星画像上の主要道路の透明レイヤー' => 'HYBRID'),
    30 => array('白' => 1, '黒' => 0),
    31 => array('一時' => 1, '永続' => 0),
);

$LANG_MAPS_1['location_search_label'] = '住所を検索';
$LANG_MAPS_1['location_search_help'] = '住所を検索し、マップをクリックするかマーカーをドラッグして位置を微調整します。';
$LANG_MAPS_1['use_map_click_help'] = 'マップをクリックしてマーカーを移動します。';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = 'メイン';
$LANG_configtabs['maps']['tab_general'] = '一般';
$LANG_tab['maps']['tab_general'] = '一般';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = 'マップ';
$LANG_tab['maps']['tab_maps'] = 'マップ';
$LANG_configtabs['maps']['tab_markers'] = 'マーカー';
$LANG_tab['maps']['tab_markers'] = 'マーカー';
$LANG_configtabs['maps']['tab_fields'] = 'マーカーフィールド';
$LANG_tab['maps']['tab_fields'] = 'マーカーフィールド';

$LANG_fs['maps']['fs_main'] = 'アクセスと機能';
$LANG_fs['maps']['fs_permissions'] = '標準権限';
$LANG_fs['maps']['fs_uploads'] = '画像とアップロード';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = '一般表示';
$LANG_fs['maps']['fs_global_map'] = 'グローバル・ユーザーマップ';
$LANG_fs['maps']['fs_display_profile'] = 'ユーザープロフィールマップ';
$LANG_fs['maps']['fs_display_geo'] = 'Geo オートタグ';
$LANG_fs['maps']['fs_map_defaults'] = '新規マップ標準設定';
$LANG_fs['maps']['fs_events_map'] = 'イベントマップ';
$LANG_fs['maps']['fs_marker_defaults'] = 'マーカー標準設定';
$LANG_fs['maps']['fs_marker_editor'] = 'マーカーエディターマップ';
$LANG_fs['maps']['fs_marker_detail'] = 'マーカー詳細マップ';
$LANG_fs['maps']['fs_marker_popup'] = 'マーカー情報ウィンドウ';
$LANG_fs['maps']['fs_marker_fields'] = 'マーカーフィールドとラベル';

$LANG_confignames['maps']['max_image_width'] = '最大画像幅（px）';
$LANG_confignames['maps']['max_image_height'] = '最大画像高さ（px）';
$LANG_confignames['maps']['max_image_size'] = '最大画像サイズ（バイト）';
$LANG_confignames['maps']['google_api_key'] = 'Google Maps ブラウザー API キー';
$LANG_confignames['maps']['google_server_api_key'] = 'Google Geocoding サーバー API キー';
$LANG_confignames['maps']['google_map_id'] = 'Google Map ID（Advanced Markers 準備用）';
$LANG_confignames['maps']['google_language'] = 'Google Maps の言語（任意、例: ja）';
$LANG_confignames['maps']['google_region'] = 'Google Maps の地域（任意、例: JP）';
$LANG_confignames['maps']['url_geocode'] = 'Google Geocoding サービス URL';
$LANG_confignames['maps']['map_primary_color'] = 'マップ標準主要色';
$LANG_confignames['maps']['map_stroke_color'] = 'マップ標準線色';
$LANG_confignames['maps']['map_label'] = 'マップ標準マーカーラベル';
$LANG_confignames['maps']['map_label_color'] = 'マップ標準ラベル色';
$LANG_confignames['maps']['events_map_zoom'] = 'イベントマップズーム';
$LANG_confignames['maps']['events_map_height'] = 'イベントマップ高さ';
$LANG_confignames['maps']['users_map_lat'] = 'ユーザーマップ中心緯度（空欄 = 自動）';
$LANG_confignames['maps']['users_map_lng'] = 'ユーザーマップ中心経度（空欄 = 自動）';
$LANG_confignames['maps']['users_map_zoom'] = 'ユーザーマップズーム（空欄 = グローバルマップ）';
$LANG_confignames['maps']['users_map_type'] = 'ユーザーマップタイプ（空欄 = グローバルマップ）';
$LANG_confignames['maps']['users_map_width'] = 'ユーザーマップ幅（空欄 = グローバルマップ）';
$LANG_confignames['maps']['users_map_height'] = 'ユーザーマップ高さ（空欄 = グローバルマップ）';
$LANG_confignames['maps']['marker_editor_type'] = 'マーカーエディターのマップタイプ';
$LANG_confignames['maps']['marker_editor_zoom'] = 'マーカーエディター初期ズーム';
$LANG_confignames['maps']['marker_editor_width'] = 'マーカーエディターのマップ幅';
$LANG_confignames['maps']['marker_editor_height'] = 'マーカーエディターのマップ高さ';
$LANG_confignames['maps']['detail_width'] = 'マーカー詳細マップ幅';
$LANG_confignames['maps']['detail_height'] = 'マーカー詳細マップ高さ';
$LANG_confignames['maps']['detail_zoom'] = 'マーカー詳細マップズーム';
$LANG_confignames['maps']['popup_width'] = '情報ウィンドウ幅';
$LANG_confignames['maps']['popup_height'] = '情報ウィンドウ高さ';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = 'ランディングページ SEO';
$LANG_confignames['maps']['maps_page_title'] = 'マップのランディングページ SEO タイトル';
$LANG_confignames['maps']['maps_page_h1'] = 'マップのランディングページ H1 見出し';
$LANG_confignames['maps']['maps_meta_description'] = 'マップのランディングページメタ説明';
$LANG_confignames['maps']['map_main_header'] = 'マップのランディングページ紹介内容（オートタグ対応）';

$LANG_MAPS_1['server_geocode_key_missing'] = 'サーバー側の座標検索が有効ですが、専用の Google Geocoding サーバー API キーが設定されていません。ブラウザーキーはサーバー側ジオコーディングには使用されません。';
$LANG_MAPS_1['api_diag_title'] = 'Google Maps Platform 設定';
$LANG_MAPS_1['api_diag_maps_js'] = 'Maps JavaScript API';
$LANG_MAPS_1['api_diag_geocoding'] = 'Geocoding API';
$LANG_MAPS_1['api_diag_directions'] = 'Directions API';
$LANG_MAPS_1['api_diag_browser_key'] = 'ブラウザー API キー';
$LANG_MAPS_1['api_diag_server_key'] = 'サーバー API キー';
$LANG_MAPS_1['api_diag_map_id'] = 'マップ ID';
$LANG_MAPS_1['api_diag_configured'] = 'キー設定済み — API 未確認';
$LANG_MAPS_1['api_diag_browser_verify'] = 'キー設定済み — 下のブラウザーテストで確認してください';
$LANG_MAPS_1['api_diag_referrer_hint'] = 'ブラウザーキーの HTTP リファラー制限では、このサイトを許可してください（例: %s/*）。';
$LANG_MAPS_1['api_diag_missing'] = '未設定';
$LANG_MAPS_1['api_diag_optional'] = '任意 / 未設定';
$LANG_MAPS_1['integrations_title'] = '連携';
$LANG_MAPS_1['integrations_intro'] = 'マップは Geeklog API とサービスを使い、関連プラグインがデータベースへ直接結合せずにマップを検出できるようにします。';
$LANG_MAPS_1['integration_active'] = '有効';
$LANG_MAPS_1['integration_missing'] = 'プラグインなし';
$LANG_MAPS_1['integration_native'] = '標準対応';
$LANG_MAPS_1['integration_xmlsitemap'] = 'XML サイトマップ';
$LANG_MAPS_1['integration_documents'] = 'ドキュメント';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'RSS / Atom フィード';
$LANG_MAPS_1['use_my_location'] = '現在地を使用';
$LANG_MAPS_1['geolocation_unavailable'] = 'ブラウザーの位置情報を利用できません。出発住所を手動で入力してください。';
$LANG_MAPS_1['geolocation_denied'] = '現在地を取得できませんでした。位置情報アクセスを許可するか、出発住所を手動で入力してください。';
$LANG_MAPS_1['geolocation_https'] = 'ブラウザーの位置情報は通常 HTTPS が必要です。';
?>
