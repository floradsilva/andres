<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<html>
<head>
    <?php
        $handes = array(
            'openpos.admin.receipt.ejs'
        );
        wp_print_scripts($handes);
    ?>

    <?php echo $data['html_header']; ?>
</head>
<body style="margin:0;">

<script type="text/javascript">
    (function($) {

        $(document).ready(function(){
            <?php if(!$data['order_json'] || $data['order_json'] == ''): ?>
            var  order = {"id":1556507516254,"order_number":5480,"title":"","items":[{"id":1556507556577,"name":"Beanie","sub_name":"","dining":"","price":20,"price_incl_tax":21,"product_id":13,"final_price":20,"final_price_incl_tax":21,"options":[],"bundles":[],"variations":[],"discount_amount":0,"discount_type":"fixed","final_discount_amount":0,"qty":1,"refund_qty":0,"exchange_qty":0,"refund_total":0,"tax_amount":1,"total_tax":1,"total":20,"total_incl_tax":21,"product":{"name":"Beanie","id":13,"parent_id":13,"sku":"woo-beanie","qty":40,"manage_stock":true,"stock_status":"instock","barcode":"00000000013","image":"http://localhost.com/dev/openpos/wordpress/wp-content/uploads/2018/05/beanie-2.jpg","price":20,"final_price":20,"special_price":"","regular_price":"20","sale_from":null,"sale_to":null,"status":"publish","categories":["19","16"],"tax":[{"rate":0,"shipping":"no","compound":"no","rate_id":0,"label":"Tax on POS"}],"tax_amount":0,"price_included_tax":true,"group_items":[],"variations":[],"options":[],"bundles":[],"display_special_price":false,"price_display_html":"<span class=\"woocommerce-Price-amount amount\"><span class=\"woocommerce-Price-currencySymbol\">&#36;</span>20.00</span>","display":true},"option_pass":true,"note":"","parent_id":0,"seller_id":0,"seller_name":"","item_type":"","has_custom_discount":false,"disable_qty_change":false,"promotion_added":0},{"id":1556507560383,"name":"Cap","sub_name":"","dining":"","price":16,"price_incl_tax":16.8,"product_id":15,"final_price":16,"final_price_incl_tax":16.8,"options":[],"bundles":[],"variations":[],"discount_amount":0,"discount_type":"fixed","final_discount_amount":0,"qty":1,"refund_qty":0,"exchange_qty":0,"refund_total":0,"tax_amount":0.8,"total_tax":0.8,"total":16,"total_incl_tax":16.8,"product":{"name":"Cap","id":15,"parent_id":15,"sku":"woo-cap","qty":46,"manage_stock":true,"stock_status":"instock","barcode":"00000000015","image":"http://localhost.com/dev/openpos/wordpress/wp-content/uploads/2018/05/cap-2.jpg","price":16,"final_price":16,"special_price":"16","regular_price":"18","sale_from":null,"sale_to":{"date":"2030-12-30 17:00:00.000000","timezone_type":1,"timezone":"+00:00"},"status":"publish","categories":["19","16"],"tax":[{"rate":0,"shipping":"no","compound":"no","rate_id":0,"label":"Tax on POS"}],"tax_amount":0,"price_included_tax":true,"group_items":[],"variations":[],"options":[],"bundles":[],"display_special_price":false,"price_display_html":"<del><span class=\"woocommerce-Price-amount amount\"><span class=\"woocommerce-Price-currencySymbol\">&#36;</span>18.00</span></del> <ins><span class=\"woocommerce-Price-amount amount\"><span class=\"woocommerce-Price-currencySymbol\">&#36;</span>16.00</span></ins>","display":true},"option_pass":true,"note":"","parent_id":0,"seller_id":0,"seller_name":"","item_type":"","has_custom_discount":false,"disable_qty_change":false,"promotion_added":0},{"id":1556507562152,"name":"Sunglasses","sub_name":"","dining":"","price":90,"price_incl_tax":94.5,"product_id":16,"final_price":90,"final_price_incl_tax":94.5,"options":[],"bundles":[],"variations":[],"discount_amount":0,"discount_type":"fixed","final_discount_amount":0,"qty":1,"refund_qty":0,"exchange_qty":0,"refund_total":0,"tax_amount":4.5,"total_tax":4.5,"total":90,"total_incl_tax":94.5,"product":{"name":"Sunglasses","id":16,"parent_id":16,"sku":"woo-sunglasses","qty":48,"manage_stock":true,"stock_status":"instock","barcode":"00000000016","image":"http://localhost.com/dev/openpos/wordpress/wp-content/uploads/2018/05/sunglasses-2.jpg","price":90,"final_price":90,"special_price":"","regular_price":"90","sale_from":null,"sale_to":null,"status":"publish","categories":["19","16"],"tax":[{"rate":0,"shipping":"no","compound":"no","rate_id":0,"label":"Tax on POS"}],"tax_amount":0,"price_included_tax":true,"group_items":[],"variations":[],"options":[],"bundles":[],"display_special_price":false,"price_display_html":"<span class=\"woocommerce-Price-amount amount\"><span class=\"woocommerce-Price-currencySymbol\">&#36;</span>90.00</span>","display":true},"option_pass":true,"note":"","parent_id":0,"seller_id":0,"seller_name":"","item_type":"","has_custom_discount":false,"disable_qty_change":false,"promotion_added":0},{"id":1556507563868,"name":"Beanie with Logo","sub_name":"","dining":"","price":18,"price_incl_tax":18.9,"product_id":30,"final_price":18,"final_price_incl_tax":18.9,"options":[],"bundles":[],"variations":[],"discount_amount":0,"discount_type":"fixed","final_discount_amount":0,"qty":1,"refund_qty":0,"exchange_qty":0,"refund_total":0,"tax_amount":0.9,"total_tax":0.9,"total":18,"total_incl_tax":18.9,"product":{"name":"Beanie with Logo","id":30,"parent_id":30,"sku":"Woo-beanie-logo","qty":0,"manage_stock":true,"stock_status":"outofstock","barcode":"360","image":"http://localhost.com/dev/openpos/wordpress/wp-content/uploads/2018/05/beanie-with-logo-1.jpg","price":18,"final_price":18,"special_price":"18","regular_price":"20","sale_from":null,"sale_to":{"date":"2030-12-30 17:00:00.000000","timezone_type":1,"timezone":"+00:00"},"status":"publish","categories":["19","16"],"tax":[{"rate":0,"shipping":"no","compound":"no","rate_id":0,"label":"Tax on POS"}],"tax_amount":0,"price_included_tax":true,"group_items":[],"variations":[],"options":[],"bundles":[],"display_special_price":false,"price_display_html":"<del><span class=\"woocommerce-Price-amount amount\"><span class=\"woocommerce-Price-currencySymbol\">&#36;</span>20.00</span></del> <ins><span class=\"woocommerce-Price-amount amount\"><span class=\"woocommerce-Price-currencySymbol\">&#36;</span>18.00</span></ins>","display":true},"option_pass":true,"note":"","parent_id":0,"seller_id":0,"seller_name":"","item_type":"","has_custom_discount":false,"disable_qty_change":false,"promotion_added":0}],"sub_total":144,"sub_total_incl_tax":151.2,"tax_amount":7.2,"customer":{"id":0,"group_id":0,"name":"","email":"","address":"","phone":"","point":0,"point_rate":0,"discount":0,"addition_data":{}},"discount_amount":5,"discount_type":"fixed","final_items_discount_amount":0,"final_discount_amount":5,"grand_total":146.2,"discount_code":"","discount_codes":[],"discount_code_amount":0,"payment_method":[{"name":"Cash","code":"cash","ref":"","description":"","paid":50,"return":0,"paid_point":0},{"name":"Chip & PIN","code":"chip_pin","ref":"","description":"","paid":96.2,"return":0,"paid_point":0}],"shipping_information":{"shipping_method":"","shipping_title":"","address_id":0,"name":"","email":"","address":"","phone":"","note":""},"shipping_cost":0,"sale_person":1,"sale_person_name":"admin","note":"","created_at":"4/29/2019, 10:13:10 AM","state":"new","online_payment":false,"print_invoice":true,"point_discount":[],"add_discount":true,"add_shipping":false,"add_tax":true,"custom_tax_rate":5,"custom_tax_rates":[],"tax_details":[],"source":{},"source_type":"","created_at_time":1556507590133,"order_id":"5480","sync_status":0,"refunds":[],"exchanges":[],"refund_total":0};
            <?php else: ?>
            var  order = <?php echo $data['order_json']; ?>;
            <?php endif; ?>
            var template = '<?php echo $data['html_body']; ?>';

            var html = ejs.render(template, order);

            $('body').html(html);
            window.print();
        });

    }(jQuery));

</script>
</body>
</html>