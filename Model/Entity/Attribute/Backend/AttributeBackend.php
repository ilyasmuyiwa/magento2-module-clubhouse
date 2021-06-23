<?php
/**
 * Created by PhpStorm.
 * User: BOBLEE
 * Date: 26/11/2019
 * Time: 5:20 PM
 */

namespace DGTERA\Clubhouse\Model\Entity\Attribute\Backend;

use \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \DGTERA\Clubhouse\ViewModel\Profile;

/**
 * Class AttributeBackend is the backend attribute class for handling clubhouse_profile
 * attribute
 *
 */
class AttributeBackend extends AbstractBackend
{

    const URL_LENGTH_LIMIT = 16;

    const FIELD_CONFIG_PATH = 'ClubhouseUrlConf\ClubhouseConfigGroup\urlConfig';

    private $_isValidated;

    /**
     * @var ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * @var Profile
     */
    private $_profile;

    /**
     * AttributeBackend constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Profile $profile
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Profile $profile
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_profile = $profile;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validate($object)
    {

        $clubProfile = $object->getData('clubhouse_profile');
        $clubProfile = trim($clubProfile);

        if ($this->_profile->isRequired() ||
            ($this->_profile->isOptional() && strlen($clubProfile) > 0)) {

            if (strlen($clubProfile) > self::URL_LENGTH_LIMIT) {
                throw new LocalizedException(__('Clubhouse  Length can\'t be longer than 16 Characters'));
            } elseif (empty($clubProfile)) {
                throw new LocalizedException(__('Clubhouse field should not be left blank'));
            }

        }
        $this->_isValidated = true;
        return true;
    }


    public function beforeSave($object)
    {
        if (!$this->_isValidated) {
            $this->validate($object);
        }
        return parent::beforeSave($object);
    }
}
