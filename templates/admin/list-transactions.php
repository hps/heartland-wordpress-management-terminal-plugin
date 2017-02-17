<table>
    <tr>
        <th>
        </th>
    </tr>
    <?php foreach($items as $o): ?>
        <?php
        $offset = date('Z');
        $localTs = strtotime($o->transactionUTCDate) + $offset;
        $localTime = date('Y-m-d g:i A', $localTs);
        ?>
        <tr>
            <td><?= $localTime ?></td>
            <td><?= $o->amount ?></td>
            <td><?= $o->settlementAmount ?></td>
            <td><?= $o->originalTransactionId ?></td>
            <td><?= $o->maskedCardNumber ?></td>
            <td><?= $o->transactionType ?></td>
            <td><?= $o->transactionId ?></td>
            <td><?= $o->responseText ?></td>
            <td><a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=heartland-list-transactions&a=refund&amount=' . $o->amount . '&t=' . $o->transactionId)); ?>">Refund</a></td>
        </tr>
    <?php endforeach; ?>
</table>