<?php
declare(strict_types=1);

namespace DGTERA\Clubhouse\Model;

use Magento\Customer\Model\AttributeMetadataResolver as BaseAttributeMetadataResolver;
use Magento\Customer\Model\Config\Share as ShareConfig;
use Magento\Customer\Model\fileUploaderDataResolver;
use Magento\Customer\Model\ResourceModel\Address\Attribute\Source\CountryWithWebsites;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Eav\Model\Entity\Type;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\DataProvider\EavValidationRules;
use DGTERA\Clubhouse\ViewModel\Profile;

class AttributeMetadataResolver extends BaseAttributeMetadataResolver
{
    private $_profile;

    public function __construct(
        CountryWithWebsites $countryWithWebsiteSource,
        EavValidationRules $eavValidationRules,
        fileUploaderDataResolver $fileUploaderDataResolver,
        ContextInterface $context,
        ShareConfig $shareConfig,
        Profile $profile
    ) {
        $this->_profile = $profile;
        parent::__construct(
            $countryWithWebsiteSource,
            $eavValidationRules,
            $fileUploaderDataResolver,
            $context,
            $shareConfig
        );
    }

    /**
     * @param AbstractAttribute $attribute
     * @param Type $entityType
     * @param bool $allowToShowHiddenAttributes
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAttributesMeta(
        AbstractAttribute $attribute,
        Type $entityType,
        bool $allowToShowHiddenAttributes
    ) : array {
        $meta = parent::getAttributesMeta($attribute, $entityType, $allowToShowHiddenAttributes);

        if ($attribute->getAttributeCode() === Profile::ATTRIBUTE_CODE) {
            if (isset($meta['arguments']['data']['config']['formElement'])) {
                $meta['arguments']['data']['config']['visible'] = !$this->_profile->isHidden();
                $meta['arguments']['data']['config']['required'] = (int)$this->_profile->isRequired();

                if ($this->_profile->isOptional() || $this->_profile->isRequired()) {

                    $meta['arguments']['data']['config']['validation']['validate-length'] = true;
                    $meta['arguments']['data']['config']['validation']['maximum-length-16'] = true;
                    if ($this->_profile->isRequired()) {
                        $meta['arguments']['data']['config']['validation']['required-entry'] = true;
                    }
                }
            }
        }

        return $meta;
    }
}
