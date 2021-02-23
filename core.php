<?php
/**
 * Class WPfanyi_Import
 *
 * Core class
 *
 * @package wpfanyi-import
 */
class WPfanyi_Import {

    /**
     * @var string Translation package import method. value:"file" or "url"
     *
     * @since 1.0.0
     */
    private $trans_import_method = '';

    /**
     * @var string Translation package type. value:"plugin" or "theme"
     *
     * @since 1.0.0
     */
    private $trans_type = '';

    /**
     * @var array Translation package information uploaded by users
     *
     * @since 1.0.0
     */
    private $trans_zip = [];

    /**
     * @var string Translation package URL
     *
     * @since 1.0.0
     */
    private $trans_url = '';

    public function __construct() {
        /**
         * Register menu
         */
        add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', function () {
            add_submenu_page(
                is_multisite() ? 'index.php' : 'tools.php',
                __('import translation', 'wpfanyi-import'),
                __('import translation', 'wpfanyi-import'),
                is_multisite() ? 'manage_network_options' : 'manage_options',
                'wpfanyi_import',
                [$this, 'wpfanyi_import_page']
            );
        });
    }

    /**
     * Output and process translation import form
     *
     * @since 1.0.0
     */
    public function wpfanyi_import_page() {
        /** If it is a post request, the form value is processed */
        if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === strtoupper($_SERVER['REQUEST_METHOD'])) {
            $this->trans_import_method = @$_POST['trans_import_method'];
            $this->trans_type = @$_POST['trans_type'];
            $this->trans_zip = @$_FILES['trans_zip'];
            $this->trans_url = @$_POST['trans_url'];

            if ($this->data_verify()) {
                if ($this->import_trans()) {
                    $this->success_msg(__('Translation imported successfully!', 'wpfanyi-import'));
                }
            }
        }

        add_action('admin_footer', [$this, 'register_css_and_js']);

        require_once 'page.php';
    }

    /**
     * Register CSS and JS for forms
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
     * Verify the data submitted by the user
     *
     * @since 1.0.0
     *
     * @return bool true on success or false on failure.
     */
    private function data_verify() {
        if (!current_user_can( 'install_plugins' ) || !(isset($_POST['_wpnonce']) &&
            wp_verify_nonce($_POST['_wpnonce'], 'wpfanyi-import-nonce'))) {
            $this->error_msg(__('You don\'t have the authority to do that', 'wpfanyi-import'));

            return false;
        }

        if ('plugin' !== $this->trans_type && 'theme' !== $this->trans_type) {
            $this->error_msg(__('Unexpected translation package type', 'wpfanyi-import'));

            return false;
        }

        if ('file' === $this->trans_import_method) {
            if (empty($this->trans_zip['name'])) {
                $this->error_msg(__('Translation package not selected', 'wpfanyi-import'));

                return false;
            }
            if ('application/x-zip-compressed' !== @$this->trans_zip['type']) {
                $this->error_msg(__('The translation package should be in ZIP format', 'wpfanyi-import'));

                return false;
            }
        } elseif ('url' === $this->trans_import_method) {
            $pattern="#(http|https)://(.*\.)?.*\..*#i";
            if (!preg_match($pattern, $this->trans_url)) {
                $this->error_msg(__('Invalid URL format', 'wpfanyi-import'));

                return false;
            }
        } else {
            $this->error_msg(__('Parameter error: unknown translation package import method', 'wpfanyi-import'));

            return false;
        }


        return true;
    }

    /**
     * Handling translation package import
     *
     * @since 1.0.0
     *
     * @return bool true on success or false on failure.
     */
    private function import_trans() {
        $trans_dir = WP_CONTENT_DIR . '/languages/' . $this->trans_type . 's';

        $trans_zip_file = 'file' === $this->trans_import_method ? @$this->trans_zip['tmp_name'] : download_url($this->trans_url, $timeout = 1000);

        if(!file_exists($trans_zip_file) && filesize($trans_zip_file) > 0) {
            if ('file' === $this->trans_import_method) {
                $this->error_msg(__('Translation package upload failed, please check whether the file system permissions are normal', 'wpfanyi-import'));
            } else {
                $this->error_msg(__('Translation package acquisition failed, please check whether the URL is valid', 'wpfanyi-import'));
            }

            return false;
        }

        if(!is_writable($trans_dir)) {
            /**
             * When it is found that the translation directory isn't writable, try to create a new directory.
             * If it fails, it proves that there is a problem with the file system permissions.
             */
            if (!mkdir($trans_dir, 0775, true)) {
                /* translators: %s: Translation storage directory */
                $this->error_msg(sprintf(__('The translation storage directory of this WordPress is not writable：%s', 'wpfanyi-import'), $trans_dir));

                return false;
            }
        }

        if (!class_exists('ZipArchive')) {
            $this->error_msg(__('This server doesn‘t support the decompression of Zip archives. Please contact the service provider of this server to enable the zip extension module function of PHP.', 'wpfanyi-import'));

            return false;
        }

        $zip = new ZipArchive;
        $res = $zip->open($trans_zip_file);
        if (!$res) {
            $this->error_msg(__('Failed to parse the Zip package. The Zip package may be damaged', 'wpfanyi-import'));
        }

        /**
         * @var array Save the file name of the Mo and Po files read from the zip package
         *
         * @since 1.0.0
         */
        $trans_file_list = [];

        /** Read valid Mo and Po files from the translation package to prevent code injection */
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            $pattern="#.*\.(mo|po)#i";
            if (preg_match($pattern, $filename)) {
                $trans_file_list[] = $filename;
            }
        }

        if (empty($trans_file_list)) {
            $this->error_msg(__('There are no valid Po and Mo files in the current translation package', 'wpfanyi-import'));

            return false;
        }

        $zip->extractTo($trans_dir, $trans_file_list);
        $zip->close();

        /** Try to delete the temporary file after all operations */
        @unlink($trans_zip_file);

        return true;
    }

    /**
     * Print success message
     *
     * @since 1.0.0
     *
     * @param string $msg Message
     */
    private function success_msg($msg) {
        echo "<div id='message' class='updated notice'><p>{$msg}</p></div>";
    }

    /**
     * Print fail message
     *
     * @since 1.0.0
     *
     * @param string $msg Message
     */
    private function error_msg($msg) {
        echo "<div id='message' class='updated error'><p>{$msg}</p></div>";
    }

}