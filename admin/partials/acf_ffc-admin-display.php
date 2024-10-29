<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       mediavuk.com
 * @since      1.0.0
 *
 * @package    Acf_ffc
 * @subpackage Acf_ffc/admin/partials
 */

require_once 'functions.php';
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <!-- Left Section (Settings) -->
    <div class="acf_ffc_settings">

        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <div class="success creating"><?php echo esc_html('You have successfully created template!') ?></div>
        <div class="success deleting"><?php echo esc_html('You have successfully deleted template!') ?></div>
        <div class="error"><?php echo esc_html('Error, please try again!') ?></div>


        <!-- Tab Buttons -->
        <div class="tabs">

            <button class="js-create active" type="button"><?php echo esc_html('Create Flexy') ?></button>
            <button class="js-templates" type="button"><?php echo esc_html('Templates') ?></button>
            <button class="js-documentation" type="button"><?php echo esc_html('Documentation') ?></button>

        </div>
        <!-- Tab Buttons End -->

        <!-- Flexy Settings -->
        <form class="acf_form" action="" method="post">

            <?php
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
            ?>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Generate"></p>
            <p class="error emptyFelds"><?php echo esc_html('Check at least one field group to generate!') ?></p>

        </form>
        <!-- Flexy Settings end -->

        <!-- Templates -->
        <div class="acf-templates">

            <ul class="templatesList">

                <?php

                $templates_array = listTemplates();
                
                foreach ($templates_array as $file => $name):

                    ?>

                    <li><?php echo $name; ?>
                        <a class="js-delete" data-filename="<?php echo $file ?>">
                            <img src="<?php echo plugins_url('/acf-fast-flexy/assets/images/delete-icon.png'); ?>" alt="Delete Icon" title="Delete Icon">
                        </a>
                    </li>

                <?php endforeach; ?>


            </ul>

        </div>
        <!-- Templates End -->

        <!-- Documentation -->
        <div class="acf-documentation">

            <h1><?php echo esc_html('Documentation') ?></h1>

            <h2><?php echo esc_html('1. First Step - Create Template') ?></h2>
            <h3><?php echo esc_html('1.1 Select all desired fields') ?></h3>

            <p><?php echo esc_html('All selected fields will be part of your page template, so we highly recommend to select all offered fields.') ?></p>

            <h3><?php echo esc_html('1.2 Rename template') ?></h3>

            <p><?php echo esc_html('You can rename template to suite your page. Or, if you like to call it Flexy(default), just skip this step.') ?></p>
            <p><?php echo esc_html('Note: If you create template, and name it, for example, "Template", and then make another one with the same name ("Template"), the second one will be automatically renamed to Template 1.') ?></p>

            <h3><?php echo esc_html('1.3 Click Generate.') ?></h3>

            <p><?php echo esc_html('And, that\'s it. Now you can start customizing your pages.') ?></p>


            <h2><?php echo esc_html('2. Customize your pages') ?></h2>

            <h3><?php echo esc_html('2.1 Create new page, or edit existing one.') ?></h3>

            <p><?php echo esc_html('It is important to choose template which you have just created.') ?></p>

            <h3><?php echo esc_html('2.2 Add flexible content') ?></h3>

            <p><?php echo esc_html('Under the default content editor, you will find flexible content editor. Just add your content and you\'re good to go.') ?></p>

            <img src="<?php echo plugins_url('/acf-fast-flexy/assets/images/flexible-content.jpg'); ?>" alt="Explanation">

            <h2><?php echo esc_html('3. Additional Fields') ?></h2>

            <h3><?php echo esc_html('Synchronize template') ?></h3>

            <p><?php echo esc_html('Go to Custom Fields. There you will find available syncs. Choose the one you want to extend and sync it. Now your field group is available for extending.') ?></p>

            <h2><?php echo esc_html('4.Enjoy') ?></h2>
            <p><?php echo esc_html("(Note: If you are using child theme, in order to use benefits of Acf Fast Flexy plugin, you will have to go to your themes, activate main theme, then go to custom fields tab in main navigation, sync desired flexible content. After the syncing, activate your child theme again, and you're good to go!)") ?></p>

            <br>

            <h2><?php echo esc_html('Important note:') ?></h2>
            <p><?php echo esc_html('ACF Flexy is a helper plugin, and it will do nothing if you don\'t install') ?> <a href="https://www.advancedcustomfields.com/pro/" title="ACF homepage"><?php echo esc_html('Advanced Custom Field Pro') ?></a> <?php echo esc_html('plugin first!') ?>
            </p>

        </div>
        <!-- Documentation End -->


    </div>
    <!-- Left Section (Settings) End -->


    <!-- Aside -->
    <div class="aside">


        <div class="aside-inner">

            <div class="logo">

                <a href="https://mediavuk.com/" title="Visit Our Website" target="_blank">
                    <img src="<?php echo plugins_url('/acf-fast-flexy/assets/images/logo.png'); ?>" alt="Mediavuk Logo">
                </a>

            </div>

            <div class="about-us">

                <h2><?php echo esc_html('Mediavuk') ?></h2>
                <h3><?php echo esc_html(' - The Media Consultants - ') ?></h3>

                <p><?php echo esc_html('We’re a team of MEDIA CREATORS bent on creating the most amazing work for our clients. And by work we mean WEBSITES, PRINT DESIGN, LOGOS, and various adventures in DIGITAL APPLICATIONS.') ?></p>

            </div>

            <div class="social">

                <h3><?php echo esc_html('Follow us:') ?></h3>

                <ul>

                    <li><a class="facebook" href="https://www.facebook.com/mediavuk/" title="Go To Mediavuk Facebook Profile" target="_blank"><?php echo esc_html('Facebook') ?></a>
                    </li>
                    <li><a class="twitter" href="https://twitter.com/mediavuk" title="Go To Mediavuk Twitter Profile" target="_blank"><?php echo esc_html('Twitter') ?></a></li>
                    <li><a class="linkedin" href="https://www.linkedin.com/company/6378728" title="Go To Mediavuk Linkedin Profile"
                           target="_blank"><?php echo esc_html('Linkedin') ?></a></li>

                </ul>

            </div>

        </div>

    </div>
    <!-- Aside End -->

</div>



