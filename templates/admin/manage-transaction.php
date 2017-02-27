<?php require 'header.php'; ?>

<?php
$ignoredProperties = array(
    'transactionId',
    'transactionStatus',
    'transactionType',
    'exceptions',
    'serviceName',
    'tokenData',
    'responseCode',
    'responseText',
);
?>

<?php do_action('admin_notices'); ?>

<form method="post">
    <?php wp_nonce_field('heartland-manage-transaction-' . $_GET['transaction']); ?>
    <input type="hidden" name="transaction" value="<?php echo $_GET['transaction'] ?>">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <?php if (false === $transaction) : ?>
                <div id="post-body-content" style="position: relative">
                    <div class="postarea">
                        <div class="notice notice-error">
                            <p>
                                Error fetching transaction details for transaction
                                <code><?php echo $_GET['transaction'] ?></code>
                            </p>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <!-- content -->
                <div id="post-body-content" style="position: relative">
                    <div class="postarea">
                        <table class="wp-list-table widefat fixed striped">
                            <tbody>
                                <?php
                                $data = (array)$transaction;
                                ksort($data);
                                ?>
                                <?php foreach ($data as $property => $value) : ?>
                                    <?php
                                    if (in_array($property, $ignoredProperties)) {
                                        continue;
                                    }

                                    if ($value instanceof HpsTransactionHeader) {
                                        continue;
                                    }
                                    ?>
                                    <tr>
                                        <td><strong><?php echo strtoupper(substr($property, 0, 1)) . substr($property, 1) ?></strong></td>
                                        <td><?php echo $this->dataOrDash($value) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- meta boxes -->
                <div id="postbox-container-1" class="postbox-container">
                    <div id="infodiv" class="postbox">
                        <h2 class="hndle">
                            <span>Information</span>
                        </h2>
                        <div class="inside">
                            <div class="misc-pub-section">
                                Transaction ID:
                                <strong><?php echo $_GET['transaction'] ?></strong>
                            </div>
                            <div class="misc-pub-section">
                                Status:
                                <strong><?php echo $this->transactionStatusPretty($transaction) ?></strong>
                            </div>
                            <div class="misc-pub-section">
                                Type:
                                <strong><?php echo $transaction->serviceName ?></strong>
                            </div>
                            <div class="misc-pub-section">
                                Gateway Response:
                                <?php
                                $gatewayResponse = null;
                                if ($transaction->gatewayResponse()->message) {
                                    $gatewayResponse = sprintf(
                                        '%s (%s)',
                                        $transaction->gatewayResponse()->message,
                                        $transaction->gatewayResponse()->code
                                    );
                                }
                                ?>
                                <strong><?php echo $this->dataOrDash($gatewayResponse) ?></strong>
                            </div>
                            <div class="misc-pub-section">
                                Issuer/Processor Response:<br>
                                <?php
                                $issuerResponse = null;
                                if ($transaction->responseCode) {
                                    $issuerResponse = sprintf(
                                        '%s (%s)',
                                        $transaction->responseText,
                                        $transaction->responseCode
                                    );
                                }
                                ?>
                                <strong><?php echo $this->dataOrDash($issuerResponse) ?></strong>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div id="infodiv" class="postbox">
                        <h2 class="hndle">
                            <span>Actions</span>
                        </h2>
                        <div class="inside">
                            <?php if (in_array($transaction->transactionStatus, array('A'))) : ?>
                                <div class="misc-pub-section">
                                    <p>Void:</p>
                                    <button name="command" value="void-transaction" class="button">Void</button>
                                </div>
                            <?php endif; ?>
                            <?php if (in_array($transaction->transactionStatus, array('A', 'C'))) : ?>
                                <div class="misc-pub-section">
                                    <p>Refund:</p>
                                    <div>
                                        <label for="refund-transaction-amount">Transaction amount:</label>
                                        <input type="text" id="refund-transaction-amount" disabled="disabled" value="<?php echo !empty($transaction->settlementAmount) ? $transaction->settlementAmount : $transaction->authorizedAmount ?>">
                                    </div>
                                    <div>
                                        <label for="refund-refund-amount">Refund amount:</label>
                                        <input type="text" id="refund-refund-amount" name="refund_amount">
                                    </div>
                                    <p>
                                        <button name="command" value="refund-transaction" class="button">Refund</button>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>
</form>

<?php require 'footer.php'; ?>
