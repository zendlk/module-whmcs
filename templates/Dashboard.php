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
    public function templates() {
        self::partials_tabs();

        echo "<table class='table table-bordered table-zend'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th width='15%' class='align-middle text-center'>Hook</th>";
                    echo "<th width='10%' class='align-middle text-center'>Type</th>";
                    echo "<th class='align-middle text-center'>Message</th>";
                    echo "<th width='10%' class='align-middle text-center'>Active</th>";
                    echo "<th width='10%' class='align-middle text-center'></th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                foreach ( Capsule::table('mod_zend_templates')->where("type", "client")->get() as $template ):
                echo "<tr>";
                    echo "<td class='text-center'>".$template->hook."</td>";
                    echo "<td class='text-center'>".ucfirst($template->type)."</td>";
                    echo "<td class='text-center'>";
                        echo "<textarea id='message-".$template->id."' class='form-control' rows='3'>".$template->message."</textarea>";
                        echo "<div class='text-left' style='margin-top: 4px;'>";
                        foreach ( explode(",", $template->parameters) as $parameter ):
                            echo "<div class='badge' style='background-color: #337ab7; padding: 4px 8px;'>".$parameter."</div>&nbsp;&nbsp;";
                        endforeach;
                        echo "</div>";
                    echo "</td>";
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

    public function ajax_template_edit() {
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
     * This method responsible for rendering administrator
     * information tab in the module configuration view
     */
    public function administrators() {
        self::partials_tabs();

        echo "<table class='table table-bordered table-zend'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th width='15%' class='align-middle text-center' width='30%'>Name</th>";
                    echo "<th width='10%' class='align-middle text-center' width='30%'>Email</th>";
                    echo "<th class='align-middle text-center' width='20%'>Mobile</th>";
                    echo "<th class='align-middle text-center' width='10%'>Status</th>";
                    echo "<th width='10%' class='align-middle text-center'></th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                foreach ( Capsule::table('tbladmins')->get() as $administrator ):
                $info = Capsule::table('mod_zend_admininfo')->where("admin_id", $administrator->id)->first();
                echo "<tr>";
                    echo "<td class='text-center'>".$administrator->firstname." ".$administrator->lastname."</td>";
                    echo "<td class='text-center'>".$administrator->email."</td>";
                    echo "<td class='text-center'>";
                    echo "<input id='phone-".$administrator->id."' class='form-control' value='".$info->mobile."' />";
                    echo "</td>";
                    echo "<td class='text-center'>";
                        if ( $info->is_active ):
                        echo "<input id='is-active-".$administrator->id."' type='checkbox' class='form-check-input' checked />";
                        else:
                        echo "<input id='is-active-".$administrator->id."' type='checkbox' class='form-check-input' />";
                        endif;
                    echo "</td>";
                    echo "<td class='text-center'><button class='btn btn-success btn-sm' onclick='administrator.update(".$administrator->id.")'>Save</button></td>";
                echo "</tr>";
                endforeach;
            echo "</tbody>";
        echo "</table>";
    }

    public function ajax_administrator_update() {
        header('Content-Type: application/json');
        if ( Capsule::table('mod_zend_admininfo')->where("admin_id", $_POST["id"])->first() ):
            try {
                Capsule::table('mod_zend_admininfo')->where("admin_id", $_POST["id"])->update([
                    "mobile"    => $_POST['phone'],
                    "is_active" => ($_POST['is_active'] == 'true') ? true : false
                ]);
                echo json_encode(["status" => "success"]);
            } catch (\Exception $e) {
                echo json_encode(["status" => "failed"]);
            }
        else:
            try {
                Capsule::table('mod_zend_admininfo')->insert([
                    "admin_id"  => $_POST['id'],
                    "mobile"    => $_POST['phone'],
                    "is_active" => ($_POST['is_active'] == 'true') ? true : false
                ]);
                echo json_encode(["status" => "success"]);
            } catch (\Exception $e) {
                echo json_encode(["status" => "failed"]);
            }
        endif;
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
            echo "<li>";
                echo "<a href='addonmodules.php?module=zend&tab=administrators'><button class='btn btn-primary'>Administrators</button></a>";
            echo "</li>";
        echo "</ul>";
    }
}

?>