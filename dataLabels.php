<?php
namespace FreedomForged\XPlaneData;
	/**
	 *
	 * Return data with labels from x-plane
	 * @author Jason Gillman Jr. jason@rrfaae.com
	 *
	 */

	/**
	 * $sentenceLabels[$sentenceId] = $label
	 */
	$sentenceLabels[0] = 'Frame Rate';
	$sentenceLabels[1] = 'Times';
	$sentenceLabels[2] = 'Sim Stats';
	$sentenceLabels[3] = 'Speeds';
	$sentenceLabels[4] = 'Mach / VVI / G-Load';
	$sentenceLabels[5] = 'Atmosphere: Weather';
	$sentenceLabels[6] = 'Atmosphere: Aircraft';
	$sentenceLabels[7] = 'System Pressures';
	$sentenceLabels[8] = 'Joystick Ail / Elev / Rud';
	$sentenceLabels[9] = 'Other Flight Controls';
	$sentenceLabels[10] = 'Artificial Stab Ail / Elev / Rud';
	$sentenceLabels[11] = 'Flight Controls Ail / Elev/ Rud';
	$sentenceLabels[12] = 'Wing Sweep / Thrust Vector';
	$sentenceLabels[13] = 'Trim / Flaps / Slats / Speed Brakes';
	$sentenceLabels[14] = 'Speeds';

	/**
	 * $messageLabels[$sentenceId] = array('Label 1', 'Label 2' , 'Label 3', 'Label 4', 'Label 5', 'Label 6', 'Label 7', 'Label 8')
	 */
	$messageLabels[0] = array('Label 1', 'Label 2' , 'Label 3', 'Label 4', 'Label 5', 'Label 6', 'Label 7', 'Label 8');
	$messageLabels[1] = array('Label 1', 'Label 2' , 'Label 3', 'Label 4', 'Label 5', 'Label 6', 'Label 7', 'Label 8');
	$messageLabels[2] = array('Label 1', 'Label 2' , 'Label 3', 'Label 4', 'Label 5', 'Label 6', 'Label 7', 'Label 8');
	$messageLabels[3] = array('Vind KIAS', 'Vind KEAS', 'Vtrue KTAS', 'Vtrue KTGS', 'NOT USED', 'Vind MPH', 'Vtrue MPHAS', 'Vtrue MPHGS');

	/**
	 *
	 * @param array $rawData The raw data to label
	 * @return array The labeled data
	 *
	 * This function generates an array with data labels
	 */
	function convertToLabels($rawData)
	{
		$sentenceLabels = $GLOBALS['sentenceLabels'];
		$messageLabels = $GLOBALS['messageLabels'];
		$shiftCounter = 1;
		$labeledArray = array();

		while(!is_null($currentData = array_shift($rawData)))
		{
			if($shiftCounter == 1)
			{
				$sentenceId = $currentData;

				if(array_key_exists($currentData, $sentenceLabels)) // You can only assign a label if one exists..
				{
					$labeledArray[$sentenceId]['label'] = $sentenceLabels[$sentenceId];
				}
				else
				{
					$labeledArray[$sentenceId]['label'] = 'No Label Set';
				}
				$shiftCounter++;
			}
			elseif($shiftCounter < 9)
			{
				if(array_key_exists($sentenceId, $messageLabels)) // You can only assign a label if one exists..
				{
					$labeledArray[$sentenceId]['data'][$shiftCounter - 2]['label'] = $messageLabels[$sentenceId][$shiftCounter - 2];
					$labeledArray[$sentenceId]['data'][$shiftCounter - 2]['value'] = $currentData;
				}
				else
				{
					$labeledArray[$sentenceId]['data'][$shiftCounter - 2]['label'] = 'No Label Set';
					$labeledArray[$sentenceId]['data'][$shiftCounter - 2]['value'] = $currentData;
				}
				$shiftCounter++;
			}
			else // Last datapoint
			{
				if(array_key_exists($sentenceId, $messageLabels)) // You can only assign a label if one exists..
				{
					$labeledArray[$sentenceId]['data'][$shiftCounter - 2]['label'] = $messageLabels[$sentenceId][$shiftCounter - 2];
					$labeledArray[$sentenceId]['data'][$shiftCounter - 2]['value'] = $currentData;
				}
				else
				{
					$labeledArray[$sentenceId]['data'][$shiftCounter - 2]['label'] = 'No Label Set';
					$labeledArray[$sentenceId]['data'][$shiftCounter - 2]['value'] = $currentData;
				}
				$shiftCounter = 1; // Reset for the next sentence
			}
		}

		return $labeledArray;
	}
