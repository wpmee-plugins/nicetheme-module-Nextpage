<?php
/*
  	Plugin Name: nicetheme 自动分页
	Plugin URI: https://www.nicetheme.cn/modules/nice-nextpage
	Description: 实现文章内容自动分页
	Version: 1.0.0
	Author URI: http://www.nicetheme.cn
	Nicetheme Module: Nextpage
	Compatible: PandaPRO|Cosy|Grace|Ashley|LivingCoral
*/

define( 'NC_NEXTPAGE_DIR', dirname(__FILE__) );
define( 'NC_NEXTPAGE_RELATIVE_DIR', NC_NEXTPAGE_DIR );
define( 'NC_NEXTPAGE_URI', plugin_dir_url(__FILE__));
define( 'NC_NEXTPAGE_VERSION', '1.0.0' );
define( 'NC_NEXTPAGE__FILE__', __FILE__ );

function nc_nextpage_init() {
	if( !defined('NC_STORE_ROOT_PATH') ) {
		add_action( 'admin_notices', 'nc_nextpage_init_check' );
		function nc_nextpage_init_check() {
			$html = '<div class="notice notice-error">
				<p><b>错误：</b> 自动分页 积木 缺少依赖插件 <code>nicetheme 积木</code> 请先安装并启用 <code>nicetheme 积木</code> 插件。</p>
			</div>';
			echo $html;
		}
	} else {
		add_filter('nc_save_json_paths', 'nc_nextpage_acf_json_save_point');
		function nc_nextpage_acf_json_save_point( $path ) {
			$path[] = NC_NEXTPAGE_DIR . '/functions/conf';
			return $path;
		}
		
		add_filter('acf/settings/load_json', 'nc_nextpage_acf_json_load_point');
		function nc_nextpage_acf_json_load_point( $paths ) {
			$paths[] = NC_NEXTPAGE_DIR . '/functions/conf';
			return $paths;
		}

		acf_add_options_sub_page(
			array(
				'page_title'      => '自动分页 积木',
				'menu_title'      => '自动分页 积木',
				'menu_slug'       => 'nc-nextpage-options',
				'parent_slug'     => 'nc-modules-store',
				'capability'      => 'manage_options',
				'update_button'   => '保存',
				'updated_message' => '设置已保存！'
			)
		);
		require_once NC_NEXTPAGE_DIR . '/functions/core.php';
	}
}
add_action('plugins_loaded', 'nc_nextpage_init', 999);