<?php require 'header.php'; ?>

<h2 class="screen-reader-text">Filter transaction list</h2>

<ul class="subsubsub">
    <li class="all">
        <a class="current" href="<?php echo $page ?>">
            All (<?php echo $total ?>)
        </a>
    </li>
</ul>

<form method="get" action="<?php echo $page ?>">
    <input type="hidden" name="page" value="heartland-transactions">
    <input type="hidden" name="action" value="manage">
    <p class="search-box">
        <label class="screen-reader-text" for="post-search-input">Goto Transaction:</label>
        <input id="post-search-input" name="transaction" value="" type="search" placeholder="Transaction ID">
        <input id="search-submit" class="button" value="Goto Transaction" type="submit">
    </p>
</form>

<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th class="manage-column column-primary">ID</th>
            <th class="manage-column">Date</th>
            <th class="manage-column">Amount</th>
            <th class="manage-column">Settled</th>
            <th class="manage-column">Type</th>
            <th class="manage-column">Reponse</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $o) : ?>
            <?php
            $offset = date('Z');
            $localTs = strtotime($o->transactionUTCDate) + $offset;
            $localDate = date('Y-m-d', $localTs);
            ?>
            <tr>
                <td>
                    <?php echo $o->transactionId ?>
                    <div class="row-actions">
                        <span class="manage">
                            <a href="<?php echo $page ?>&action=manage&transaction=<?php echo $o->transactionId ?>">
                                Manage
                            </a>
                        </span>
                    </div>
                </td>
                <td><?php echo $localDate ?></td>
                <td><?php echo $this->dataOrDash($o->amount, '0') ?></td>
                <td><?php echo $this->dataOrDash($o->settlementAmount, '0') ?></td>
                <td><?php echo $o->serviceName ?></td>
                <td><?php echo $this->dataOrDash($o->responseText) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$pageLinks = paginate_links(array(
    'base' => add_query_arg('pagenum', '%#%'),
    'format' => '',
    'prev_text' => __('&laquo;', 'text-domain'),
    'next_text' => __('&raquo;', 'text-domain'),
    'total' => $numOfPages,
    'current' => $pagenum,
));
?>

<?php if ($pageLinks) : ?>
    <div class="tablenav">
        <div class="tablenav-pages" style="margin: 1em 0">
            <?php echo $pageLinks ?>
        </div>
    </div>
<?php endif; ?>

<?php require 'footer.php'; ?>
