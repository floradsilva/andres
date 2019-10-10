<?php
/**
 * Enquiry mail single product table row
 *
 * This template can be overridden by copying it to yourtheme/quoteup/public/enquiry-mail/single-product-enquiry/products-table-row.php.
 *
 * HOWEVER, on occasion QuoteUp will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author  WisdmLabs
 * @version 6.1.0
 */
?>
<tr>
    <td class='product-name'>
        <a href='<?php echo esc_url($prod_permalink); ?>'><?php echo $title; ?></a>
        <?php
        if (!empty($variationStringToPrint)) {
            ?>
            <div style='margin-left:10px'>
            <?php
            foreach ($variationStringToPrint as $value) {
                echo "&#8627".$value."<br>";
            }
            ?>
            </div>
            <?php
        }
        ?>
    </td>
    <td class='sku'>
        <?php echo $sku; ?>
    </td>
    <td class='qty'>
        <?php echo $productQuantity; ?>
    </td>
    <?php
    // Check if 'Price' column disabled.
    if (!quoteupIsPriceColumnDisabled($form_data)) :
        ?>
        <td class='price'>
        <?php
        if ($enable_price == 'yes'  || $source == 'admin') {
            echo $price;
        } else {
            echo '-';
        }
        ?>
        </td>
        <?php
    endif;
    ?>
</tr>
