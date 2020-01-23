<?php

namespace Learn\NovaPoshta\Plugin;

use Exception;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Learn\NovaPoshta\Model\DestinationAddressRepository;
use Learn\NovaPoshta\Model\Carrier\NovaPoshtaShipping;

/**
 * Class AddExtensionAttributesToOrderPlugin
 * @package Learn\NovaPoshta\Plugin
 */
class AddExtensionAttributesToOrder
{
    /**
     * Order Comment field name
     */
    const FIELD_NAME = 'order_comment';

    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    protected $extensionFactory;

    /**
     * @var DestinationAddressRepository
     */
    protected $repository;

    /**
     * AddExtensionAttributesToOrderPlugin constructor.
     * @param OrderExtensionFactory $extensionFactory
     * @param DestinationAddressRepository $repository
     */
    public function __construct(
        OrderExtensionFactory $extensionFactory,
        DestinationAddressRepository $repository
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->repository = $repository;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface|string
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {

        if ($order->getShippingMethod() === NovaPoshtaShipping::NAME) {
            try {
                $destinationData = $this->repository->getDestAddressByOrderId(intval($order->getIncrementId()));
                $extensionAttributes = $order->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ?? $this->extensionFactory->create();
                $extensionAttributes->setNpCarrierData($destinationData);
                $order->setExtensionAttributes($extensionAttributes);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        return $order;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            //$orderComment = $order->getData(self::FIELD_NAME);
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setNpCarrierData('25');
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }

    /**
//     * @param  OrderRepositoryInterface $subject
//     * @param  OrderInterface $entity
//     * @return OrderInterface
//     */
//    public function afterSave(OrderRepositoryInterface $subject, OrderInterface $entity)
//    {
//        $extensionAttributes = $entity->getExtensionAttributes();
//        //$ourCustomData = $extensionAttributes->getOurCustomData();
//
//        //$this->repository->saveDestAddress($ourCustomData);
//
//        return $entity;
//    }
}

