<?php
/**
 * Class WXD_Trans_Import
 *
 * 翻译包导入小工具核心类
 *
 * @package wpfanyi-import
 */
class WXD_Trans_Import {

    /**
     * @var string 翻译导入的方式 file or url
     *
     * @since 1.0.0
     */
    private $trans_import_method = '';

    /**
     * @var string 用户选择的翻译包类型 plugin or theme
     *
     * @since 1.0.0
     */
    private $trans_type = '';

    /**
     * @var array 用户上传的翻译包信息
     *
     * @since 1.0.0
     */
    private $trans_zip = [];

    /**
     * @var string 翻译包的URL
     *
     * @since 1.0.0
     */
    private $trans_url = '';

    public function __construct() {
        /**
         * 注册菜单
         */
        add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', function () {
            add_submenu_page(
                is_multisite() ? 'index.php' : 'tools.php',
                __('导入翻译包', 'wpfanyi-import'),
                __('导入翻译包', 'wpfanyi-import'),
                is_multisite() ? 'manage_network_options' : 'manage_options',
                'wpfanyi_import',
                [$this, 'wpfanyi_import_page']
            );
        });
    }

    /**
     * 输出翻译导入页面表单并处理用户提交的表单
     *
     * @since 1.0.0
     */
    public function wpfanyi_import_page() {
        /**
         * 若是POST请求则处理翻译包导入工作
         */
        if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === strtoupper($_SERVER['REQUEST_METHOD'])) {
            $this->trans_import_method = @$_POST['trans_import_method'];
            $this->trans_type = @$_POST['trans_type'];
            $this->trans_zip = @$_FILES['trans_zip'];
            $this->trans_url = @$_POST['trans_url'];

            if ($this->data_verify()) {
                if ($this->import_trans()) {
                    $this->success_msg(__('翻译导入成功', 'wpfanyi-import'));
                }
            }
        }

        add_action('admin_footer', [$this, 'register_css_and_js']);

        require_once 'page.php';
    }

    /**
     * 注册设置页依赖的css及js
     */
    public function register_css_and_js() {
        echo <<<EOT
<style>
    .trans_tabs {
        display: flex;
        list-style: none;
    }

    .trans_tabs li {
        padding: .5rem 1rem 1rem;
        margin: 0 1rem;
        cursor: pointer;
    }

    .trans_tabs li.active, .trans_tabs li:hover {
        box-shadow: inset 0 -3px #007cba;

        transition: box-shadow .5s ease-in-out;
    }

    .tab-item {
        display: none;
    }

    .show {
        display: block;
    }
	#trans_url{min-height: 31px;
		width: 28em;}
	.form-table th{
		    width: 140px;
	}
	.input_clear button{
	    position: absolute;
    right: 5px;
    margin: auto;
    bottom: 0;
    top: 0;
    border: 0;
    background: none;
    font-size: 18px;
    cursor: pointer;
	}
</style>
<script >!function(a){function b(a){return"[id="+a.attr("id")+"][name="+a.attr("name")+"]"}a.fn.sisyphus=function(c){var d=a.map(this,function(c){return b(a(c))}).join(),e=Sisyphus.getInstance(d);return e.protect(this,c),e};var c={};c.isAvailable=function(){if("object"==typeof a.jStorage)return!0;try{return localStorage.getItem}catch(b){return!1}},c.set=function(b,c){if("object"==typeof a.jStorage)a.jStorage.set(b,c+"");else try{localStorage.setItem(b,c+"")}catch(d){}},c.get=function(b){if("object"==typeof a.jStorage){var c=a.jStorage.get(b);return c?c.toString():c}return localStorage.getItem(b)},c.remove=function(b){"object"==typeof a.jStorage?a.jStorage.deleteKey(b):localStorage.removeItem(b)},Sisyphus=function(){function f(){return{setInstanceIdentifier:function(a){this.identifier=a},getInstanceIdentifier:function(){return this.identifier},setInitialOptions:function(b){var d={excludeFields:[],customKeySuffix:"",locationBased:!1,timeout:0,autoRelease:!0,onBeforeSave:function(){},onSave:function(){},onBeforeRestore:function(){},onRestore:function(){},onRelease:function(){}};this.options=this.options||a.extend(d,b),this.browserStorage=c},setOptions:function(b){this.options=this.options||this.setInitialOptions(b),this.options=a.extend(this.options,b)},protect:function(b,c){this.setOptions(c),b=b||{};var f=this;if(this.targets=this.targets||[],f.options.name?this.href=f.options.name:this.href=location.hostname+location.pathname+location.search+location.hash,this.targets=a.merge(this.targets,b),this.targets=a.unique(this.targets),this.targets=a(this.targets),!this.browserStorage.isAvailable())return!1;var g=f.options.onBeforeRestore.call(f);if((void 0===g||g)&&f.restoreAllData(),this.options.autoRelease&&f.bindReleaseData(),!d.started[this.getInstanceIdentifier()])if(f.isCKEditorPresent())var h=setInterval(function(){e.isLoaded&&(clearInterval(h),f.bindSaveData(),d.started[f.getInstanceIdentifier()]=!0)},100);else f.bindSaveData(),d.started[f.getInstanceIdentifier()]=!0},isCKEditorPresent:function(){return this.isCKEditorExists()?(e.isLoaded=!1,e.on("instanceReady",function(){e.isLoaded=!0}),!0):!1},isCKEditorExists:function(){return"undefined"!=typeof e},findFieldsToProtect:function(a){return a.find(":input").not(":submit").not(":reset").not(":button").not(":file").not(":password").not(":disabled").not("[readonly]")},bindSaveData:function(){var c=this;c.options.timeout&&c.saveDataByTimeout(),c.targets.each(function(){var d=b(a(this));c.findFieldsToProtect(a(this)).each(function(){if(-1!==a.inArray(this,c.options.excludeFields))return!0;var e=a(this),f=(c.options.locationBased?c.href:"")+d+b(e)+c.options.customKeySuffix;(e.is(":text")||e.is("textarea"))&&(c.options.timeout||c.bindSaveDataImmediately(e,f)),c.bindSaveDataOnChange(e)})})},saveAllData:function(){var c=this;c.targets.each(function(){var d=b(a(this)),f={};c.findFieldsToProtect(a(this)).each(function(){var g=a(this);if(-1!==a.inArray(this,c.options.excludeFields)||void 0===g.attr("name")&&void 0===g.attr("id"))return!0;var h=(c.options.locationBased?c.href:"")+d+b(g)+c.options.customKeySuffix,i=g.val();if(g.is(":checkbox")){var j=g.attr("name");if(void 0!==j&&-1!==j.indexOf("[")){if(f[j]===!0)return;i=[],a("[name='"+j+"']:checked").each(function(){i.push(a(this).val())}),f[j]=!0}else i=g.is(":checked");c.saveToBrowserStorage(h,i,!1)}else if(g.is(":radio"))g.is(":checked")&&(i=g.val(),c.saveToBrowserStorage(h,i,!1));else if(c.isCKEditorExists()){var k=e.instances[g.attr("name")]||e.instances[g.attr("id")];k?(k.updateElement(),c.saveToBrowserStorage(h,g.val(),!1)):c.saveToBrowserStorage(h,i,!1)}else c.saveToBrowserStorage(h,i,!1)})}),c.options.onSave.call(c)},restoreAllData:function(){var c=this,d=!1;c.targets.each(function(){var e=a(this),f=b(a(this));c.findFieldsToProtect(e).each(function(){if(-1!==a.inArray(this,c.options.excludeFields))return!0;var e=a(this),g=(c.options.locationBased?c.href:"")+f+b(e)+c.options.customKeySuffix,h=c.browserStorage.get(g);null!==h&&(c.restoreFieldsData(e,h),d=!0)})}),d&&c.options.onRestore.call(c)},restoreFieldsData:function(a,b){if(void 0===a.attr("name")&&void 0===a.attr("id"))return!1;var c=a.attr("name");!a.is(":checkbox")||"false"===b||void 0!==c&&-1!==c.indexOf("[")?!a.is(":checkbox")||"false"!==b||void 0!==c&&-1!==c.indexOf("[")?a.is(":radio")?a.val()===b&&a.prop("checked",!0):void 0===c||-1===c.indexOf("[")?a.val(b):(b=b.split(","),a.val(b)):a.prop("checked",!1):a.prop("checked",!0)},bindSaveDataImmediately:function(a,b){var c=this;if("onpropertychange"in a?a.get(0).onpropertychange=function(){c.saveToBrowserStorage(b,a.val())}:a.get(0).oninput=function(){c.saveToBrowserStorage(b,a.val())},this.isCKEditorExists()){var d=e.instances[a.attr("name")]||e.instances[a.attr("id")];d&&d.document.on("keyup",function(){d.updateElement(),c.saveToBrowserStorage(b,a.val())})}},saveToBrowserStorage:function(a,b,c){var d=this,e=d.options.onBeforeSave.call(d);(void 0===e||e!==!1)&&(c=void 0===c?!0:c,this.browserStorage.set(a,b),c&&""!==b&&this.options.onSave.call(this))},bindSaveDataOnChange:function(a){var b=this;a.change(function(){b.saveAllData()})},saveDataByTimeout:function(){var a=this,b=a.targets;setTimeout(function(){function b(){a.saveAllData(),setTimeout(b,1e3*a.options.timeout)}return b}(b),1e3*a.options.timeout)},bindReleaseData:function(){var c=this;c.targets.each(function(){var d=a(this),e=b(d);a(this).bind("submit reset",function(){c.releaseData(e,c.findFieldsToProtect(d))})})},manuallyReleaseData:function(){var c=this;c.targets.each(function(){var d=a(this),e=b(d);c.releaseData(e,c.findFieldsToProtect(d))})},releaseData:function(c,e){var f=!1,g=this;d.started[g.getInstanceIdentifier()]=!1,e.each(function(){if(-1!==a.inArray(this,g.options.excludeFields))return!0;var d=a(this),e=(g.options.locationBased?g.href:"")+c+b(d)+g.options.customKeySuffix;g.browserStorage.remove(e),f=!0}),f&&g.options.onRelease.call(g)}}}var d={instantiated:[],started:[]},e=window.CKEDITOR;return{getInstance:function(a){return d.instantiated[a]||(d.instantiated[a]=f(),d.instantiated[a].setInstanceIdentifier(a),d.instantiated[a].setInitialOptions()),a?d.instantiated[a]:d.instantiated[a]},free:function(){return d={instantiated:[],started:[]},null},version:"1.1.3"}}()}(jQuery);</script>
<script type="text/javascript">
  (function ($) {
  
  
    $(".trans_tabs li").click(function () {
      // 修改tab标签样式
      $(this).attr("class", "active")
      $(this).siblings().attr("class", "")



      // 获取tab ID
      var itemId = $(this).attr("tabid") - 1;

      // 切换到指定tab
      $("#box").find("div:eq(" + itemId + ")").attr("class", "show")
      $("#box").find("div:eq(" + itemId + ")").siblings().attr("class", "tab-item")
    })
	

$( "form" ).sisyphus();
		$("input").focus(function(){  
    $(this).parent().children(".input_clear").show();  
});  
$("input").blur(function(){  
    if($(this).val()=='')  
    {  
        $(this).parent().children(".input_clear").hide();  
    }  
});  
$(".input_clear").click(function(){  
    $(this).parent().find('input').val('');  
    $(this).hide();  
}); 





  })(jQuery);
</script>

EOT;
    }

    /**
     * 用于校验用户传递来的数据
     *
     * @since 1.0.0
     *
     * @return bool 是否校验通过
     */
    private function data_verify() {
        if (!current_user_can( 'install_plugins' ) || !(isset($_POST['_wpnonce']) &&
            wp_verify_nonce($_POST['_wpnonce'], 'wpfanyi-import-nonce'))) {
            $this->error_msg(__('非法的提交，你没有权限这样做', 'wpfanyi-import'));

            return false;
        }

        if ('plugin' !== $this->trans_type && 'theme' !== $this->trans_type) {
            $this->error_msg(__('非法的包类型', 'wpfanyi-import'));

            return false;
        }

        if ('file' === $this->trans_import_method) {
            if (empty($this->trans_zip['name'])) {
                $this->error_msg(__('未选择翻译包', 'wpfanyi-import'));

                return false;
            }
            if ('application/x-zip-compressed' !== @$this->trans_zip['type']) {
                $this->error_msg(__('翻译包应为zip格式压缩包', 'wpfanyi-import'));

                return false;
            }
        } elseif ('url' === $this->trans_import_method) {
            $pattern="#(http|https)://(.*\.)?.*\..*#i";
            if (!preg_match($pattern, $this->trans_url)) {
                $this->error_msg(__('你输入的不是有效的URL地址', 'wpfanyi-import'));

                return false;
            }
        } else {
            $this->error_msg(__('参数错误：未知的翻译包导入方式', 'wpfanyi-import'));

            return false;
        }


        return true;
    }

    /**
     * 执行翻译包导入
     *
     * @since 1.0.0
     *
     * @return bool 是否导入成功
     */
    private function import_trans() {
        $trans_dir = WP_CONTENT_DIR . '/languages/' . $this->trans_type . 's';

        $trans_zip_file = 'file' === $this->trans_import_method ? @$this->trans_zip['tmp_name'] : download_url($this->trans_url, $timeout = 1000);

        if(!file_exists($trans_zip_file) && filesize($trans_zip_file) > 0) {
            if ('file' === $this->trans_import_method) {
                $this->error_msg(__('翻译包上传失败，请检查文件系统权限是否正常', 'wpfanyi-import'));
            } else {
                $this->error_msg(__('翻译包获取失败，请检查URL是否有效', 'wpfanyi-import'));
            }

            return false;
        }

        if(!is_writable($trans_dir)) {
            /** 当发现翻译目录不可写后尝试创建目录，若创建失败则证明是文件系统权限存在问题 */
            if (!mkdir($trans_dir, 0775, true)) {
                /* translators: %s: 不可写的系统翻译存储目录的具体路径 */
                $this->error_msg(sprintf(__('该WordPress的翻译存储目录不可写：%s', 'wpfanyi-import'), $trans_dir));

                return false;
            }
        }

        if (!class_exists('ZipArchive')) {
            $this->error_msg(__('当前服务器不支持zip压缩包的解压，请联系服务商开启php的zip扩展模块', 'wpfanyi-import'));

            return false;
        }

        $zip = new ZipArchive;
        $res = $zip->open($trans_zip_file);
        if (!$res) {
            $this->error_msg(__('压缩包解析失败，该压缩包可能已经损坏', 'wpfanyi-import'));
        }

        $zip->extractTo($trans_dir);
        $zip->close();

        /** 对于使用download_url()下载的临时文件使用后必须删除，但用户通过表单上传的则有可能因为权限问题删除失败，不过这无关紧要 */
        @unlink($trans_zip_file);

        return true;
    }

    /**
     * 向页面输出状态为成功的消息
     *
     * @since 1.0.0
     *
     * @param string $msg 要输出的消息
     */
    private function success_msg($msg) {
        echo "<div id='message' class='updated notice'><p>{$msg}</p></div>";
    }

    /**
     * 向页面输出状态为失败的消息
     *
     * @since 1.0.0
     *
     * @param string $msg 要输出的消息
     */
    private function error_msg($msg) {
        echo "<div id='message' class='updated error'><p>{$msg}</p></div>";
    }

}
