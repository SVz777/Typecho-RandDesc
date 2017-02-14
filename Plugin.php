<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * RandDesc
 *
 * @package RandDesc
 * @author SVz
 * @version 1.0
 * @link http://svz777.top
 */
class RandDesc_Plugin implements Typecho_Plugin_Interface
{
    private static $descs = 'RandDesc/page/descs.php';
    private static $write= 'RandDesc/page/write-desc.php';
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->___desc = array('RandDesc_Plugin', 'desc');
        $msg = RandDesc_Plugin::install();
        Helper::addPanel(1, self::$descs, 'RandDesc', 'RandDesc', 'administrator');
        Helper::addPanel(2, self::$write, 'write-desc', 'write-desc', 'administrator');
        Helper::addRoute('randdesc_advanced', __TYPECHO_ADMIN_DIR__ . 'randdesc/advanced', 'RandDesc_Action', 'addDesc');
        return _t($msg);
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
        $config = Typecho_Widget::widget('Widget_Options')->plugin('RandDesc');
        $isDrop = $config->isDrop;
        if ($isDrop == 0) {
            $db     = Typecho_Db::get();
            $prefix = $db->getPrefix();
            $db->query("DROP TABLE `" . $prefix . "randdesc`", Typecho_Db::WRITE);
        }
        Helper::removePanel(1, self::$descs);
        Helper::removePanel(2, self::$write);
        Helper::removeRoute('randdesc_advanced');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        //TODO 加入后台添加签名功能
        $isDrop = new Typecho_Widget_Helper_Form_Element_Radio(
            'isDrop', array(
            '0' => '删除',
            '1' => '不删除',
        ), '1', '删除数据表:', '请选择是否在禁用插件时，删除日志数据表');
        $form->addInput($isDrop);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function desc(){
        $db = Typecho_Db::get();
        //TODO 自动从数据库获取签名数量
        $rand=rand(0,143);
        $query= $db->select('description')->from('table.randdesc')->where('id = ?',$rand);
        echo '<p class="description" num="'.$rand.'">'.$db->fetchRow($query)["description"].'</p>';
    }

    public static function install(){
        $db = Typecho_Db::get();
        $type      = explode('_', $db->getAdapterName());
        $type      = array_pop($type);
        $prefix    = $db->getPrefix();
        $scripts   = "CREATE TABLE `typecho_randdesc` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MYISAM  DEFAULT CHARSET=%charset%;";
        $scripts = str_replace('typecho_', $prefix, $scripts);
        $scripts = str_replace('%charset%', 'utf8', $scripts);
        $scripts = explode(';', $scripts);
        try {
            foreach ($scripts as $script) {
                $script = trim($script);
                if ($script) {
                    $db->query($script, Typecho_Db::WRITE);
                }
            }
            return '成功创建数据表，插件启用成功';
        } catch (Typecho_Db_Exception $e) {
            $code = $e->getCode();
            if (('Mysql' == $type && $code == (1050 || '42S01'))) {
                $script = 'SELECT * from `' . $prefix . 'randdesc`';
                $db->query($script, Typecho_Db::READ);
                return '数据表已存在，插件启用成功';
            } else {
                throw new Typecho_Plugin_Exception('数据表建立失败，插件启用失败。错误号：' . $code);
            }
        }
    }
}
