<?php
/**
 Plugin Name: WooCommerce Generate Order plugin
 Description: 
 Version: 2.0

 @package deals
 */

/**
 * Add generate the order.
 */
add_action('admin_menu', 'generate_button_menu');

function generate_button_menu()
{
    add_menu_page('Generate The Order Page', 'Generate The Order', 'manage_options', 'generate-button-slug', 'generate_button_admin_page');
}

function generate_button_admin_page()
{

    // General check for user permissions.
    if (! current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient pilchards to access this page.'));
    }
    // Check whether the button has been pressed AND also check the nonce
    if (isset($_POST['generate_button']) && check_admin_referer('generate_button_clicked')) {
        // the button has been pressed AND we've passed the security check
        generate_button_action();
    }

    // Start building the page

    echo '<div class="wrap">';
    ?>
<div>
  <?php screen_icon(); ?>
  <h2>Generate The Order</h2>
	<form method="post"
		action="options-general.php?page=generate-button-slug">
		<table>
			<tr valign="top">
				<th scope="row"><label for="amount">Amount $</label></th>
				<td><input type="text" id="amount" name="amount" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="date">Order date&time</label></th>
				<td><input type="text" id="date" name="date" /></td>format date:
				2018-04-25 16:18:15
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="card">Card Number</label></th>
				<td><input type="text" id="card" name="card" /></td>last 4 digits of
				the card
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="shipping_method">Carrier</label></th>
				<td><input type="text" id="shipping_method" name="shipping_method" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="tracking_number">Tracking number</label></th>
				<td><input type="text" id="tracking_number" name="tracking_number" /></td>
			</tr>
		</table>
		<p>Billing address</p>
		<table>
			<tr valign="top">
				<th scope="row"><label for="first_name">First Name</label></th>
				<td><input type="text" id="first_name" name="first_name" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="last_name">Last Name</label></th>
				<td><input type="text" id="last_name" name="last_name" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="email">Email</label></th>
				<td><input type="text" id="email" name="email" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="phone">Phone</label></th>
				<td><input type="text" id="phone" name="phone" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="address">Address</label></th>
				<td><input type="text" id="address" name="address" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="city">City</label></th>
				<td><input type="text" id="city" name="city" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="state">State</label></th>
				<td><input type="text" id="state" name="state" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="postcode">ZipCode</label></th>
				<td><input type="text" id="postcode" name="postcode" /></td>
			</tr>
		</table>
		<p>Shipping address</p>
		<table>
			<tr valign="top">
				<th scope="row"><label for="ship_first_name">First Name</label></th>
				<td><input type="text" id="ship_first_name" name="ship_first_name" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="ship_last_name">Last Name</label></th>
				<td><input type="text" id="ship_last_name" name="ship_last_name" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="ship_address">Address</label></th>
				<td><input type="text" id="ship_address" name="ship_address" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="ship_city">City</label></th>
				<td><input type="text" id="ship_city" name="ship_city" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="ship_state">State</label></th>
				<td><input type="text" id="ship_state" name="ship_state" /></td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<th scope="row"><label for="ship_postcode">ZipCode</label></th>
				<td><input type="text" id="ship_postcode" name="ship_postcode" /></td>
			</tr>
		</table>
   <?php  wp_nonce_field('generate_button_clicked'); ?>
  <input type="hidden" value="true" name="generate_button" />
  <?php  submit_button('Generate the order'); ?>  
  </form>
</div>
<?php
}

function generate_button_action()
{
    echo "</br>";
    global $woocommerce;

    $address = array(
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'company' => '',
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address_1' => $_POST['address'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'postcode' => $_POST['postcode'],
        'country' => 'US'
    );
    $shipping_address = array(
        'first_name' => $_POST['ship_first_name'],
        'last_name' => $_POST['ship_last_name'],
        'company' => '',
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address_1' => $_POST['ship_address'],
        'city' => $_POST['ship_city'],
        'state' => $_POST['ship_state'],
        'postcode' => $_POST['ship_postcode'],
        'country' => 'US'
    );

    // Now we create the order
    $order = wc_create_order();

    global $wpdb;
    $products = $wpdb->get_results("select ID from $wpdb->posts where post_status = 'publish' and post_type = 'product' and post_title not like '%Discount%'", ARRAY_A);
    $draft_product = $wpdb->get_results("select ID from $wpdb->posts where post_status = 'draft' and post_type = 'product'", ARRAY_A);
    $count = count($draft_product);
    if ($count == 0) {
        echo "there is not the draft product";
        die();
    }
    if (empty($_POST['amount']) or $_POST['amount'] == 0) {
        echo "amount not shoud be null";
        die();
    }

    $amount = $_POST['amount'];
    $draft_product_id = $draft_product[0]['ID'];
    $diff_amount = $amount - 10;
    $tmp_amount = 0;
    while ($tmp_amount <= $diff_amount) {
        $post_id = $products[array_rand($products)]['ID'];
        if ($amount > 100) {
            $qty = rand(1, 6);
        } else {
            $qty = rand(1, 2);
        }
        $tmp_amount = $tmp_amount + ((get_post_meta($post_id, '_price')[0]) * $qty);
        if ($tmp_amount <= $diff_amount) {
            $order->add_product(get_product($post_id), $qty);
            $true_amount = $tmp_amount;
        } else {
            break;
        }
    }
    do {
        $end_product_price_max = $amount - $true_amount - 3;
        $end_product_price_min = $amount - $true_amount;
        $post_id = $products[array_rand($products)]['ID'];
        $product_price = get_post_meta($post_id, '_price')[0];
        if ($product_price < ($amount - $true_amount)) {
            $order->add_product(get_product($post_id), 1);
            $true_amount = $true_amount + $product_price;
        }
    } while (($amount - $true_amount) > 3);

    update_post_meta($draft_product_id, '_price', ($amount - $true_amount));
    update_post_meta($draft_product_id, '_regular_price', ($amount - $true_amount));

    $wpdb->update($wpdb->posts, array(
        'post_status' => 'publish'
    ), array(
        'ID' => $draft_product_id
    ));
    $order->add_product(get_product($draft_product_id), 1);

    $order->set_address($address, 'billing');
    $order->set_address($shipping_address, 'shipping');
    $shipping = new WC_Shipping_Rate(1, $_POST['shipping_method'] . ': Tracking Number: ' . $_POST['tracking_number'], 0, 1, 1);
    $order->add_shipping($shipping);

    $order->calculate_shipping();
    $order->calculate_totals();
    $order->update_status('completed');

    $order_id = $order->get_id();
    update_post_meta($order_id, '_payment_method', 'elviauthorized');
    update_post_meta($order_id, '_payment_method_title', 'Visa Mastercard Pay with Card:XXXX XXXX XXXX ' . $_POST['card']);
    if (! empty($_POST['date'])) {
        $wpdb->update($wpdb->posts, array(
            'post_date' => $_POST['date']
        ), array(
            'ID' => $order_id )
     );
	}	

    echo "</br>";
    echo "<h3>Generated order </h3>".$order_id;
}




