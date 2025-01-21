<?php require 'header.php'; ?>

<h2 class="screen-reader-text"><?php esc_attr_e('Filter transaction list', 'heartland-management-terminal') ?></h2>

<ul class="subsubsub">
    <li class="all">
        <a class="current" href="<?php echo esc_attr($page) ?>">
            <?php esc_attr_e('All', 'heartland-management-terminal') ?> (<?php echo esc_attr($total) ?>)
        </a>
    </li>
</ul>

<form method="get" action="<?php echo esc_attr($page) ?>">
    <input type="hidden" name="page" value="heartland-transactions">
    <input type="hidden" name="action" value="manage">
    <p class="search-box">
        <label class="screen-reader-text" for="post-search-input"><?php esc_attr_e('Goto Transaction', 'heartland-management-terminal') ?>:</label>
        <input id="post-search-input" name="transaction" value="" type="search" placeholder="<?php esc_attr_e('Transaction ID', 'heartland-management-terminal') ?>">
        <input id="search-submit" class="button" value="<?php esc_attr_e('Goto Transaction', 'heartland-management-terminal') ?>" type="submit">
    </p>
</form>

<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th class="manage-column column-primary"><?php esc_attr_e('ID', 'heartland-management-terminal') ?></th>
            <th class="manage-column"><?php esc_attr_e('Date', 'heartland-management-terminal') ?></th>
            <th class="manage-column"><?php esc_attr_e('Amount', 'heartland-management-terminal') ?></th>
            <th class="manage-column"><?php esc_attr_e('Settled', 'heartland-management-terminal') ?></th>
            <th class="manage-column"><?php esc_attr_e('Type', 'heartland-management-terminal') ?></th>
            <th class="manage-column"><?php esc_attr_e('Reponse', 'heartland-management-terminal') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $o) : ?>
            <?php
            $offset = gmdate('Z');
            $localTs = strtotime($o->transactionUTCDate) + $offset;
            $localDate = gmdate('Y-m-d', $localTs);
            ?>
            <tr>
                <td>
                    <?php echo esc_attr($o->transactionId) ?>
                    <div class="row-actions">
                        <span class="manage">
                            <a href="<?php echo esc_attr($page) ?>&action=manage&transaction=<?php echo esc_attr($o->transactionId) ?>">
                                <?php esc_attr_e('Manage', 'heartland-management-terminal') ?>
                            </a>
                        </span>
                    </div>
                </td>
                <td><?php echo esc_attr($localDate) ?></td>
                <td><?php echo esc_attr($this->dataOrDash($o->amount, '0')) ?></td>
                <td><?php echo esc_attr($this->dataOrDash($o->settlementAmount, '0')) ?></td>
                <td><?php echo esc_attr($o->serviceName) ?></td>
                <td><?php echo esc_attr($this->dataOrDash($o->responseText)) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$pageLinks = paginate_links(array(
    'base' => add_query_arg('pagenum', '%#%'),
    'format' => '',
    'prev_text' => __('&laquo;', 'heartland-management-terminal'),
    'next_text' => __('&raquo;', 'heartland-management-terminal'),
    'total' => $numOfPages,
    'current' => $pagenum,
));
?>

<?php if ($pageLinks) : ?>
    <div class="tablenav">
        <div class="tablenav-pages" style="margin: 1em 0">
            <?php echo esc_attr($pageLinks) ?>
        </div>
    </div>
<?php endif; ?>

<?php require 'footer.php'; ?>
