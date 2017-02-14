<?php
/**
 * Created by IntelliJ IDEA.
 * User: SVz7
 * Date: 2017/2/14
 * Time: 13:06
 */
class RandDesc_Action extends Typecho_Widget implements Widget_Interface_Do
{

    public function action()
    {
    }

    public static function addDesc()
    {
        $conetent=$_POST['text'];
        $options=Helper::options();
        $db=Typecho_Db::get();
        $rows      = array(
            'id' => NULL,
            'description' => $conetent,
        );
        $db->query($db->insert('table.randdesc')->rows($rows));
        header('Location: ' . $options->adminUrl.'extending.php?panel=RandDesc%2Fpage%2Fdescs.php', false, 302);
        exit;
    }

}
