<?php
/**
 * Class used to format datetimes
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class DateTimeFormat
{

    /**
     * @var string
     * @access private
     */
    private $value;

    /**
     * @var string
     * @access private
     */
    private $format;

    /**
     * @var boolean
     * @access private
     */
    private $supHtmlSuffix;

    /**
     * Constructor method
     *
     * @param string $value = ''
     * @param string $format = 'M jS, h:i a'
     * @access public
     * @return void
     */
    public function __construct( $value = '', $format = 'M jS, h:i a' )
    {
        if( !ctype_digit((string)$value) && !is_string($value) )
        {
            throw new InvalidArgumentException('$value must be datetime string or a timestamp');
        }

        $this->supHtmlSuffix = false;
        $this->setValue($value);
        $this->setFormat($format);
    }

    /**
     * Sets the datetime value
     *
     * @access public
     * @param string | int $value - (Datetime or timestamp)
     * @return void
     */
    public function setValue( $value )
    {
        $this->value = $value;
    }

    /**
     * Sets the format for the datetime
     *
     * @param string $format
     * @access public
     * @return void
     */
    public function setFormat( $format )
    {
        $this->format = $format;
    }

    /**
     * Sets whether to add a "sup" html tag for suffix
     *
     * @access public
     * @param boolean $supHtmlSuffix
     * @return void
     */
    public function setSupHtmlSuffix( $supHtmlSuffix )
    {
        $this->supHtmlSuffix = $supHtmlSuffix;
    }

    /**
     * Returns the formatted datetime
     *
     * @access public
     * @return string
     */
    public function format()
    {
        if( ctype_digit((string)$this->value) )
        {
            $dateTime = date($this->format, $this->value);
        }
        else
        {
            $dateTime = date($this->format, strtotime($this->value));
        }

        if( $this->supHtmlSuffix )
        {
            return preg_replace('/(st|nd|rd|th)/', '<sup>\\1</sup>', $dateTime);
        }

        return $dateTime;
    }

}
?>