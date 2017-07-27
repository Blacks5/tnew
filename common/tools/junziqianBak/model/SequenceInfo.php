<?php
/**
 * 多合同顺序签约Info
 * User: yfx
 * Date: 2017-03-07 0007
 * Time: 16:41
 */
namespace common\tools\junziqian\model;

class SequenceInfo{
    /**客户方合同的唯一编号*/
    public $businessNo;
    /**签约顺序号*/
    public $sequenceOrder;
    /**总份数*/
    public $totalNum;

    function __construct($businessNo,$sequenceOrder,$totalNum) {
        $this->businessNo=$businessNo;
        $this->sequenceOrder=$sequenceOrder;
        $this->totalNum=$totalNum;
    }

    function validate(){
        if(is_null($this->businessNo)){
            throw new \Exception("businessNo is null");return false;
        }
        $this->businessNo=static::trim($this->businessNo);
        if((!is_numeric($this->totalNum))||$this->totalNum>15||$this->totalNum<1){
            throw new \Exception("totalNum,合同总数不能为空，且不能超过15份或小于1");return false;
        }else if((!is_numeric($this->sequenceOrder))||$this->sequenceOrder<1||$this->sequenceOrder>$this->totalNum){
            throw new \Exception("sequenceOrder,顺序号不能为空，且不能超过合同总数或小于1");return false;
        }
        return true;
    }

    /**处理无效params转数字null或0为'0'**/
    static function trim($str){
        if($str==null){
            if(is_numeric($str)){
                return '0';
            }
            return '';
        }else{
            if(is_numeric($str)){
                return $str;
            }else{
                return trim($str.'');
            }
        }
    }
}