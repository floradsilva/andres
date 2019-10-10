<?php
/**
 * Enquiry mail multi products table row
 *
 * This template can be overridden by copying it to yourtheme/quoteup/public/enquiry-mail/multi-product-enquiry/products-table-row.php.
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
        <a href='<?php echo $url; ?>'><?php echo $title; ?></a>
        <?php
        if (!empty($variationString)) {
        ?>
            <div style='margin-left:10px'>
                <?php echo $variationString; ?>
            </div>
        <?php
        }
        ?>
    </td>
    <td class='sku'>
        <?php echo $sku; ?>
    </td>
    <td class='qty'>
        <?php echo $element['quant']; ?>
    </td>
    <?php
    // Check if 'Price' column disabled.
    if (!quoteupIsPriceColumnDisabled($form_data)) :
        ?>
        <td class='price'>
        <?php
        if ($enable_price == 'yes'  || $source == 'admin') {
            echo wc_price($element['price']);
        } else {
            echo '-';
        }
        ?>
        </td>
    <?php
    endif;

    // Check if 'Remarks' column disabled.
    if (!quoteupIsRemarksColumnDisabled($form_data)) :
        ?>
        <td class='remarks'>
            <?php echo $element[ 'remark' ]; ?> 
        </td>
    <?php
    endif;
        ?>
</tr>
