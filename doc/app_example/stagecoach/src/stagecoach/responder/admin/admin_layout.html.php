<?php
namespace stagecoach\responder\admin;

/** @var $this \stagecoach\responder\admin\AdminLayout */
/** @var $menu string */
/** @var $content string */
?>
<div style="float: left; padding: 10px;">
    <?= $this->renderPartial(new AdminMenuPartial($this)) ?>
</div>
<div style="float: left; padding: 10px;">
    <?= $content ?>
</div>
