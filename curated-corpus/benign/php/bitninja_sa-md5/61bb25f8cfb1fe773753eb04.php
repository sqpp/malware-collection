<?php

namespace ADP\BaseVersion\Includes\Rule\Interfaces\CartAdjustments;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

interface FeeCartAdj {
	const FEE_VALUE_KEY = 'fee_value';
	const FEE_NAME_KEY = 'fee_name';
	const FEE_TAX_CLASS_KEY = 'fee_tax_class';

	/**
	 * @param float $fee_value
	 */
	public function setFeeValue( $fee_value );

	/**
	 * @param string $fee_name
	 */
	public function setFeeName( $fee_name );

	/**
	 * @param string $tax_class
	 */
	public function setFeeTaxClass( $tax_class );

	/**
	 * @return float
	 */
	public function getFeeValue();
	
	/**
	 * @return string
	 */
	public function getFeeName();

	/**
	 * @return string
	 */
	public function getFeeTaxClass();
}
