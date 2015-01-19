<?php

Yii::app()->moduleManager->register(array(
    'id' => 'randomspace',
    'class' => 'application.modules.randomspace.RandomSpaceModule',
    'import' => array(
        'application.modules.randomspace.*',
    ),
    // Events to Catch 
    'events' => array(
        array('class' => 'DashboardSidebarWidget', 'event' => 'onInit', 'callback' => array('RandomSpaceModule', 'onSidebarInit')),
    ),
));

?>
