<div class="info">
    <?php
    $shortcode_name = RippleBreadcrumbWidget::get_shortcode_name();
    $defaultOptions = ripple_get_breadcrumbs_options();
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
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>separator</td>
                <td><?php echo ucfirst(gettype($defaultOptions["separator"])); ?></td>
                <td><?php echo $defaultOptions["separator"]; ?></td>
            </tr>
            <tr>
                <td>display_home_link</td>
                <td><?php echo ucfirst(gettype($defaultOptions["display_home_link"])); ?></td>
                <td><?php echo $defaultOptions["display_home_link"]; ?></td>
            </tr>
            <tr>
                <td>home_link_text</td>
                <td><?php echo ucfirst(gettype($defaultOptions["home_link_text"])); ?></td>
                <td><?php echo $defaultOptions["home_link_text"]; ?></td>
            </tr>
        </tbody>
    </table>
    <br /><br />
    <strong>Usage</strong>
    <div class="code-example">
        [<span class="red"><?php echo $shortcode_name; ?></span> <span class="blue">separator=</span>">>" <span class="blue">display_home_link=</span>"0" <span class="blue">...</span>]
    </div>
</div>
