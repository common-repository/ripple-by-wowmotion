<div class="info">
    <?php
    $shortcode_name = RippleSemanticRelatedContentWidget::get_shortcode_name();
    $defaultOptions = ripple_get_semantic_related_content_options();
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
                <td>max_related_post</td>
                <td><?php echo ucfirst(gettype($defaultOptions["max_related_post"])); ?></td>
                <td><?php echo $defaultOptions["max_related_post"]; ?></td>
                <td align="left"><?php _e("A positive integer", RIPPLE_TR_DOMAIN); ?> </td>
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
                <td align="left"><?php _e("A positive integer", RIPPLE_TR_DOMAIN); ?> </td>
            </tr>
            <tr>
                <td>column_number</td>
                <td><?php echo ucfirst(gettype($defaultOptions["column_number"])); ?></td>
                <td><?php echo $defaultOptions["column_number"]; ?></td>
                <td align="left"><?php _e("A positive integer", RIPPLE_TR_DOMAIN); ?></td>
            </tr>
            <tr>
                <td>search_base</td>
                <td><?php echo "Mixed"; ?></td>
                <td><?php echo $defaultOptions["search_base"]; ?></td>
                <td align="left">
                    <?php _e("This option allow to defined the taxonomies used to search for related content.", RIPPLE_TR_DOMAIN); ?>
                    <ul>
                        <li><?php _e("(String) \"origin\" : related contents will be searched based on the taxonomies carried by the original content", RIPPLE_TR_DOMAIN); ?></li>
                        <li><?php _e("(int) \$post_id : related contents will be searched based on the taxonomies carried by the post describe by this ID ", RIPPLE_TR_DOMAIN); ?></li>
                        <li><?php _e("(String) \$query_string : a simple query string such as \"my_taxonomy=a_term\" ", RIPPLE_TR_DOMAIN); ?></li>
                        <li><?php _e("(String) \"none\" : no taxonomies will be used to request content.", RIPPLE_TR_DOMAIN); ?></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>post_type</td>
                <td><?php echo ucfirst(gettype($defaultOptions["post_type"])); ?></td>
                <td><?php echo $defaultOptions["post_type"]; ?></td>
                <td align="left">
                    <ul>
                        <li><?php _e("(String)\"self\" | undefined : related content will have the same post type as the original one", RIPPLE_TR_DOMAIN); ?></li>
                        <li><?php _e("(String) \"*\" : related content will be of any kind of registered post type (post type will not be considred as silo anymore)", RIPPLE_TR_DOMAIN); ?></li>
                        <li><?php _e("(String) \$post_type_list : A String of post type slug separated by coma (ex: \"post, page, project\"). Related contents post type will match with this list", RIPPLE_TR_DOMAIN); ?></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>tax_white_list</td>
                <td><?php echo "Mixed" ?></td>
                <td><?php echo $defaultOptions["tax_white_list"]; ?></td>
                <td align="left">
                    <ul>
                        <li><?php _e("undefined  : if the option is not defined, every registered taxonomies will be used to search for related content", RIPPLE_TR_DOMAIN); ?></li>
                        <li>
                            <?php _e("(String) \$tax_list - Taxonomies name separated by coma (ex: \"category,post_tag,my_custom_tax\").", RIPPLE_TR_DOMAIN); ?> <br />
                            <?php _e("The widget will search for related content based only on these taxonomies and will ignore others. ", RIPPLE_TR_DOMAIN); ?><br />
                        </li>
                    </ul>
                    <strong><?php _e("Important : ", RIPPLE_TR_DOMAIN); ?></strong><?php _e("if \"search_base\" option is specified this option will be ignored.", RIPPLE_TR_DOMAIN); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br /><br />
    <strong>Usage</strong>
    <div class="code-example">
        [<span class="red"><?php echo $shortcode_name; ?></span> <span class="blue">max_related_post=</span>"3" <span class="blue">widget_title=</span>"Awesome title" <span class="blue">...</span>]
    </div>
</div>
