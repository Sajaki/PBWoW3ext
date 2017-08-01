<?php
/**
 *
 * @package PBWoW Extension
 * Japanese translation by momo-i
 *
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	// Extension modules
	'ACP_PBWOW3_CATEGORY'		=> 'PBWoW 3',
	'ACP_PBWOW3_OVERVIEW'		=> '概要',
	'ACP_PBWOW3_CONFIG'			=> '環境設定',

	// Common
	'PBWOW_ACTIVE'				=> '有効',
	'PBWOW_INACTIVE'			=> '無効',
	'PBWOW_DETECTED'			=> '検出',
	'PBWOW_NOT_DETECTED'		=> '未検出',
	'PBWOW_OBSOLETE'			=> '廃止',
	'PBWOW_FLUSH'				=> 'フラッシュ',
	'PBWOW_FATAL'				=> '致命的なエラー！これは本当に起こることはありません。',

	'LOG_PBWOW_CONFIG'			=> '<strong>PBWoW の設定が変更されました</strong><br />&raquo; %s',

	// OVERVIEW //

	'PBWOW_OVERVIEW_TITLE'				=> 'PBWoW 拡張機能の概要',
	'PBWOW_OVERVIEW_TITLE_EXPLAIN'		=> 'PBWoWをお選びいただきありがとうございます。',
	'ACP_PBWOW_INDEX_SETTINGS'			=> '一般設定',

	'PBWOW_DB_CHECK'					=> 'PBWoW データベースチェック',
	'PBWOW_DB_GOOD'						=> 'PBWoW の設定テーブルが見つかりました (%s)',
	'PBWOW_DB_BAD'						=> 'PBWoW の設定テーブルが見つかりませんでした。データベースにテーブル(%s)が存在することを確認してください。',
	'PBWOW_DB_BAD_EXPLAIN'				=> 'PBWoW 3 拡張機能を無効化して再度有効化してみてください。動作しない場合、拡張機能を無効化してデータを削除してみてください。そのあとで再度有効化をお試しください。',

	'PBWOW_VERSION_CHECK'				=> 'PBWoW バージョンチェック',
	'PBWOW_LATEST_VERSION'				=> '最新バージョン',
	'PBWOW_EXT_VERSION'					=> '拡張機能バージョン',
	'PBWOW_STYLE_VERSION'				=> 'スタイルバージョン',
	'PBWOW_VERSION_ERROR'				=> 'バージョンを確認することができません！',
	'PBWOW_CHECK_UPDATE'				=> '更新が利用可能な場合は<a href="http://www.avathar.be/bbdkp/index.php">avathar.be</a>を確認して下さい。',

	'PBWOW_DONATE'						=> 'PBWoWへ寄付',
	'PBWOW_DONATE_SHORT'				=> 'PBWoWへ寄付をします',
	'PBWOW_DONATE_EXPLAIN'				=> 'PBWoW は 100% 無料です。それは私の趣味で自分の時間とお金を費やしているプロジェクトです。あなたがPBWoWを使用して楽しむ場合は、寄付をすることをご検討ください。私は実際にそれをいただければ幸いです。付帯条件はありません。',

	// CONFIG //

	'PBWOW_CONFIG_TITLE'				=> 'PBWoW 設定',
	'PBWOW_CONFIG_TITLE_EXPLAIN'		=> 'ここではPBWoWインストールをカスタマイズするためのいくつかのオプションを選択できます。',

	'PBWOW_LOGO'						=> 'カスタムロゴ',
	'PBWOW_LOGO_ENABLE'					=> '自身のカスタムロゴ画像を有効にする',
	'PBWOW_LOGO_ENABLE_EXPLAIN'			=> 'これを使用してインストール済みの全てのPBWoWスタイル(PBWoWのマスタースタイルを除く)でカスタムロゴを有効にします。',
	'PBWOW_LOGO_SRC'					=> '画像のソースパス',
	'PBWOW_LOGO_SRC_EXPLAIN'			=> 'phpBBのルートディレクトリ配下の画像パス(例 <samp>images/logo.png</samp>)です。<br />透過PNG画像を使用することを強くおすすめします。',
	'PBWOW_LOGO_SIZE'					=> 'ロゴの大きさ',
	'PBWOW_LOGO_SIZE_EXPLAIN'			=> 'ロゴ画像の正確な大きさ(幅 x 高さ ピクセル)です。<br />350 x 200 ピクセル以上の画像は(レスポンシブレイアウトのため)お勧めしません。',
	'PBWOW_LOGO_MARGINS'				=> 'ロゴの余白',
	'PBWOW_LOGO_MARGINS_EXPLAIN'		=> 'ロゴのCSSの余白(mergin)を設定します。これは画像の位置をより詳細にコントロールします。正しいCSSマークアップ(例 <samp>10px 5px 25px 0</samp>)を使用してください。',

	'PBWOW_TOPBAR'						=> 'トップヘッダーバー',
	'PBWOW_TOPBAR_ENABLE'				=> 'トップヘッダーバーを有効にする',
	'PBWOW_TOPBAR_ENABLE_EXPLAIN'		=> 'このオプションを有効にすることで、40ピクセルの高さのカスタマイズ可能なバーを各ページのトップに表示します。',
	'PBWOW_TOPBAR_CODE'					=> 'トップヘッダーバーのコード',
	'PBWOW_TOPBAR_CODE_EXPLAIN'			=> 'ここにコードを入力します。ボーダーでブロックを区切るには&lt;span&gt;または&lt;a class="cell"&gt;要素を使用します。アイコンを使用するには&lt;img&gt;ブロックを使用するかcustom.css内部に特別なCSSクラスを定義(おすすめ)するかのどちらかです。',
	'PBWOW_TOPBAR_FIXED'				=> 'トップに固定する',
	'PBWOW_TOPBAR_FIXED_EXPLAIN'		=> '画面の最上部へトップヘッダーバーを固定することはスクロールしても所定の位置にロックされてみることが出来る事を維持します。<br />これはモバイルデバイスには適用されません。小さい画面で閲覧するときはデフォルトのスクローリングに戻ります。',

	'PBWOW_HEADERLINKS'					=> 'ヘッダーボックスのカスタムリンク',
	'PBWOW_HEADERLINKS_ENABLE'			=> 'ヘッダーボックスでカスタムリンクを有効にする',
	'PBWOW_HEADERLINKS_ENABLE_EXPLAIN'	=> 'このオプションを有効にすることで、以下に入力されたHTMLコードは画面の右上のボックス内(FAQリンクの前のインライン)に表示されます。これはDKPリンク(そのいくつかは自動的に検出されます)やポータルで有用です。',
	'PBWOW_HEADERLINKS_CODE'			=> 'カスタムヘッダーリンクのコード',
	'PBWOW_HEADERLINKS_CODE_EXPLAIN'	=> 'カスタムリンクをここに入力します。これらは&lt;li&gt;要素で囲まれます。アイコンを使用するにはcustom.css内部にCSSクラスを定義してください。',

	'PBWOW_VIDEOBG'						=> '(動画) 背景設定',
	'PBWOW_VIDEOBG_ENABLE'				=> 'アニメーション動画背景を有効にする',
	'PBWOW_VIDEOBG_ENABLE_EXPLAIN'		=> 'いくつかの PBWoW スタイル(全てではありません)は特別なアニメーション動画背景をサポートします。クールなエフェクトのためにこれらを有効化したり、帯域確保(または問題がある場合)のためにこれらを無効にすることが出来ます。',
	'PBWOW_VIDEOBG_ALLPAGES'			=> '全てのページで動画背景を表示しますか？',
	'PBWOW_VIDEOBG_ALLPAGES_EXPLAIN'	=> 'デフォルトでは、<u>index.php</u>ページ上でのみ動画背景(利用可能な場合)を読み込みます。全てのページでこれを有効に出来ますが、訪問者の閲覧速度に影響をあたえるかもしれません。(ただし、彼らはローカルにキャッシュするため、サーバーの帯域は一般的ではありません)【動画が有効な場合のみ適用されます】',
	'PBWOW_FIXEDBG'						=> '背景位置を固定する',
	'PBWOW_FIXEDBG_EXPLAIN'				=> '背景位置の固定はコンテンツの残りの部分と一緒にスクロールされることを防ぐことが出来ます。いくつかの低解像度デバイスは全体の背景画像を見るためのオプションを持っていないことに注意してください。',

	'PBWOW_ADS_INDEX'					=> 'インデックス広告ブロック',
	'PBWOW_ADS_INDEX_ENABLE'			=> 'インデックス広告を有効にする',
	'PBWOW_ADS_INDEX_ENABLE_EXPLAIN'	=> 'この広告を有効にするとフォーラムのインデックスページ(新着トピック拡張が必須)に小さな広告ブロックを生成します。',
	'PBWOW_ADS_INDEX_CODE'				=> 'インデックス広告コード',
	'PBWOW_ADS_INDEX_CODE_EXPLAIN'		=> 'このブロックは<b>300ピクセル</b>幅の広告に適しています。<br />CSSスタイルを使用/変更したい場合、<samp>styles/pbwow3/theme/custom.css</samp>へ追加してください。',
));
