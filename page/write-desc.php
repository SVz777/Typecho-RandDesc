<?php
/**
 * Created by IntelliJ IDEA.
 * User: SVz7
 * Date: 2017/2/14
 * Time: 12:54
 */
include 'common.php';
include 'header.php';
include 'menu.php';
Typecho_Widget::widget('Widget_Contents_Page_Edit')->to($page);
?>
<div class="main">
    <div class="body container">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main typecho-post-area" role="form">
            <form action="<?php $options->adminUrl('randdesc/advanced')?>" method="post">
                <div class="col-mb-12 col-tb-9" role="main">
                    <p>
                        <label for="text" class="sr-only"><?php _e('页面内容'); ?></label>
                        <textarea style="height: <?php $options->editorSize(); ?>px" autocomplete="off" id="text" name="text" class="w-100 mono"><?php echo htmlspecialchars($page->text); ?></textarea>
                    </p>
                    <p class="submit clearfix">
                        <span class="right">
                            <button type="submit" name="do" value="publish" class="btn primary" id="btn-submit"><?php _e('添加段子'); ?></button>
                            <?php if ($options->markdown && (!$page->have() || $page->isMarkdown)): ?>
                                <input type="hidden" name="markdown" value="1" />
                            <?php endif; ?>
                        </span>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'footer.php';
?>
