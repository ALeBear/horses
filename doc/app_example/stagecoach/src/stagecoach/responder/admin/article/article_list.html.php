<?php
/** @var $this \stagecoach\responder\AbstractPartial */
/** @var $articles \stagecoach\journal\ArticleCollection */
?>
<form method="post">
</form>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            Articles
        </h3>
    </div>
    <div class="panel-body">
        <?php if (!count($articles)): ?>
            <div class="alert alert-info"><?= $this->translator->translate('no_records') ?></div>
        <?php else: ?>

            <?php //echo $this->partial('partials/pagination.phtml', ['page' => $this->page, 'data' => $paginationData, 'count' => $this->count, 'limit' => $this->limit]); ?>

            <table id="name" class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <?php foreach ($articles->getAccessibleFields() as $field): ?>
                        <?php
                        $classLinkUp = $classLinkDown = 'disabled';
                        $sortUrlUp = $sortUrlDown = '';

//                        $params                    = $this->params;
//                        $params['order_by']        = $field;
//                        $params['p']               = 1;
//                        unset($params['order_by_method']);
//
//                        $sortUrlUp   = $this->url($params + ['order_by_method' => 'asc']);
//                        $sortUrlDown = $this->url($params + ['order_by_method' => 'desc']);
//
//                        if (isset($this->params['order_by']) && $this->params['order_by'] == $field) {
//                            if (isset($this->params['order_by_method']) && 'asc' == $this->params['order_by_method']) {
//                                $classLinkUp = 'current';
//                                $classLinkDown = '';
//                            } else {
//                                $classLinkUp = '';
//                                $classLinkDown = 'current';
//                            }
//                        }
                        ?>
                        <th>
                            <?= $this->translator->translate(sprintf('%s.%s', $articles->getEntityCode(), $field)) ?>
                            <span class="sort-links">
                            <a href="<?= $sortUrlUp; ?>" class="<?= $classLinkUp; ?>"><span class="glyphicon glyphicon-chevron-up"></span></a>
                            <a href="<?= $sortUrlDown; ?>" class="<?= $classLinkDown; ?>"><span class="glyphicon glyphicon-chevron-down"></span></a>
                        </span>
                        </th>
                    <?php endforeach; ?>
                    <th class="row-action"></th>
                </tr>
                </thead>
                <tbody class="selectable">
                <?php foreach ($articles as $entity):?>
                    <tr>
                        <?php foreach ($articles->getAccessibleFields() as $field): ?>
                            <td><?= call_user_func([$entity, 'get' . $field]) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
