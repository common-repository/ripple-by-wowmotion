<tr>
    <th scope="row">
        <?php
        $checked   = checked(1, $this->option_manager->option_value("clickable_item"), false);
        $html_name = $this->option_manager->html_name("clickable_item");
        ?>
        <div class="pretty plain toggle">
            <input type="checkbox" value="1" id="clickable_item" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off" class="ripple-activate-widget" data-toggle="toggle_link_form"/>
            <label><i class="fa fa-toggle-off danger"></i></label>
            <label><i class="fa fa-toggle-on success"></i></label>
        </div>

    </th>
    <td>
        <label for="clickable_item"><?php _e("Make the related content item clickable", RIPPLE_TR_DOMAIN); ?></label> <br />
        <em><?php _e("When activated the related content items display a link redirecting to the full post", RIPPLE_TR_DOMAIN); ?></em>
    </td>
</tr>
<tr id="toggle_link_form">
    <th scope="row">

    </th>
    <td>
        <?php
        $html_name     = $this->option_manager->html_name("rel");
        $value         = $this->option_manager->option_value("rel");
        $available_tag = array("nofollow", "prev", "next", "alternate");
        ?>
        <label for="rel"><?php _e('Links "rel" attribute', RIPPLE_TR_DOMAIN); ?></label> :
        <select id="rel" name="<?php echo $html_name; ?>">
            <option value=""><?php _e("None", RIPPLE_TR_DOMAIN);?></option>
            <?php
            foreach($available_tag as $tag){
                $selected = selected( $value, $tag );
                echo "<option value='{$tag}' {$selected}>{$tag}</option>";
            }
            ?>
        </select> <br />

        <label for="rel"><?php _e('Choose the "rel" attribute value that will be carried by the links generated by the widget for each related content', RIPPLE_TR_DOMAIN); ?></label>
    </td>
</tr>
<tr>
    <th scope="row">
        <?php
        $checked   = checked(1, $this->option_manager->option_value("display_thumbnail"), false);
        $html_name = $this->option_manager->html_name("display_thumbnail");
        ?>
        <div class="pretty plain toggle">
            <input type="checkbox" value="1" id="display_thumbnail" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
            <label><i class="fa fa-toggle-off danger"></i></label>
            <label><i class="fa fa-toggle-on success"></i></label>
        </div>

    </th>
    <td>
        <label for="display_thumbnail"><?php _e("Display related posts thumbnails", RIPPLE_TR_DOMAIN); ?></label>
        <br />
        <em><?php _e("Ripple will search for the \"Featured image\" attached to your post. If none found, a default image will be displayed.", RIPPLE_TR_DOMAIN); ?></em>
    </td>
</tr>
<tr>
    <th scope="row">
        <?php
        $checked = checked(1, $this->option_manager->option_value("display_excerpt"), false);
        $html_name = $this->option_manager->html_name("display_excerpt");
        ?>
        <div class="pretty plain toggle">
            <input type="checkbox" id="display_excerpt" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off" class="ripple-activate-widget" data-toggle="toggle_excerpt_form"/>
            <label><i class="fa fa-toggle-off danger"></i></label>
            <label><i class="fa fa-toggle-on success"></i></label>
        </div>
    </th>
    <td>
        <label for="display_excerpt"><?php _e("Display related posts excerpts", RIPPLE_TR_DOMAIN); ?></label> <br/>
        <em><?php _e("When activated, Ripple will always try to display an excerpt for each related content. The excerpt generator methods are describe just below.", RIPPLE_TR_DOMAIN); ?></em>
    </td>
</tr>
<tr id="toggle_excerpt_form">
    <td colspan="2">
        <table>
            <tr>
                <th scope="row"></th>
                <td>
                    <?php _e("Ripple generates an excerpt based on the following methods, and in that order. You can activate / deactivate these methods : ", RIPPLE_TR_DOMAIN); ?>
                    <br/>

                    <?php
                    $help_link_more_tag = '<a target="_blank" href="https://codex.wordpress.org/Customizing_the_Read_More">' . __("Learn how to use the more tag", RIPPLE_TR_DOMAIN) . '</a>';
                    $help_link_excerpt = '<a target="_blank" href="https://www.google.com/search?q=wordpress+excerpt+field">' . __("Learn how to use the excerpt field", RIPPLE_TR_DOMAIN) . '</a>';

                    $excerpt_generators = [
                        "more_content" => [
                            "label" => __('An excerpt based on the <strong>more tag</strong> content.', RIPPLE_TR_DOMAIN) . ' ' . $help_link_more_tag
                        ],
                        "excerpt_field" => [
                            "label" => __('An excerpt based on the <strong>excerpt field</strong>.', RIPPLE_TR_DOMAIN) . ' ' . __('Notice that the excerpt field need to be activated to be used (this can be done from the Ripple Dashboard)', RIPPLE_TR_DOMAIN) . ' ' . $help_link_excerpt
                        ],
                        "ripple" => [
                            "label" => __('A random excerpt based on the first paragraph found inside your post content.', RIPPLE_TR_DOMAIN)
                        ],
                    ];
                    ?>

                    <ol>
                        <?php
                        foreach ($excerpt_generators as $key => $gen_opt) {
                            $html_name = $this->option_manager->html_name("excerpt_generators", $key);
                            $current_value = $this->option_manager->option_value('excerpt_generators', $key);
                            ?>
                            <li>
                                <input type="checkbox" id="<?php echo $html_name; ?>" name="<?php echo $html_name; ?>"
                                       value="1" <?php checked(1, $current_value); ?> autocomplete="off"/>
                                <label for="<?php echo $html_name; ?>"><?php echo $gen_opt["label"]; ?></label>&nbsp;&nbsp;&nbsp;
                            </li>
                            <?php
                        }
                        ?>
                    </ol>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="excerpt_length"><?php _e('Excerpt length', RIPPLE_TR_DOMAIN) ?></label></th>
                <td>
                    <?php
                    $html_name = $this->option_manager->html_name("excerpt_length");
                    $value = $this->option_manager->option_value("excerpt_length");
                    ?>
                    <input type="number" id="excerpt_length" name="<?php echo $html_name; ?>" value="<?php echo $value; ?>"
                           autocomplete="off" min="1"/>
                    <br/>
                    <label
                        for="excerpt_length"><?php _e("Define the maximum length of the displayed excerpt. (min. 1)", RIPPLE_TR_DOMAIN) ?></label><?php
                    ?>
                </td>
            </tr>
        </table>
    </td>
</tr>

