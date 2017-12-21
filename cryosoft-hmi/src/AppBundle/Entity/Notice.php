<?php
/**
 * Created by PhpStorm.
 * User: haidt
 * Date: 10/20/17
 * Time: 3:07 PM
 */

namespace AppBundle\Entity;


class Notice
{
    // ::::::::::::::: ERROR ::::::::::::: 
	const ERROR_ENERGY_NOT_IDENTICAL = "The selected equipment does not have the correct energy type";
    const ERROR_ONE_EQUIPMENT_ONLY = "The study can only bare one equipment";
    const ERROR_PROD_VOLUME_FOR_EQUIP = "Caution, this equipment can be used with small products only";


    //  :::::::::::::: WARNING :::::::::::
    const WARNING_GENERATED_EQUIPMENT_TS = "This is a generated equipment. The generation setpoint is used for first calculations. You can adjust the setpoint by hand.";
    const WARNING_GENERATED_EQUIPMENT_TR = "This is a generated equipment. The setpoint is fixed to value used during generation.";
    const WARNING_NO_TR_EQUIPMENT = "This equipment does not allow to use the assistant of calculation of the couple 'dwelling time/temperature setpoint'. <br>Displayed values are default values of couple 'dwelling time/temperature setpoint'";
    const STUDYLOAD_INPROGRESS_MSG = "Cannot open this study : brain calculation in progress";
    const ERROR_READING_EQUIPMENT = "Error while reading equipment data";
}