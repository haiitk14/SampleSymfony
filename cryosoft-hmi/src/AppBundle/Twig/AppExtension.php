<?php
// src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

use AppBundle\Cryosoft\UnitsConverterService;
use AppBundle\Entity\Post;

class AppExtension extends \Twig_Extension
{

    public function __construct(UnitsConverterService $unitconv) {
        $this->unitconv = $unitconv;
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('duration', array($this, 'durationFilter')),
            new \Twig_SimpleFilter('tsformat', array($this, 'tsformatFilter')),
            new \Twig_SimpleFilter('unitconvtime', array($this, 'unitconvTime')),
            new \Twig_SimpleFilter('unitconvcontroltemperature', array($this, 'unitconvControlTemperature')),
            new \Twig_SimpleFilter('unitconvconvectionspeed', array($this, 'unitconvConvectionSpeed')),
            new \Twig_SimpleFilter('unitconvexhausttemperature', array($this, 'unitconvExhaustTemperature')),
        );
    }

    public function unitconvTime($value)
    {
        return $this->unitconv->unitConvert(Post::TYPE_UNIT_TIME, $value, 1);
    }

    public function unitconvControlTemperature($value)
    {
        return $this->unitconv->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $value, 0);
    }

    public function unitconvConvectionSpeed($value)
    {
        return $this->unitconv->unitConvert(Post::TYPE_UNIT_CONV_SPEED, $value, 1);
    }

    public function unitconvExhaustTemperature($value)
    {
        return $this->unitconv->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $value, 0);
    }

    public function tsformatFilter($ts)
    {
    	if (!empty($ts))
    		return date("Y-M-d H:i:s",$ts);
    	return "";
    }

    public function durationFilter($start, $end)
    {
    	$start = intval($start);
    	$end = intval($end);
    	$secs =  $end - $start ;
    	if ($secs < 0)
    		return "";
	    $hours   = floor($secs / 3600); 
	    $minutes = floor($secs / 60);
	    $secs = $secs - $hours*3600 - $minutes*60; 
	    $output = $hours . ":". $minutes .":". $secs;
        return $output;
    }


}
