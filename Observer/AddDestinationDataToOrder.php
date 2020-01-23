<?php

namespace Learn\NovaPoshta\Observer;

use Learn\NovaPoshta\Model\DestinationAddressRepository;
use Magento\Checkout\Helper\Data;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Learn\NovaPoshta\Model\Provider\CheckoutConfigProvider;
use Magento\Sales\Api\Data\OrderExtensionFactory;

/**
 * Class DonationPlugin
 * @package Learn\CustomCheckout\Plugin
 */
class AddDestinationDataToOrder implements ObserverInterface
{

    /**
     * @var Data
     */
    public $checkoutHelper;

    /**
     * @var ShippingInformationInterface
     */
    public $shippingInformation;

    /**
     * @var CheckoutConfigProvider
     */
    public $configProvider;

    /**
     * @var CheckoutSession
     */
    public $checkoutSession;

    /**
     * @var OrderExtensionFactory
     */
    public $extensionFactory;

    /**
     * @var DestinationAddressRepository
     */
    private $destinationAddressRepository;

    /**
     * AddShippingPointToOrder constructor.
     * @param Data $checkoutHelper
     * @param ShippingInformationInterface $shippingInformation
     * @param CheckoutSession $checkoutSession
     * @param OrderExtensionFactory $extensionFactory
     * @param CheckoutConfigProvider $configProvider
     * @param DestinationAddressRepository $destinationAddressRepository
     */
    public function __construct(
        Data $checkoutHelper,
        ShippingInformationInterface $shippingInformation,
        CheckoutSession $checkoutSession,
        OrderExtensionFactory $extensionFactory,
        CheckoutConfigProvider $configProvider,
        DestinationAddressRepository $destinationAddressRepository
    ) {
        $this->checkoutHelper = $checkoutHelper;
        $this->shippingInformation = $shippingInformation;
        $this->checkoutSession = $checkoutSession;
        $this->extensionFactory = $extensionFactory;
        $this->configProvider = $configProvider;
        $this->configProvider = $configProvider;
        $this->destinationAddressRepository = $destinationAddressRepository;
    }

    /**
     * @param Observer $observer
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();

        $npDestinationData = $quote->getBillingAddress()->getExtensionAttributes()->getNpDestinationData();

        $npDestinationData['order_id'] = $order->getIncrementId();
        $this->destinationAddressRepository->saveDestAddress($npDestinationData);
    }
}

