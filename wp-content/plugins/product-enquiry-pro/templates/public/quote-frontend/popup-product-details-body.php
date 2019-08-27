<tbody>
    <?php
    use \Includes\Frontend\QuoteUpAddPopupModal;

    $popup = new QuoteUpAddPopupModal();
    $enquiryid = $args['enquiryid'];
    // $result = \Includes\QuoteupManageHistory::getQuoteHistoryData($enquiryid, true);
    $grandtotal = 0;
    $quotetotal = 0;
                      
    $enquiryProducts = getQuoteProducts($enquiryid);
    
    foreach ($enquiryProducts as $variations) {
        $varation_pro = printVariations($variations);
        $quotetitle=$variations['product_title'].$varation_pro;
        $quoteprice=wc_price($variations['oldprice']);
        $quotenewprice=wc_price($variations['newprice']);
        $quotequantity=$variations['quantity'];
        $quotetotal=$variations['newprice']*$quotequantity;
        $grandtotal=$grandtotal+$quotetotal;

        ?>
        <tr class="woocommerce-orders-table__row">
                                                                   
            <td data-title="Product" data-colname="Product" class="prodtitle woocommerce-orders-table__cell woocommerce-orders-table__cell-product proddata">
            <label id="qproduct"><?php echo $quotetitle ?></label>
            </td>
                <?php
                // to check if show price option is enabled at backend.
                $flag=$popup->shouldShowOldPriceForEnquiry($enquiryid);
                if ($flag == true) {
                    ?>
                    <td class="price woocommerce-orders-table__cell woocommerce-orders-table__cell-oldprice" data-title="Old Price" data-colname="Old Price">
                        <label id="oprice"><?php echo $quoteprice ?></label>
                    </td>
                    <?php
                }
                ?>
                <td class="proddata price woocommerce-orders-table__cell woocommerce-orders-table__cell-newprice" data-title="New Price" data-colname="New Price"><label id="nprice"><?php echo $quotenewprice ?></label></td>
                <td class="proddata price woocommerce-orders-table__cell woocommerce-orders-table__cell-quantity" data-title="Quantity" data-colname="Quantity"><label id="qty"><?php echo $quotequantity ?></label></td>
                <td class="proddata price woocommerce-orders-table__cell woocommerce-orders-table__cell-amount" data-title="Amount" data-colname="Amount"><label id="total"><?php echo wc_price($quotetotal) ?></label></td>
            </tr>
            <?php
    }
            ?>
            <tr>
                <td class="footer-row" id='amt' colspan="<?php echo ($flag==true)?4:3 ?>">
                    <label class="qdetails"><?php _e('Total', QUOTEUP_TEXT_DOMAIN); ?></label>
                </td>
                <td class="price footer-row"><label id="totalamt"><?php echo wc_price($grandtotal) ?></label></td>
            </tr>
        
    </tbody>