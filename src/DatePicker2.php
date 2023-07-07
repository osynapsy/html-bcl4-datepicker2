<?php

/*
 * This file is part of the Osynapsy package.
 *
 * (c) Pietro Celeste <p.celeste@osynapsy.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osynapsy\Bcl4\DatePicker2;

use Osynapsy\Html\Component\AbstractComponent;
use Osynapsy\Bcl4\TextBox;

class DatePicker2 extends AbstractComponent
{
    const FORMAT_DATE_IT = 'DD/MM/YYYY';
    const FORMAT_DATETIME_IT = 'DD/MM/YYYY HH:mm';

    private $datePickerId;
    private $dateComponent;
    protected $defaultValue;
    protected $format = 'DD/MM/YYYY';

    public function __construct($id)
    {        
        parent::__construct('div', $id.'_datepicker');
        $this->datePickerId = $id;
        $this->requireCss('lib/tempusdominus-5.38.0/style.css');
        $this->requireJs('lib/momentjs-2.17.1/moment.js');
        $this->requireJs('lib/tempusdominus-5.38.0/script.js');
        $this->requireJs('bcl4/datepicker2/script.js');
        $this->attributes(['class' => 'input-group date date-picker2' , 'data-target-input'=> 'nearest']);
        $this->dateComponent = $this->add($this->fieldDateBoxFactory());
        $this->fieldInputGroupAppendFactory();
    }

    protected function fieldInputGroupAppendFactory()
    {
        $this->add('<div class="input-group-append" data-target="#'.$this->id.'" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div>');
    }

    protected function fieldDateBoxFactory()
    {
        $TextBox = new class ($this->datePickerId) extends TextBox
        {
            public function __construct($id)
            {
                $this->formatValueFunction = [$this, 'formatDateValue'];
                parent::__construct($id);
                $this->attributes([
                    'class' => 'form-control datetimepicker-input text-center',
                    'data-toggle' => 'datetimepicker',
                    'data-target' => sprintf('#%s',$id)
                ]);                
            }

            public function formatDateValue($value)
            {                
                if (empty($value)) {
                    return $value;
                }                
                try {
                    $format = '%d/%m/%Y %H:%i:%s';
                    return (new \DateTime($value))->format($format);
                } catch (\Exception $e) {
                    return '';
                }                
            }
        };
        return $TextBox;
    }

    public function preBuild()
    {
        $this->attribute('data-date-format', $this->format);
        if (!empty($this->defaultValue) && empty($this->getTextBox()->getValue())) {
            $this->getTextBox()->setValue($this->defaultValue);
        }
    }

    public function getTextBox()
    {
        return $this->dateComponent;
    }

    /**
     *
     * @param type $min accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     * @param type $max accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateLimit($min, $max)
    {
        $this->setDateMin($min);
        $this->setDateMax($max);
    }

    /**
     *
     * @param type $date accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateMax($date)
    {
        $this->dateComponent->attribute('data-max', $date);
    }
    /**
     *
     * @param type $date accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateMin($date)
    {
        $this->dateComponent->attribute('data-min', $date);
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function setDefaultDate($date = null)
    {
        $this->defaultValue = empty($date) ? date('d/m/Y') : $date;
    }

    public function onChange($code)
    {
        $this->attribute('onchange', $code);
    }

    public function setAction($action, $parameters = null, $confirmMessage = null, $class = 'change-execute')
    {
        parent::setAction($action, $parameters, $confirmMessage, $class);
    }

    public function setDisabled($condition)
    {
        $this->dateComponent->setDisabled($condition);
    }

    public function setReadOnly($condition)
    {
        $this->dateComponent->setReadOnly($condition);
    }   
}
