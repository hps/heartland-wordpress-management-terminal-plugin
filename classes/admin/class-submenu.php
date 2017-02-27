<?php
/**
 * Creates the submenu item for the plugin.
 *
 * @package Custom_Admin_Settings
 */

/**
 * Creates the submenu item for the plugin.
 *
 * Registers a new menu item under 'Tools' and uses the dependency passed into
 * the constructor in order to display the page corresponding to this menu item.
 *
 * @package Custom_Admin_Settings
 */
class Submenu
{
    /**
    * A reference the class responsible for rendering the submenu page.
    *
    * @var    Submenu_Page
    * @access protected
    */
    protected $submenuPage;

    /**
    * Initializes all of the partial classes.
    *
    * @param Submenu_Page $submenuPage A reference to the class that renders the
    *                                  page for the plugin.
    */
    public function __construct($submenuPage)
    {
        $this->submenuPage = $submenuPage;
    }

    /**
    * Adds a submenu for this plugin to the 'Tools' menu.
    */
    public function init()
    {
        add_action('admin_menu', array($this, 'addAdminMenuPage'));
        include_once plugin_dir_path(__FILE__) . '../includes/Hps.php';
    }

    /**
    * Creates the submenu item and calls on the Submenu Page object to render
    * the actual contents of the page.
    */
    public function addAdminMenuPage()
    {
        add_menu_page(
            'Heartland Payment Systems Terminal',
            'Heartland',
            'administrator',
            __FILE__,
            array($this, 'adminHeartlandRoot'),
            plugins_url('/assets/images/heartland-icon.jpg', __FILE__)
        );

        add_submenu_page(
            __FILE__,
            'Heartland Transactions',
            'List Transactions',
            'administrator',
            'heartland-transactions',
            array($this, 'adminHeartlandTransactions')
        );

        add_submenu_page(
            __FILE__,
            'Heartland Options',
            'Options',
            'administrator',
            'heartland-options',
            array($this, 'adminHeartlandOptions')
        );
    }

    public function adminHeartlandRoot()
    {
        $title = 'Heartland Payment Systems';
        include_once plugin_dir_path(__FILE__)
            . '../../templates/admin/root.php';
    }

    public function adminHeartlandOptions()
    {
        $title = 'Heartland Payment Systems - Options';
        include_once plugin_dir_path(__FILE__)
            . '../../templates/admin/options.php';
    }

    public function adminHeartlandTransactions()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $id = isset($_GET['transaction']) ? $_GET['transaction'] : '';
        $command = isset($_POST['command']) ? $_POST['command'] : '';

        if (!empty($action) && !empty($id) && !empty($command)) {
            try {
                $this->processActionCommand($id, $action, $command);
                $this->addNotice('Transaction update succeeded.', 'notice-success');
            } catch (HpsException $e) {
                $this->addNotice(
                    sprintf('Transaction update failed. %s', $e->getMessage()),
                    'notice-error'
                );
            }
        }

        if ($action === 'manage') {
            if (empty($id)) {
                wp_redirect(get_admin_url(null, 'admin.php?page=heartland-transactions'));
                wp_die();
                return;
            }

            $this->performManageAction($id);
            return;
        }

        $this->writeTransactionTable($this->getReport());
    }

    protected function addNotice($message, $classes)
    {
        add_action('admin_notices', function () use ($message, $classes) {
            include_once plugin_dir_path(__FILE__)
                . '../../templates/admin/notice.php';
        });
    }

    protected function processActionCommand($id, $action, $command)
    {
        $service = new HpsFluentCreditService($this->getHeartlandConfiguration());

        if ($action === 'manage' && $command === 'void-transaction') {
            return $service->void()
                ->withTransactionId($id)
                ->execute();
        }

        if ($action === 'manage' && $command === 'refund-transaction') {
            $transaction = $service->get($id)->execute();
            $amount = !empty($transaction->settlementAmount)
                ? $transaction->settlementAmount
                : $transaction->authorizedAmount;
            $authAmount = isset($_POST['refund_amount']) && !empty($_POST['refund_amount'])
                ? $_POST['refund_amount']
                : false;

            $builder = $service->refund();

            if ($transaction->transactionStatus === 'A') {
                $builder = $service->reverse();
            }

            $builder = $builder
                ->withTransactionId($id)
                ->withCurrency('usd');

            if ($builder instanceof HpsCreditServiceRefundBuilder) {
                $builder = $builder->withAmount(
                    $authAmount !== false ? $authAmount : $amount
                );
            } elseif ($builder instanceof HpsCreditServiceReverseBuilder) {
                $builder = $builder
                    ->withAmount($transaction->authorizedAmount)
                    ->withAuthAmount(
                        $authAmount !== false ? ($amount - $authAmount) : null
                    );
            }

            return $builder->execute();
        }
    }

    protected function dataOrDash($data, $empty = null)
    {
        return empty($data) || $data === $empty
            ? '&mdash;' : $data;
    }

    protected function getReport()
    {
        $items = get_transient('heartland-transactions');

        if (false !== $items) {
            return json_decode($items);
        }

        $defaultTZ = date_default_timezone_get();
        date_default_timezone_set("UTC");
        $service = new HpsCreditService($this->getHeartlandConfiguration());
        $dateFormat = 'Y-m-d\TH:i:s.00\Z';
        $dateMinus10 = new DateTime();
        $dateMinus10->sub(new DateInterval('P10D'));
        $current = new DateTime();

        $items = $service->listTransactions($dateMinus10->format($dateFormat), $current->format($dateFormat));
        $filteredItems = $this->filterTransactions($items);

        if (!defined('HOUR_IN_SECONDS')) {
            define('HOUR_IN_SECONDS', 60 * 60);
        }
        ini_set('error_log', dirname(__FILE__) . '/error_log');
        set_transient('heartland-transactions', json_encode($filteredItems), HOUR_IN_SECONDS / 2);

        date_default_timezone_set($defaultTZ);
        return $filteredItems;
    }

    protected function filterTransactions($items)
    {
        return array_reverse(
            array_map(
                array($this, 'listTransactionsConvertExceptions'),
                array_filter($items, array($this, 'listTransactionsStripUndesiredTypes'))
            )
        );
    }

    protected function listTransactionsStripUndesiredTypes($item)
    {
        return
            substr($item->serviceName, 0, strlen('Report')) !== 'Report'
            && !in_array($item->serviceName, array('GetAttachments'));
    }

    protected function listTransactionsConvertExceptions($item)
    {
        if (empty($item->exceptions)) {
            return $item;
        }

        if (!empty($item->exceptions->hpsException)) {
            $item->exceptions->hpsException = (object)array(
                'code' => $item->exceptions->hpsException->getCode(),
                'message' => $item->exceptions->hpsException->getMessage(),
                'details' => $item->exceptions->hpsException->details,
            );
        }

        if (!empty($item->exceptions->cardException)) {
            $item->exceptions->cardException = (object)array(
                'code' => $item->exceptions->cardException->getCode(),
                'message' => $item->exceptions->cardException->getMessage(),
                'details' => $item->exceptions->cardException->details,
            );
        }

        return $item;
    }

    protected function performManageAction($transactionId)
    {
        $service = new HpsFluentCreditService($this->getHeartlandConfiguration());
        $title = 'Heartland Payment Systems - Manage Transaction';
        $transaction = null;

        try {
            $transaction = $service->get($transactionId)->execute();
        } catch (HpsException $e) {
            $transaction = false;
        }

        include_once plugin_dir_path(__FILE__)
            . '../../templates/admin/manage-transaction.php';
    }

    protected function performRefundAction($transactionId, $amount)
    {
        $service = new HpsFluentCreditService($this->getHeartlandConfiguration());
        return $service
            ->refund()
            ->withAmount($amount)
            ->withTransactionId($transactionId)
            ->withCurrency('usd')
            ->execute();
    }

    protected function transactionStatusPretty($transaction)
    {
        $status = $transaction->transactionStatus;

        switch ($status) {
            case 'A':
                $status = 'Active';
                break;
            case 'I':
                $status = 'Inactive';
                break;
            case 'C':
                $status = 'Cleared';
                break;
            case 'V':
                $status = 'Voided';
                break;
            case 'X':
                $status = 'Autovoided';
                break;
            case 'R':
                $status = 'Reversed';
                break;
            case 'T':
                $status = 'Timed-Out';
                break;
            default:
                break;
        }
        return $this->dataOrDash($status);
    }

    protected function getHeartlandConfiguration()
    {
        $secretApiKey = "skapi_cert_MYl2AQAowiQAbLp5JesGKh7QFkcizOP2jcX9BrEMqQ";
        $config = new HpsServicesConfig();
        $config->secretApiKey = $secretApiKey;
        $config->versionNumber = '1510';
        $config->developerId = '002914';
        return $config;
    }

    protected function writeTransactionTable($items)
    {
        $page = esc_url(get_admin_url(null, 'admin.php?page=heartland-transactions'));
        $title = 'Heartland Payment Systems - Transactions';
        $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
        $limit = 10;
        $offset = ($pagenum - 1) * $limit;
        $total = count($items);
        $numOfPages = ceil($total/$limit);
        $items = array_slice($items, $offset, $limit);
        include_once plugin_dir_path(__FILE__)
            . '../../templates/admin/list-transactions.php';
    }
}
