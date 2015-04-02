<?php
/** @var $this \horses\responder\view\html\HtmlFileTemplate */
/** @var $username string */
/** @var $password string */
/** @var $message string */
?>
Please login <br/>
<?php if ($message): ?>
    <div style="border: 1px solid red;"><?= $this->escape($message) ?></div>
<?php endif; ?>
<form method="post">
    <label for="username">username:</label>
    <input type="text" id="username" name="username" value="<?= $this->escapeForAttribute($username) ?>"/><br/>
    <label for="password">password:</label>
    <input type="password" id="password" name="password" value="<?= $this->escapeForAttribute($password) ?>"/><br/>
    <input type="submit"/>
</form>
