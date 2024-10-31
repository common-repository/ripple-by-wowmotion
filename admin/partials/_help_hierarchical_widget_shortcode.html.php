<div class="info">
    <?php
    $shortcode_name = RippleHierarchicalRelatedContentWidget::get_shortcode_name();
    $defaultOptions = ripple_get_hierarchical_related_content_options();
    ?>
    <br />
    <strong>Shortcode name : </strong> <?php echo $shortcode_name; ?><br />
    <br />

    <table>
        <thead>
            <tr>
                <th>Parameters</th>
                <th>Type</th>
                <th>Default value</th>
                <th align="left">Possible value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>widget_title</td>
                <td><?php echo ucfirst(gettype($defaultOptions["widget_title"])); ?></td>
                <td><?php echo $defaultOptions["widget_title"]; ?></td>
                <td align="left"><?php _e("String value", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
            <tr>
                <td>widget_title_tag</td>
                <td><?php echo ucfirst(gettype($defaultOptions["widget_title_tag"])); ?></td>
                <td><?php echo $defaultOptions["widget_title_tag"]; ?></td>
                <td align="left"><?php _e("String value corresponding to a valid HTML tag", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
            <tr>
                <td>clickable_item</td>
                <td><?php echo ucfirst(gettype($defaultOptions["clickable_item"])); ?></td>
                <td><?php echo (int)$defaultOptions["clickable_item"]; ?></td>
                <td align="left"><?php _e("0 or 1 : activate / deactivate the display of a clickable link for each related content item", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
            <tr>
                <td>rel</td>
                <td><?php echo ucfirst(gettype($defaultOptions["rel"])); ?></td>
                <td><?php echo $defaultOptions["rel"]; ?></td>
                <td align="left"><?php _e("String value that will be used to fill the \"rel\" attribute for each related content link displayed by the widget", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
            <tr>
                <td>item_title_tag</td>
                <td><?php echo ucfirst(gettype($defaultOptions["item_title_tag"])); ?></td>
                <td><?php echo $defaultOptions["item_title_tag"]; ?></td>
                <td align="left"><?php _e("String value corresponding to a valid HTML tag", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
            <tr>
                <td>display_thumbnail</td>
                <td><?php echo ucfirst(gettype($defaultOptions["display_thumbnail"])); ?></td>
                <td><?php echo (int)$defaultOptions["display_thumbnail"]; ?></td>
                <td align="left"><?php _e("0 or 1", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
            <tr>
                <td>display_excerpt</td>
                <td><?php echo ucfirst(gettype($defaultOptions["display_excerpt"])); ?></td>
                <td><?php echo (int)$defaultOptions["display_excerpt"]; ?></td>
                <td align="left"><?php _e("0 or 1", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
            <tr>
                <td>excerpt_length</td>
                <td><?php echo ucfirst(gettype($defaultOptions["excerpt_length"])); ?></td>
                <td><?php echo $defaultOptions["excerpt_length"]; ?></td>
                <td align="left"><?php _e("0 or 1", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
        </tbody>
    </table>
    <br /><br />
    <strong>Usage</strong>
    <div class="code-example">
        [<span class="red"><?php echo $shortcode_name; ?></span> <span class="blue">widget_title_tag=</span>"h3" <span class="blue">widget_title=</span>"Awesome title" <span class="blue">...</span>]
    </div>
</div>
