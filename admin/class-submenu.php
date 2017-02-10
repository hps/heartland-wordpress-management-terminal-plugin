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
class Submenu {

	/**
	 * A reference the class responsible for rendering the submenu page.
	 *
	 * @var    Submenu_Page
	 * @access private
	 */
	private $submenu_page;

	/**
	 * Initializes all of the partial classes.
	 *
	 * @param Submenu_Page $submenu_page A reference to the class that renders the
	 *																	 page for the plugin.
	 */
	public function __construct( $submenu_page ) {
		$this->submenu_page = $submenu_page;
	}

	/**
	 * Adds a submenu for this plugin to the 'Tools' menu.
	 */
	public function init() {
		 add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );
		 include_once plugin_dir_path( __FILE__ ) . 'heartland-php-sdk/Hps.php';
	}

	/**
	 * Creates the submenu item and calls on the Submenu Page object to render
	 * the actual contents of the page.
	 */
	public function add_admin_menu_page() {
		add_menu_page(
			'Heartland Payment Systems Terminal', 
			'Heartland', 
			'administrator', 
			__FILE__, 
			array($this, 'admin_heartland_root_element'), 
			plugins_url('/heartland.jpg', __FILE__)
		);

		add_submenu_page(
			__FILE__,
			'Heartland List Transactions',
			'List Transactions',
			'administrator',
			'heartland-list-transactions',
			array($this, 'admin_heartland_list_transactions')
		);
	}

	public function admin_heartland_root_element() {
		echo "test";
	}

	public function admin_heartland_list_transactions() {
		if (isset($_GET['a']) && isset($_GET['t'])) {
			$this->perform_heartland_action($_GET['a'], $_GET['t']);
		}

		date_default_timezone_set("UTC");
		$heartland_service = new HpsCreditService($this->get_heartland_configuration());
		$dateFormat = 'Y-m-d\TH:i:s.00\Z';
        $dateMinus10 = new DateTime();
        $dateMinus10->sub(new DateInterval('P1D'));
        $current = new DateTime();

		$filtered_items = null;
		$items = null;

		$items = $heartland_service->listTransactions($dateMinus10->format($dateFormat), $current->format($dateFormat));
		$filtered_items = $this->filter_transactions($items);

		date_default_timezone_set('America/New_York');

		$this->write_transaction_table($filtered_items);
	}

	private function filter_transactions($items) {
		return array_reverse($items);
	}

	private function perform_heartland_action($action_type, $transaction_id) {
		if ($action_type === 'refund') {
			if (!isset($_GET['amount']))
				return;
				
			$amount = $_GET['amount'];
			$this->perform_refund_action($transaction_id, $amount);
		}

		return;
	}

	private function perform_refund_action($transaction_id, $amount) {
		$heartland_service = new HpsFluentCreditService($this->get_heartland_configuration());
		$heartland_service
			->refund()
			->withAmount($amount)
			->withTransactionId($transaction_id)
			->withCurrency('usd')
			->execute();
		;
	}

	private static function get_heartland_configuration()
    {
        $secretApiKey = "skapi_cert_MYl2AQAowiQAbLp5JesGKh7QFkcizOP2jcX9BrEMqQ";
        $config = new HpsServicesConfig();
        $config->secretApiKey = $secretApiKey;
        $config->versionNumber = '1510';
        $config->developerId = '002914';
        return $config;
    }

	private function write_transaction_table($items) {
		?>

		<table><tr><th>
		<?php foreach($items as $o): ?>
		<?php
			$offset = date('Z');
			$local_ts = strtotime($o->transactionUTCDate) + $offset;
			$local_time = date('Y-m-d g:i A', $local_ts);
		?>
		<tr>
		<td><?= $local_time ?></td>
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

		<?php
	}
}
