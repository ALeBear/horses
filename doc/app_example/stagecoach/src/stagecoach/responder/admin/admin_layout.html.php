<?php
namespace stagecoach\responder\admin;

/** @var $this \stagecoach\responder\admin\AdminLayout */
/** @var $menu string */
/** @var $content string */
/** @var $message string */
?>
<div style="float: left; padding: 10px;">
    <?= $this->renderPartial(new AdminMenuPartial($this)) ?>
</div>
<div style="float: left; padding: 10px;">
    <?php if ($message): ?>
        <div style="border: 1px solid red;"><?= $this->escape($message) ?></div>
    <?php endif; ?>
    <?= $content ?>
</div>
