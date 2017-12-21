<?php

$path = '/opt/Ice-3.7.0/php';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'Ice.php';
require_once 'lib/IBrainCalculator.php';
require_once 'lib/IComponentBuilder.php';
require_once 'lib/ICryoRunning.php';
require_once 'lib/IDimMatCalculator.php';
require_once 'lib/IEconomicCalculator.php';
require_once 'lib/IFreezeCalculator.php';
require_once 'lib/IKernelToolCalculator.php';
require_once 'lib/ILayoutCalculator.php';
require_once 'lib/IMeshBuilder.php';
require_once 'lib/IPhamCastCalculator.php';
require_once 'lib/IPipelineCalculator.php';
require_once 'lib/IProfitCalculator.php';
require_once 'lib/IStudyCleaner.php';
require_once 'lib/IWeightCalculator.php';
