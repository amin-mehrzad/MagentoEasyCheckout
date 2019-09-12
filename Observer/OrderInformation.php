<?php

namespace XCode\PaymentAgeVerification\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use XCode\Api\Helper\Data;
use XCode\Api\Api\ApiManagementInterface;
use \Magento\Framework\Stdlib\CookieManagerInterface;

class OrderInformation implements ObserverInterface
{
    protected $_aipManagement;
    protected $quoteFactory;
    protected $_sessionQuote;
    protected $_order;
    protected $_state;
    protected $helper;
    protected $_cookieManager;
    protected $orderRepository;
    protected $orderManagement;
    protected $_checkoutSession;

//protected $_orderFactory;

    public function __construct(
        \Magento\Sales\Api\OrderManagementInterface $orderManagement,
        ApiManagementInterface $apiManagement,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Sales\Api\Data\OrderInterface $order,
        //   \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\App\State $state,
        CookieManagerInterface $cookieManager,
        OrderRepositoryInterface $orderRepository,
        Data $helper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Model\QuoteFactory $quoteFactory

    ) {
        $this->_apiManagement = $apiManagement;
        $this->_order = $order;
        $this->_sessionQuote = $sessionQuote;
        //  $this->_orderFactory = $orderFactory;
        $this->_state = $state;
        $this->helper = $helper;
        $this->_cookieManager = $cookieManager;
        $this->orderRepository = $orderRepository;
        $this->quoteFactory = $quoteFactory;
        $this->orderManagement = $orderManagement;
        $this->_checkoutSession = $checkoutSession;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $area = $this->_state->getAreaCode();

        if ($area != "adminhtml") {
            $order = $this->_checkoutSession->getLastRealOrder();
            $key = $this->helper->ageverification_publicKey();
            $secretKey = $this->helper->ageverification_secretKey();

            $avcCookie = $this->_cookieManager->getCookie('aspire_token');
            $session_id = $this->_cookieManager->getCookie('session_id');

            $order = $this->_checkoutSession->getLastRealOrder();
            $cyaOrderHold = $this->_checkoutSession->getCyaOrderHold();
            
            error_log("Xxxx");
            error_log(print_r($order->getData(),true));

            if ($order->canHold() && $cyaOrderHold) {
                $order->setState("holded")->setStatus("holded");
                $order->save();
            }
       
        if (true) {
            $session_data = [
                "person"=>[],
                "profile"=>[],
                "aspire_token" => $avcCookie,
                "session_id"   => $session_id,
                "order"=>[
                    "order_data"    => $order->getData(),
                    "order_number"  => $order->getIncrementId(),
                    "order_total"   => $order->getGrandTotal(),
                    "order_status"  => $order->getStatus(),
                    "order_date"    => $order->getCreatedAt()
                ]
            ];

            $res = $this->_apiManagement->finalize($session_data);
            error_log(print_r($res, true));
            
            $resx = json_decode($res);
            if ($resx->cya_code != 200 && !$cyaOrderHold) {
                $this->orderManagement->hold($order->getEntityId());
            }
            
        } else {
                exit; //popup
        }

        }
    }
}
