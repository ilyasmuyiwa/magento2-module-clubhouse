<?php


namespace DGTERA\Clubhouse\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ConfigSource supplies parameter for hiding, and showing of config field
 *
 */
class ConfigSource implements OptionSourceInterface
{

    const REQUIRED = 'required';
    const OPTIONAL = 'optional';
    const INVISIBLE = 'invisible';
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['label'=>__('field is Required'), 'value'=> self::REQUIRED],
            ['label'=>__('field is Optional'), 'value'=> self::OPTIONAL],
            ['label'=>__('Hide Field'), 'value'=> self::INVISIBLE]
        ];
    }
}
