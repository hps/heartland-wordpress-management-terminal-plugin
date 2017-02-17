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
            array($this, 'adminHeartlandRootElement'),
            plugins_url('/heartland.jpg', __FILE__)
        );

        add_submenu_page(
            __FILE__,
            'Heartland List Transactions',
            'List Transactions',
            'administrator',
            'heartland-list-transactions',
            array($this, 'adminHeartlandListTransactions')
        );
    }

    public function adminHeartlandRootElement()
    {
        echo "test";
    }

    public function adminHeartlandListTransactions()
    {
        if (isset($_GET['a']) && isset($_GET['t'])) {
            $this->performHeartlandAction($_GET['a'], $_GET['t']);
        }

        date_default_timezone_set("UTC");
        $service = new HpsCreditService($this->getHeartlandConfiguration());
        $dateFormat = 'Y-m-d\TH:i:s.00\Z';
        $dateMinus10 = new DateTime();
        $dateMinus10->sub(new DateInterval('P1D'));
        $current = new DateTime();

        $filteredItems = null;
        $items = null;

        $items = $service->listTransactions($dateMinus10->format($dateFormat), $current->format($dateFormat));
        $filteredItems = $this->filterTransactions($items);

        date_default_timezone_set('America/New_York');

        $this->writeTransactionTable($filteredItems);
    }

    protected function filterTransactions($items)
    {
        return array_reverse($items);
    }

    protected function performHeartlandAction($action_type, $transaction_id)
    {
        if ($action_type === 'refund') {
            if (!isset($_GET['amount'])) {
                return;
            }

            $amount = $_GET['amount'];
            $this->performRefundAction($transaction_id, $amount);
        }

        return;
    }

    protected function performRefundAction($transaction_id, $amount)
    {
        $service = new HpsFluentCreditService($this->getHeartlandConfiguration());
        return $service
            ->refund()
            ->withAmount($amount)
            ->withTransactionId($transaction_id)
            ->withCurrency('usd')
            ->execute();
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
        include_once plugin_dir_path(__FILE__) . '../../templates/admin/list-transactions.php';
    }
}
