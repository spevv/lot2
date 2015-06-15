<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('uploadURL', '/uploads'); //http://lot2.localhost/
Yii::setAlias('uploadDir', dirname(dirname(__DIR__)).'/www/uploads'); //E:\xampp\htdocs\lot2\www\uploads