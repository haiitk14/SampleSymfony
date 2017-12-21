<?php
/**
 * Created by PhpStorm.
 * User: haidt
 * Date: 10/20/17
 * Time: 3:07 PM
 */

namespace AppBundle\Entity;


class Post
{
    const OBSOLETE = 9;
    const DISABLED = 5;
	// production
    const LIMIT_ITEM_PRODUCTION_DURATION = 1001;//Production duration
    const LIMIT_ITEM_WEEKLY_PRODUCTION = 1000;//Weekly production
    const LIMIT_ITEM_ANNUAL_PRODUCTION = 1004;//Number of production weeks per year
    const LIMIT_ITEM_DAILY_STARTUP = 1002;//Daily startup
    const LIMIT_ITEM_AMBIENT_TEMPERATURE = 1005;//Ambient temperature
    const LIMIT_ITEM_AMBIENT_HUMIDITY = 1006;//Ambient humidity
    const LIMIT_ITEM_FLOW_RATE = 1003;//Flow rate
    const LIMMTI_ITEM_AVG_TEMPERATURE_DES = 1007;
    
    // product character
    const TRANSTYPE_FAMILY = 14;
    const TRANSTYPE_SUBFAMILY = 16;
    const TRANSTYPE_COMPONENT = 1;
    const TRANSTYPE_SHAPE = 4;

    // MinMax
    const LIMIT_ITEM_DIM1 = 1102;
    const LIMIT_ITEM_DIM2 = 1103;
    const LIMIT_ITEM_DIM3 = 1104;
    const LIMIT_ITEM_PROD_WEIGHT = 1100;

    // Equipment
    const TRANSTYPE_COOLING_FAMILY = 2;
    const TRANSTYPE_EQUIPMENT_FAMILY = 5;
    const TRANSTYPE_EQUIPMENT_ORIGIN =17;
    const TRANSTYPE_PROCESS_TYPE =13;
    const TRANSTYPE_MODEL = 7;
    const ID_TRANSLATION_LN = 2;
    const ID_TRANSLATION_CO2 = 3;
    const LIMIT_ITEM_VOLUME = 1129;
    const LIMIT_ITEM_TRRATIO =1095;
    const LIMIT_ITEM_LOADING_RATE =704;
    const MIN_MAX_ENERGY_PRICE = 1105;
    const MIN_MAX_L = 1034;
    const MIN_MAX_W = 1033;
    const TRANSTYPE_SUB_NAME_EQUIPMENT=100;
    const LIMIT_ITEM_VC = 1037;
    const LIMIT_ITEM_ALPHA = 1018;
    const LIMIT_ITEM_EQUIP_SIZE_LENGTH = 1076;
    const LIMIT_ITEM_EQUIP_SIZE_WIDTH = 1077;
    const LIMIT_ITEM_LENGTH_SHELVES = 1096;
    const LIMIT_ITEM_WIDTH_SHELVES = 1097;
    const LIMIT_ITEM_NB_SHELVES = 1090;

    // get Component
    const TRANS_TYPE_STATUS_COMP = 100;
    const TRANS_TYPE_COMP_LIST = 1;
    
    // CalculParamDefault
    const LIMIT_ITEM_MimmaxMaxItNbDef = 1010;
    const LIMIT_ITEM_MimmaxTimeStepNbDef = 1011;
    const LIMIT_ITEM_MimmaxCoeffDef = 1012;
    const LIMIT_ITEM_MimmaxRequestDef = 1019;
    const LIMIT_ITEM_MimmaxStopTopSurfDef = 1014;
    const LIMIT_ITEM_MimmaxStopIntDef = 1015;
    const LIMIT_ITEM_MimmaxStopBottomSurfDef = 1016;
    const LIMIT_ITEM_MimmaxStopAvgDef = 1017;
    const LIMIT_ITEM_MimmaxTimeStep = 1013;
    const LIMIT_ITEM_MimmaxStorageStep = 1106;
    const LIMIT_ITEM_MimmaxPrecLogStep = 1107;


    // MeshParamDefault
    const LIMIT_ITEM_MimmaxMesh1Size = 1;
    const LIMIT_ITEM_MimmaxMesh2Size = 2;
    const LIMIT_ITEM_MimmaxMesh3Size = 3;
    const LIMIT_ITEM_MimmaxMeshRatio = 1064;

    // config_unit
    const TYPE_UNIT_FLUID_FLOW = 1;
    const TYPE_UNIT_PRODUCT_FLOW = 2;
    const TYPE_UNIT_LENGTH = 3;
    const TYPE_UNIT_MASS = 4;
    const TYPE_UNIT_TIME = 5;
    const TYPE_UNIT_SPECIFIC_HEAT = 6;
    const TYPE_UNITDENSITY = 7;
    const TYPE_UNIT_TEMPERATURE = 8;
    const TYPE_UNIT_ENTHALPY = 9;
    const TYPE_UNIT_CONDUCTIVITY = 10;
    const TYPE_UNIT_LOSSES1 = 11;
    const TYPE_UNIT_LOSSES2 = 12;
    const TYPE_UNIT_CONV_SPEED = 13;
    const TYPE_UNIT_CONV_COEFF = 14;
    const TYPE_UNIT_PRESSURE = 15;
    const TYPE_UNIT_THICKNESS_PACKING = 16;
    const TYPE_UNIT_LINE = 17;
    const TYPE_UNIT_RESERVOIR_CAPACITY_LN2 = 18;
    const TYPE_UNIT_PROD_DIMENSION = 19;
    const TYPE_UNIT_MESH_CUT = 20;
    const TYPE_UNIT_EQUIP_DIMENSION = 21;
    const TYPE_UNIT_W_CARPET_SHELVES = 22;
    const TYPE_UNIT_SLOPES_POSITION = 23;
    const TYPE_UNIT_MATERIAL_RISE = 24;
    const TYPE_UNIT_RESERVOIR_CAPACITY_CO2 = 25;
    const TYPE_UNIT_EVAPORATION = 26;
    const TYPE_UNIT_CONSUMPTION_UNIT = 28;
    const TYPE_UNIT_CONSUMPTION_UNIT_LN2 = 29;
    const TYPE_UNIT_CONSUMPTION_UNIT_CO2 = 30;
    const TYPE_UNIT_CONSUM_MAINTIEN = 31;
    const TYPE_UNIT_CONSUM_MAINTIEN_LN2 = 32;
    const TYPE_UNIT_CONSUM_MAINTIEN_CO2 = 33;
    const TYPE_UNIT_CONSUM_MEF = 34;
    const TYPE_UNIT_CONSUM_MEF_LN2 = 35;
    const TYPE_UNIT_CONSUM_MEF_CO2 = 36;
    const TYPE_UNIT_MASS_PER_UNIT = 37;
    const TYPE_UNIT_PRODCHART_DIMENSION = 38;
    const TYPE_UNIT_CONV_SPEED_UNIT = 3;

    //packing
    const ID_PACKING_THICKNESS = 14;

    //brand mode
    const BRAIN_MODE_ESTIMATION = 1;
    const BRAIN_MODE_ESTIMATION_OPTIM = 2;
    const BRAIN_MODE_OPTIMUM_CALCULATE = 10;
    const BRAIN_MODE_OPTIMUM_REFINE = 11;
    const BRAIN_MODE_OPTIMUM_FULL = 12;
    const BRAIN_MODE_OPTIMUM_DHPMAX = 13;
    const BRAIN_MODE_SELECTED_CALCULATE = 14;
    const BRAIN_MODE_SELECTED_REFINE = 15;
    const BRAIN_MODE_SELECTED_FULL = 16;
    const BRAIN_MODE_SELECTED_DHPMAX = 17;

    //Value list
    const PROFIL_EXPERT = 2;
    const VALUE_N_A = "N.A.";
    const EQUIP_NOT_STANDARD = 0;
    const PROFIL_GUEST = 3;
    const STUDY_ECO_MODE = 1;
    const MIN_MAX_EQUIPMENT_WIDTH = 1077;
    const MIN_MAX_EQUIPMENT_LENGTH = 1076;
    const STUDY_OPTIMUM_MODE = 3;
    const STUDY_SELECTED_MODE = 2;
    const SLAB = 1;
    const NO_SPECIFIC_SIZE = -1.0;

    //Min max prodTempProdIso
    const LIMMIT_ITEM_INITIAL_TEMP = 1009;

    //line
    const MIN_MAX_STUDY_LINE_INSULATEDLINE_LENGHT = 1020;
    const MIN_MAX_STUDY_LINE_NON_INSULATEDLINE_LENGHT = 1021;
    const MIN_MAX_STUDY_LINE_ELBOWS_NUMBER = 1022;
    const MIN_MAX_STUDY_LINE_TEES_NUMBER = 1023;
    const MIN_MAX_STUDY_LINE_INSULATEDVALVE_NUMBER = 1024;
    const MIN_MAX_STUDY_LINE_NON_INSULATEDVALVE_NUMBER = 1025;
    const MIN_MAX_STUDY_LINE_HEIGHT = 1026;
    const MIN_MAX_STUDY_LINE_PRESSURE = 1027;
    const TRANSTYPE_COMMENT_LINE_ELMT = 27;
    const TRANSTYPE_ACTIVE = 100;

    // PreCalC_LDG_Rate_PRM
    const LIMMIT_ITEM_LINTERVAL = 1033; 
    const LIMMIT_ITEM_WINTERVAL = 1034;

}
