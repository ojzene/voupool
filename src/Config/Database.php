<?php
use RedBeanPHP\R;

R::setup('mysql:host=localhost;dbname=voupooldb', 'root', '');
R::setAutoResolve( TRUE );