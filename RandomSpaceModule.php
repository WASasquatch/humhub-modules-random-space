<?php
/**
 * RandomSpaceModule pre-compiles widget and functions
 *
 * @package humhub-modules-randomspace
 * @author Jordan Thompson
 */
class RandomSpaceModule extends HWebModule
{

    // http://www.yiiframework.com/wiki/148/understanding-assets/
    // getAssetsUrl()
    // return the URL for this module's assets, performing the publish operation
    // the first time, and caching the result for subsequent use.
    private $_assetsUrl;
    public function getAssetsUrl()     {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('randomspace.assets')
            );
        return $this->_assetsUrl;
    }

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
    }
    
    /**
     * Insert widget into Dashboard imitation.
     *
     * @param type $event
     */    
    public static function onSidebarInit($event) {

        if (Yii::app()->moduleManager->isEnabled('randomspace')) {
            $event->sender->addWidget('application.modules.randomspace.widgets.RandomSpaceSidebarWidget', array(), array('sortOrder' => 2)); // Ensure this widget is ontop unless otherwise edited
        }
        
    }
    
    /**
     * Enables this module
     */
    public function enable()
    {
        parent::enable();
    }
    
}
?>
