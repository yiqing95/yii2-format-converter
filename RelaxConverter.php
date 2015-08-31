<?php
namespace mdm\converter;

use mdm\converter\BaseConverter;

/**
 * NOTE  the converting should be apply once ,you should not call the virtual attributes multi times ,which will cause the
 *       converting between logical and physical applied multi times !
 *
 * Class RelaxConverter
 *       you can specify any converters (logical or physical) as you wish
 *
 * usage:
 * ~~~
 * // attach as behavior
 * [
 *     'class' => 'mdm\converter\RelaxConverter',
 *    'logicalConverter' =>function ($val, $attr, $that) {
 *           $val2 = @json_decode($val,true);
 *           if(json_last_error() === JSON_ERROR_NONE){
 *               return $val2 ;
 *           }else{
 *                  return null ;
 *           }
 *       },
 *    'physicalConverter' => function ($val, $attr, $that) {
 *            return json_encode($val);
 *     },
 *     'attributes' => [
 *          'extraData' => 'extra_data', // extra_data is original attribute
 *       ]
 *]
 *
 * // then attribute directly
 * $model->extraData = [ 'k1'=>1 ,'k2'=>2 , ]; // equivalent with $model->extra_data = '{"k1":1,"k2":2}' ;
 * ~~~.
 * above code can be implemented using SerializeConverter too !
 *
 * @package  mdm\converter
 * @author yiqing <yiqing_95@qq.com>
 * @since 1.0
 */
class RelaxConverter extends BaseConverter
{
    /**
     * @var \Closure
     */
    public $logicalConverter;

    /**
     * @var \Closure
     */
    public $physicalConverter;

    /**
     * Convert value to physical format
     * @param mixed $value value to converted
     * @param string $attribute Logical attribute
     * @return mixed Converted value
     */
    protected function convertToPhysical($value, $attribute)
    {
        if (isset($this->physicalConverter)) {
            return call_user_func_array($this->physicalConverter, [$value, $attribute, $this]);
        } else {
            // return the original value
            return $value;
        }
    }

    /**
     * Convert value to logical format
     * @param mixed $value value to converted
     * @param string $attribute Logical attribute
     * @return mixed Converted value
     */
    protected function convertToLogical($value, $attribute)
    {
        if (isset($this->logicalConverter)) {
            return call_user_func_array($this->logicalConverter, [$value, $attribute, $this]);
        } else {
            // return the original value
            return $value;
        }

    }

}