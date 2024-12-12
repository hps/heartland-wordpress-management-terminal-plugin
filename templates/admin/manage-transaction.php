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
    <?php wp_nonce_field('heartland-manage-transaction-' . isset($_GET['transaction']) ?? ''); ?>
    <input type="hidden" name="transaction" value="<?php echo esc_attr(isset($_GET['transaction']) ?? '') ?>">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <?php if (false === $transaction) : ?>
                <div id="post-body-content" style="position: relative">
                    <div class="postarea">
                        <div class="notice notice-error">
                            <p>
                                <?php esc_attr_e('Error fetching transaction details for transaction', 'heartland-management-terminal') ?>
                                <code><?php echo esc_attr(wp_unslash($_GET['transaction'])) ?></code>
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
                                        <td><strong><?php echo esc_attr(strtoupper(substr($property, 0, 1))) . esc_attr(substr($property, 1)) ?></strong></td>
                                        <td><?php echo esc_attr($this->dataOrDash($value)) ?></td>
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
                            <span><?php esc_attr_e('Information', 'heartland-management-terminal') ?></span>
                        </h2>
                        <div class="inside">
                            <div class="misc-pub-section">
                                <?php esc_attr_e('Transaction ID', 'heartland-management-terminal') ?>:
                                <strong><?php echo esc_attr(wp_unslash($_GET['transaction'])) ?></strong>
                            </div>
                            <div class="misc-pub-section">
                                <?php esc_attr_e('Status', 'heartland-management-terminal') ?>:
                                <strong><?php echo esc_attr($this->transactionStatusPretty($transaction)) ?></strong>
                            </div>
                            <div class="misc-pub-section">
                                <?php esc_attr_e('Type', 'heartland-management-terminal') ?>:
                                <strong><?php echo esc_attr($transaction->serviceName) ?></strong>
                            </div>
                            <div class="misc-pub-section">
                                <?php esc_attr_e('Gateway Response', 'heartland-management-terminal') ?>:
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
                                <strong><?php echo esc_attr($this->dataOrDash($gatewayResponse)) ?></strong>
                            </div>
                            <div class="misc-pub-section">
                                <?php esc_attr_e('Issuer/Processor Response', 'heartland-management-terminal') ?>:<br>
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
                                <strong><?php echo esc_attr($this->dataOrDash($issuerResponse)) ?></strong>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div id="infodiv" class="postbox">
                        <h2 class="hndle">
                            <span><?php esc_attr_e('Actions', 'heartland-management-terminal') ?></span>
                        </h2>
                        <div class="inside">
                            <?php if ($this->transactionCanVoid($transaction)) : ?>
                                <div class="misc-pub-section">
                                    <p><?php esc_attr_e('Void', 'heartland-management-terminal') ?>:</p>
                                    <button name="command" value="void-transaction" class="button"><?php esc_attr_e('Void', 'heartland-management-terminal') ?></button>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->transactionCanRefund($transaction)) : ?>
                                <div class="misc-pub-section">
                                    <p><?php esc_attr_e('Refund', 'heartland-management-terminal') ?>:</p>
                                    <div>
                                        <label for="refund-transaction-amount"><?php esc_attr_e('Transaction amount', 'heartland-management-terminal') ?>:</label>
                                        <input type="text" id="refund-transaction-amount" disabled="disabled" value="<?php echo !empty($transaction->settlementAmount) ? esc_attr($transaction->settlementAmount) : esc_attr($transaction->authorizedAmount) ?>">
                                    </div>
                                    <div>
                                        <label for="refund-refund-amount"><?php esc_attr_e('Refund amount', 'heartland-management-terminal') ?>:</label>
                                        <input type="text" id="refund-refund-amount" name="refund_amount">
                                    </div>
                                    <p>
                                        <button name="command" value="refund-transaction" class="button"><?php esc_attr_e('Refund', 'heartland-management-terminal') ?></button>
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
