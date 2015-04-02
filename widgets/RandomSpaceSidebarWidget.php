<?php

/**
 * RandomSpaceSidebarWidget renders the RandomSpacePanel
 * to the Dashboard.
 *
 * @package humhub-modules-randomspace
 * @author Jordan Thompson
 */
class RandomSpaceSidebarWidget extends HWidget {
    
    /**
     * Render random space information
     *
     * @render random space widget
     */
    public function run() {
        $css = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../css', true, 0, defined('YII_DEBUG'));
        Yii::app()->clientScript->registerCssFile($css . '/randomspace.css');
        $spaceInfo = $this->getRandomSpace();
        if ( is_object($spaceInfo[0]) && is_array($spaceInfo[1]) ) {
            $this->render ( 'RandomSpacePanel', array (
                'css' => $css,
                'space' => $spaceInfo[0],
                'members' => $spaceInfo[1]
            ) );
        // Something went wrong... try again
        } else {
            $this->run();
        }

    }
    
    
    /**
     * Get a random space model from DB
     *
     * @return array of space and membership information from Space Model.
     */
    private function getRandomSpace() {
    
        $max = Space::model()->count();
        $randId = rand(0,$max);
        $space = Space::model()->find(array('offset'=>$randId));
		$spaceType = (Yii::app()->user->isGuest) ? 2 : 1;
            if ( is_object($space) && is_array($space->attributes) ) {
                if ($space->attributes['visibility'] == $spaceType) {
                $members = array();
                $membership = Yii::app()->db->createCommand()
                        ->select('user_id')
                    ->from('space_membership')
                    ->where('space_id=:id', array(':id'=>$space->id))
                    ->queryAll();
                foreach ( $membership as $member ) {
                    $members[] = User::model()->findByPk($member['user_id']);
                }
                return array($space, $members);
            // Something went wrong.. try again
            } else {
                $this->getRandomSpace();
            }
        } else {
            $this->getRandomSpace();
        }
    }
    
}
