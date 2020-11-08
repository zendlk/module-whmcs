<?php

use WHMCS\Database\Capsule;

class Dashboard {

    /**
     * This method is responsible for showing overview information
     * of the module to the administrator and reporting any anomolies
     * of the module to the relevent parties.
     */
    function overview() {
        self::partials_tabs();
        echo "Overview";
    }



    /**
     * This method is responsible for handling message template
     * configuration for the module.
     */
    function templates() {
        self::partials_tabs();

        echo "<table class='table table-bordered table-zend'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th width='15%' class='align-middle text-center'>Hook</th>";
                    echo "<th class='align-middle text-center'>Message</th>";
                    echo "<th width='10%' class='align-middle text-center'>Active</th>";
                    echo "<th width='10%' class='align-middle text-center'></th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                foreach ( Capsule::table('mod_zend_templates')->get() as $template ):
                echo "<tr>";
                    echo "<td class='text-center'>".$template->hook."</td>";
                    echo "<td class='text-center'><textarea id='message-".$template->id."' class='form-control' rows='3'>".$template->message."</textarea></td>";
                    echo "<td class='text-center'>";
                        if ( $template->is_active ):
                        echo "<input id='is-active-".$template->id."' type='checkbox' class='form-check-input' checked />";
                        else:
                        echo "<input id='is-active-".$template->id."' type='checkbox' class='form-check-input' />";
                        endif;
                    echo "</td>";
                    echo "<td class='text-center'><button class='btn btn-success btn-sm' onclick='template.edit(".$template->id.")'>Save</button></td>";
                echo "</tr>";
                endforeach;
            echo "</tbody>";
        echo "</table>";
    }


    function ajax_template_edit() {
        header('Content-Type: application/json');
        try {
            Capsule::table('mod_zend_templates')->where("id", $_POST['id'])->update([
                "message" => $_POST['message'],
                "is_active" => ($_POST['is_active'] == 'true') ? true : false
            ]);
            echo json_encode(["status" => "success"]);
        } catch (\Exception $e) {
            echo json_encode(["status" => "failed"]);
        }
        exit();
    }



    /**
     * This partials method is responsible for injecting relevent
     * CSS / JS code snippts to the final rendering DOM wth every
     * tab navigation.
     */
    function partials_assets() {
        echo "<link rel='stylesheet' href='../modules/addons/zend/templates/css/zend.css' />";
        echo "<script src='../modules/addons/zend/templates/js/zend.js'></script>";
    }


    /**
     * This is where we define partialls HTML parts to render
     * top tab like menu items that help administrator to
     * navigate amoung module sections.
     */
    function partials_tabs() {
        self::partials_assets();
        echo "<ul class='list-inline list-unstyled'>";
            echo "<li>";
                echo "<a href='addonmodules.php?module=zend&tab=overview'><button class='btn btn-primary'>Overview</button></a>";
            echo "</li>";
            echo "<li>";
                echo "<a href='addonmodules.php?module=zend&tab=templates'><button class='btn btn-primary'>Templates</button></a>";    
            echo "</li>";
        echo "</ul>";
    }
}

?>