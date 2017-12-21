<?php
/****************************************************************************
**
** Copyright (C) 2017 Oriental Tran.
** Contact: dongtp@dfm-engineering.com
** Company: DFM-Engineering Vietnam
**
** This file is part of the cryosoft project.
**
**All rights reserved.
****************************************************************************/
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ValidateController extends Controller 
{
	/**
     * @Route("/validate/mesh", name="validateMesh")
     */
	public function meshAction() 
	{
		$emptyValue = "Enter a value in Dimension 1 !";
		$rangeinput = "Value out of range in Dimension 1 (0 : 1200) !";
		$notNumber = "Not a valid number in Dimension 1 !";
		$successChange = "Update mesh success";

		if (!$this->checkNull($_POST['dimension1'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['dimension1'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['dimension1'], 0, 1200)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Dimension 2 !";
		$rangeinput = "Value out of range in Dimension 2 (0 : 1200) !";
		$notNumber = "Not a valid number in Dimension 2 !";

		if (!$this->checkNull($_POST['dimension2'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['dimension2'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['dimension2'], 0, 1200)) {
			$this->alertResult($rangeinput);
		}


		$emptyValue = "Enter a value in Dimension 3 !";
		$rangeinput = "Value out of range in Dimension 3 (0 : 1200) !";
		$notNumber = "Not a valid number in Dimension 3 !";

		if (!$this->checkNull($_POST['dimension3'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['dimension3'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['dimension3'], 0, 1200)) {
			$this->alertResult($rangeinput);
		}

		$this->alertResult($successChange, true);

		return $this->render('validates/mesh.html.twig');
	}

	/**
     * @Route("/validate/calculation", name="validateCalculation")
     */
	public function calculationAction()
	{	
		$emptyvalue = "Enter a value in Max of iterations !";
		$stringValue = "Not a valid number in Max of iterations !";
		$rangeValue = "Value out of range in Max of iterations (1 : 100) !";
		$successChange = "Update calculation success";

		if (!$this->checkNull($_POST['maxIter'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['maxIter'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['maxIter'], 1, 100)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Coef. of relaxation !";
		$stringValue = "Not a valid number in Coef. of relaxation !";
		$rangeValue = "Value out of range in Coef. of relaxation (0 : 2) !";

		if (!$this->checkNull($_POST['relaxCoef'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['relaxCoef'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['relaxCoef'], 0, 2)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Precision of numerical modelling !";
		$stringValue = "Not a valid number in Precision of numerical modelling !";
		$rangeValue = "Value out of range in Precision of numerical modelling (0 : 100) !";

		if (!$this->checkNull($_POST['precision'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['precision'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['precision'], 0, 2)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Surface !";
		$stringValue = "Not a valid number in Surface !";
		$rangeValue = "Value out of range in Surface (-273 : 500) !";

		if (!$this->checkNull($_POST['r2Suface'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['r2Suface'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['r2Suface'], -273, 500)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Internal !";
		$stringValue = "Not a valid number in Internal !";
		$rangeValue = "Value out of range in Internal (-273 : 500) !";

		if (!$this->checkNull($_POST['r2Internal'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['r2Internal'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['r2Internal'], -273, 500)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Bottom !";
		$stringValue = "Not a valid number in Bottom !";
		$rangeValue = "Value out of range in Bottom (-273 : 500) !";

		if (!$this->checkNull($_POST['r2Bottom'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['r2Bottom'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['r2Bottom'], -273, 500)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Average !";
		$stringValue = "Not a valid number in Average !";
		$rangeValue = "Value out of range in Average (-273 : 500) !";

		if (!$this->checkNull($_POST['r2Average'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['r2Average'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['r2Average'], -273, 500)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Alpha top !";
		$stringValue = "Not a valid number in Alpha top !";
		$rangeValue = "Value out of range in Alpha top (0 : 2) !";

		if (!$this->checkNull($_POST['alphavalue0'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['alphavalue0'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['alphavalue0'], 0, 2)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Alpha bottom !";
		$stringValue = "Not a valid number in Alpha bottom !";
		$rangeValue = "Value out of range in Alpha bottom (0 : 2) !";

		if (!$this->checkNull($_POST['alphavalue1'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['alphavalue1'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['alphavalue1'], 0, 2)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Alpha left !";
		$stringValue = "Not a valid number in Alpha left !";
		$rangeValue = "Value out of range in Alpha left (0 : 2) !";

		if (!$this->checkNull($_POST['alphavalue2'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['alphavalue2'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['alphavalue2'], 0, 2)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Alpha right !";
		$stringValue = "Not a valid number in Alpha right !";
		$rangeValue = "Value out of range in Alpha right (0 : 2) !";

		if (!$this->checkNull($_POST['alphavalue3'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['alphavalue3'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['alphavalue3'], 0, 2)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Alpha front !";
		$stringValue = "Not a valid number in Alpha front !";
		$rangeValue = "Value out of range in Alpha front (0 : 2) !";

		if (!$this->checkNull($_POST['alphavalue4'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['alphavalue4'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['alphavalue4'], 0, 2)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Alpha rear !";
		$stringValue = "Not a valid number in Alpha rear !";
		$rangeValue = "Value out of range in Alpha rear (0 : 2) !";

		if (!$this->checkNull($_POST['alphavalue5'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['alphavalue5'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['alphavalue5'], 0, 2)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Storage step !";
		$stringValue = "Not a valid number in Storage step !";
		$rangeValue = "Value out of range in Storage step (1 : 100000000) !";

		if (!$this->checkNull($_POST['storageStep'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['storageStep'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['storageStep'], 1, 100000000)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Precision log step !";
		$stringValue = "Not a valid number in Precision log step !";
		$rangeValue = "Value out of range in Precision log step (1 : 100000000) !";

		if (!$this->checkNull($_POST['precisionStep'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['precisionStep'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['precisionStep'], 1, 100000000)) {
			$this->alertResult($rangeValue);
		}

		$emptyvalue = "Enter a value in Time Step !";
		$stringValue = "Not a valid number in Time Step !";
		$rangeValue = "Value out of range in Time Step (0.001 : 90000) !";

		if (!$this->checkNull($_POST['timeStep'])) {
			$this->alertResult($emptyvalue);
		}

		if (!$this->checkNumber($_POST['timeStep'])) {
			$this->alertResult($stringValue);
		}

		if (!$this->checkRange($_POST['timeStep'], 0.001, 90000)) {
			$this->alertResult($rangeValue);
		}

		$this->alertResult($successChange, true);

		return $this->render('validates/calculation.html.twig');
	}

	/**
     * @Route("/validate/result", name="validateResult")
     */
	public function resultAction()
	{
		$emptyValue = "Enter a value in Top- X !";
		$notNumber = "Not a valid number in Top- X !";
		$rangeinput = "Value out of range in Top- X (0 : 100) !";
		$successChange = "Update result success";

		if (!$this->checkNull($_POST['axis1Top'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis1Top'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis1Top'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Top- Y !";
		$notNumber = "Not a valid number in Top- Y !";
		$rangeinput = "Value out of range in Top- Y (0 : 100) !";

		if (!$this->checkNull($_POST['axis2Top'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis2Top'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis2Top'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Top- Z !";
		$notNumber = "Not a valid number in Top- Z !";
		$rangeinput = "Value out of range in Top- Z (0 : 100) !";

		if (!$this->checkNull($_POST['axis3Top'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis3Top'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis3Top'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Inside- X !";
		$notNumber = "Not a valid number in Inside- X !";
		$rangeinput = "Value out of range in Inside- X (0 : 100) !";

		if (!$this->checkNull($_POST['axis1Int'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis1Int'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis1Int'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Inside- Y !";
		$notNumber = "Not a valid number in Inside- Y !";
		$rangeinput = "Value out of range in Inside- Y (0 : 100) !";

		if (!$this->checkNull($_POST['axis2Int'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis2Int'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis2Int'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Inside- Z !";
		$notNumber = "Not a valid number in Inside- Z !";
		$rangeinput = "Value out of range in Inside- Z (0 : 100) !";

		if (!$this->checkNull($_POST['axis3Int'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis3Int'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis3Int'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Bottom- X !";
		$notNumber = "Not a valid number in Bottom- X !";
		$rangeinput = "Value out of range in Bottom- X (0 : 100) !";

		if (!$this->checkNull($_POST['axis1Bot'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis1Bot'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis1Bot'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Bottom- Y !";
		$notNumber = "Not a valid number in Bottom- Y !";
		$rangeinput = "Value out of range in Bottom- Y (0 : 100) !";

		if (!$this->checkNull($_POST['axis2Bot'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis2Bot'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis2Bot'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Bottom- Z !";
		$notNumber = "Not a valid number in Bottom- Z !";
		$rangeinput = "Value out of range in Bottom- Z (0 : 100) !";

		if (!$this->checkNull($_POST['axis3Bot'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis3Bot'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis3Bot'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Plan 12- Y !";
		$notNumber = "Not a valid number in Plan 12- Y !";
		$rangeinput = "Value out of range in Plan 12- Y (0 : 100) !";

		if (!$this->checkNull($_POST['plan1Value'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['plan1Value'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['plan1Value'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Plan 13- Z !";
		$notNumber = "Not a valid number in Plan 13- Z !";
		$rangeinput = "Value out of range in Plan 13- Z (0 : 100) !";

		if (!$this->checkNull($_POST['plan2Value'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['plan2Value'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['plan2Value'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Plan 23- X !";
		$notNumber = "Not a valid number in Plan 23- X !";
		$rangeinput = "Value out of range in Plan 23- X (0 : 100) !";

		if (!$this->checkNull($_POST['plan3Value'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['plan3Value'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['plan3Value'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Axis 1- Y !";
		$notNumber = "Not a valid number in Axis 1- Y !";
		$rangeinput = "Value out of range in Axis 1- Y (0 : 100) !";

		if (!$this->checkNull($_POST['axis2Axe1'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis2Axe1'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis2Axe1'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Axis 1- Z !";
		$notNumber = "Not a valid number in Axis 1- Z !";
		$rangeinput = "Value out of range in Axis 1- Z (0 : 100) !";

		if (!$this->checkNull($_POST['axis3Axe1'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis3Axe1'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis3Axe1'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Axis 2- X !";
		$notNumber = "Not a valid number in Axis 2- X !";
		$rangeinput = "Value out of range in Axis 2- X (0 : 100) !";

		if (!$this->checkNull($_POST['axis1Axe2'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis1Axe2'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis1Axe2'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Axis  2- Z!";
		$notNumber = "Not a valid number in Axis  2- Z !";
		$rangeinput = "Value out of range in Axis  2- Z (0 : 100) !";

		if (!$this->checkNull($_POST['axis3Axe2'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis3Axe2'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis3Axe2'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Axis 3- X !";
		$notNumber = "Not a valid number in Axis  3- X !";
		$rangeinput = "Value out of range in Axis  3- X (0 : 100) !";

		if (!$this->checkNull($_POST['axis1Axe3'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis1Axe3'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis1Axe3'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$emptyValue = "Enter a value in Axis 3- Y !";
		$notNumber = "Not a valid number in Axis 3- Y !";
		$rangeinput = "Value out of range in Axis 3- Y (0 : 100) !";

		if (!$this->checkNull($_POST['axis2Axe3'])) {
			$this->alertResult($emptyValue);
		}

		if (!$this->checkNumber($_POST['axis2Axe3'])) {
			$this->alertResult($notNumber);
		}

		if (!$this->checkRange($_POST['axis2Axe3'], 0, 100)) {
			$this->alertResult($rangeinput);
		}

		$this->alertResult($successChange, true);

		return $this->render('validates/result.html.twig');
	}

	private function alertResult($message = null, $result = false)
	{
		$alert['status'] = $result;
		$alert['msg'] = $message;
		$alert['heading'] = "Error";
		if ($result) {
			$alert['heading'] = "Success";
		}
		die(json_encode($alert));
	}

	private function checkLength($string, $from, $to)
	{
		$lengthString = strlen($string);
		if ($lengthString >= $from && $lengthString <= $to) {
			return true;
		} else {
			return false;
		}
	}

	private function checkRange($string, $min, $max) 
	{
		 if (($min <= $string) && ($string <= $max)) {
		 	return true;
		 } else {
		 	return false;
		 }
	}

	private function checkNull($string)
	{
		if (strlen(trim($string)) == 0 || ctype_space($string)) {
			return false;
		} else {
			return true;
		}
	}

	private function checkCharacterSpecial($string)
	{
		if (preg_match("[<>:;.,\/\\|\"?@!#$%^&*(){}+=~`]", $string)) {
			return false;
		} else {
			return true;
		}
	}

	private function allowNumber($string)
	{
		if (preg_match("#^[0-9]*$#", $string)) {
			return true;
		} else {
			return false;
		}
	}

	private function checkNumber($string)
	{
		if (is_numeric($string)){
			return true;
		} else {
			return false;
		}
	}

	private function allowChar($string)
	{
		if (preg_match("#^[a-zA-Z]*$#", $string)) {
			return true;
		} else {
			return false;
		}
	}
}