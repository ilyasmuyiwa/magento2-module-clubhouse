<?php

namespace DGTERA\Clubhouse\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use DGTERA\Clubhouse\Model\Config\Source\ConfigSource;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Customer\Model\CustomerRegistry;
use \Magento\Customer\Model\Session as CustomerSession;

/**
 * Class Profile is the viewModel Class that
 * handles the view logic
 */
class Profile implements ArgumentInterface
{
    const FIELD_CONFIG_PATH = 'ClubhouseUrlConf/ClubhouseConfigGroup/urlConfig';

    const ATTRIBUTE_CODE = 'clubhouse_profile';

    /**
     * @var ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * @var CustomerRegistry
     */
    private $_registry;

    /**
     * @var CustomerSession
     */
    private $_customerSession;

    /**
     * Profile constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerRegistry $registry
     * @param CustomerSession $session
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CustomerRegistry $registry,
        CustomerSession $session
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_registry = $registry;
        $this->_customerSession = $session;
    }

    public function getFieldSetting()
    {

        $config =  $this->_scopeConfig->getValue(
            self::FIELD_CONFIG_PATH,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );

        return $config;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        $config = $this->getFieldSetting();
        return $config === ConfigSource::INVISIBLE;
    }

    /**
     * @return bool
     */
    public function isOptional()
    {
        $config = $this->getFieldSetting();
        return $config === ConfigSource::OPTIONAL;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        $config = $this->getFieldSetting();
        return $config === ConfigSource::REQUIRED;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getClubhouseUrl()
    {

        if ($this->_customerSession->isLoggedIn()) {
            $customerId = $this->_customerSession->getCustomerId();
            $customer = $this->_registry->retrieve($customerId);
            if ($customer) {
                return $customer->getClubhouseProfile();
            }
        }
        return '';
    }
}
