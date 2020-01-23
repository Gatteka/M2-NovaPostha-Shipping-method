<?php
namespace Learn\NovaPoshta\Model\Rewrite;

use Exception;
use Magento\Checkout\Api\PaymentInformationManagementInterface;
use Magento\Checkout\Model\GuestPaymentInformationManagement as Base;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\GuestBillingAddressManagementInterface;
use Magento\Quote\Api\GuestCartManagementInterface;
use Magento\Quote\Api\GuestPaymentMethodManagementInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Psr\Log\LoggerInterface;

/**
 * Class GuestPaymentInformationManagement
 * @package Learn\NovaPoshta\Model\Rewrite
 */
class GuestPaymentInformationManagement extends Base
{
    public $connectionPool;

    public $logger;

    /**
     * GuestPaymentInformationManagement constructor.
     * @param GuestBillingAddressManagementInterface $billingAddressManagement
     * @param GuestPaymentMethodManagementInterface $paymentMethodManagement
     * @param GuestCartManagementInterface $cartManagement
     * @param PaymentInformationManagementInterface $paymentInformationManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param CartRepositoryInterface $cartRepository
     * @param ResourceConnection|null $connectionPool
     */
    public function __construct(
        GuestBillingAddressManagementInterface $billingAddressManagement,
        GuestPaymentMethodManagementInterface $paymentMethodManagement,
        GuestCartManagementInterface $cartManagement,
        PaymentInformationManagementInterface $paymentInformationManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        CartRepositoryInterface $cartRepository,
        ResourceConnection $connectionPool = null
    ) {
        $this->connectionPool = $connectionPool;
        parent::__construct(
            $billingAddressManagement,
            $paymentMethodManagement,
            $cartManagement,
            $paymentInformationManagement,
            $quoteIdMaskFactory,
            $cartRepository,
            $connectionPool
        );
    }

    /**
     * @param string $cartId
     * @param string $email
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @return int
     * @throws Exception
     */
    public function savePaymentInformationAndPlaceOrder(
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        $salesConnection = $this->connectionPool->getConnection('sales');
        $checkoutConnection = $this->connectionPool->getConnection('checkout');
        $salesConnection->beginTransaction();
        $checkoutConnection->beginTransaction();

        try {
            $this->savePaymentInformation($cartId, $email, $paymentMethod, $billingAddress);
            try {
                $orderId = $this->cartManagement->placeOrder($cartId, $paymentMethod);
            } catch (LocalizedException $e) {
                throw new CouldNotSaveException(
                    __($e->getMessage()),
                    $e
                );
            } catch (Exception $e) {
                $this->getLogger()->critical($e);
                throw new CouldNotSaveException(
                    __('An error occurred on the server. Please try to place the order again.'),
                    $e
                );
            }
            $salesConnection->commit();
            $checkoutConnection->commit();
        } catch (Exception $e) {
            $salesConnection->rollBack();
            $checkoutConnection->rollBack();
            throw $e;
        }

        return $orderId;
    }

    /**
     * Get logger instance
     *
     * @return LoggerInterface
     * @deprecated 100.1.8
     */
    private function getLogger()
    {
        if (!$this->logger) {
            $this->logger = ObjectManager::getInstance()->get(LoggerInterface::class);
        }
        return $this->logger;
    }
}
