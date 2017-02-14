<?php
/**
 * Created by IntelliJ IDEA.
 * User: SVz7
 * Date: 2017/2/11
 * Time: 13:41
 */
include 'common.php';
include 'header.php';
include 'menu.php';
require_once dirname(__FILE__) . '/../lib/Page.php';

$db = Typecho_Db::get();
$prefix = $db->getPrefix();
$req  = Typecho_Request::getInstance();
$page=$req->get('page',1);
$pageSize = 10;
$all = $db->fetchAll($db->select()->from('table.randdesc')
    ->order('table.randdesc.id', Typecho_Db::SORT_DESC));

$count=count($all);
$pageCount = ceil( $count/$pageSize );

$current = $db->fetchAll($db->select()->from('table.randdesc')
    ->page($page, $pageSize)
    ->order('table.randdesc.id', Typecho_Db::SORT_DESC));

$options= Helper::options();
$menu->addLink=$options->adminUrl.'extending.php?panel=RandDesc%2Fpage%2Fwrite-desc.php';
?>
<div class="main">
    <div class="body container">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main" role="main">
            <div class="col-mb-12 typecho-list">
                <div class="typecho-list-operate clearfix">
                    <table class="typecho-list-table">
                        <colgroup>
                            <col width="5%"/>
                            <col width="95%"/>
                        </colgroup>
                        <thead>
                        <tr>
                            <th><?php _e('序号'); ?></th>
                            <th><?php _e('段子'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($current as $line): ?>
                            <tr>
                                <td><?php echo $line['id']; ?></td>
                                <td><?php echo $line['description']; ?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <ul class="typecho-pager" style="float: none">
                        <?php
                        $pager= new Page($pageSize, $count, $page, 10);
                        echo $pager->show();
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

