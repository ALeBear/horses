<?php
/** @var $this \stagecoach\responder\AbstractPartial */
/** @var $username string */
/** @var $password string */
/** @var $message string */
?>
<?php if ($message): ?>
    <div style="border: 1px solid red;"><?= $this->escape($message) ?></div>
<?php endif; ?>
<form method="post">
    <label for="username"><?= $this->translator->translate('username') ?></label>
    <input type="text" id="username" name="username" value="<?= $this->escapeForAttribute($username) ?>"/><br/>
    <label for="password"><?= $this->translator->translate('password') ?></label>
    <input type="password" id="password" name="password" value="<?= $this->escapeForAttribute($password) ?>"/><br/>
    <input type="submit"/>
</form>
