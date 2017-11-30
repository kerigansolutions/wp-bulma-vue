<?php

namespace Includes\Modules\MLS;

use Includes\Modules\Agents\Agents;

class AdminSettings
{
    protected $status;

    public function __construct()
    {
        $this->save();
    }

    public function setupPage()
    {
        $this->createNavLabel();
    }

    protected function createNavLabel()
    {

        add_action('admin_menu', function () {
            add_menu_page('IDX Settings', 'IDX Settings', 'manage_options', 'idx-settings', function () {
                $this->createIdxSettings();
            }, 'dashicons-admin-generic', 999);
        });

    }

    protected function createIdxSettings(){
        ?>
        <div class="wrap">
        <h1 class="wp-heading-inline" style="margin-bottom: .5rem;">IDX Settings</h1>
            <form method="post">
                <input type="hidden" name="get_mothership" value="agents">
                <button class="button button-hero">Update all agent data</button>
            </form>
            <p><?php echo ($this->status != '' ? $this->status : ''); ?></p>
        </div>
        <?php
    }

    protected function save(){

        if(isset($_POST['get_mothership'])){
            $agents = new Agents();
            $agentArray = $agents->getTeam();
            foreach($agentArray as $agent){
                $agentData = $agents->assembleAgentData( $agent['mls_name'] );
                $agents->updateAgent($agentData);
            }
            $this->status = 'DONE!';
        }

    }

}