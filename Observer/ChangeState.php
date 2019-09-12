<?php

namespace XCode\PaymentAgeVerification\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Api\OrderRepositoryInterface;
use XCode\Api\Helper\Data;
use XCode\Api\Api\ApiManagementInterface;
use \Magento\Framework\Stdlib\CookieManagerInterface;

class ChangeState implements ObserverInterface
{
    protected $_apiManagement;
    protected $quoteFactory;
    protected $_sessionQuote;
    protected $_order;
    protected $_state;
    protected $helper;
    protected $_cookieManager;
    protected $orderRepository;
    protected $orderManagement;
    protected $_checkoutSession;
    protected $_session_data;
//protected $_orderFactory;

public function __construct(
    \Magento\Sales\Api\OrderManagementInterface $orderManagement,
    ApiManagementInterface $apiManagement,
    \Magento\Backend\Model\Session\Quote $sessionQuote,
    \Magento\Sales\Api\Data\OrderInterface $order,
    \Magento\Directory\Model\CountryFactory $countryFactory,
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
        $this->_countryFactory = $countryFactory;
        $this->_state = $state;
        $this->helper = $helper;
        $this->_cookieManager = $cookieManager;
        $this->orderRepository = $orderRepository;
        $this->quoteFactory = $quoteFactory;
        $this->orderManagement = $orderManagement;
        $this->_checkoutSession = $checkoutSession;
        $this->_session_data = [];
    }


    private function _addFieldToOrderData($strFieldName, $strValue)
    {
        //$strResult = mb_convert_encoding(
        //    str_replace('&', '&amp;', $strValue),
        //    'UTF-8'
        //);

        $this->_session_data[$strFieldName] = $strValue;
    }

    /**
     * Get the Shipping information of order
     *
     * @param \Magento\Sales\Model\Order $order get shipping information
     *
     * @return Shipping information
     */
    private function _getSessionData($order)
    {
        //$billing = $order->getBillingAddress();
        //$shipping = $order->getShippingAddress();
       // if (!empty($shipping)) {
           // $name = $shipping->getFirstname() . ' ' . $shipping->getLastname();

            // $country = '';
            // if($shipping->getCountryId()) {
            //     $country = $this->_countryFactory->create()
            //         ->loadByCode($shipping->getCountryId())->getName();
            // }
            $this->_session_data=array(
                "profile"   =>[
                    "firstname"         =>"",
                    "lastname"          =>"",
                    "street"            =>[
                                            "0" =>"",
                                            "1" =>"",
                                            "2" =>""
                    ],
                    "city"              =>"",
                    "region"            =>"",
                    "region_id"         =>"",
                    "postcode"          =>"",
                    "country"           =>"",
                    "country_id"        =>"",
                    "dob"               =>"",
                    "ssn"               =>""
                ],
                "person"    =>[
                    "email"             =>"",
                    "telephone"         =>"",
                    "password"          =>"",
                    "confirmPassword"   =>"",
                    "confirmationCode"  =>""         
                ],
                "token"     =>"",
                "session_id"=>"",
               "order"     =>[
                    "order_data"        =>"",
                    "order_number"      =>$order->getIncrementId(),
                    "order_total"       =>"",
                    "order_status"      =>$order->getStatus(),
                    "order_date"        =>""
               ]
            );

      //  }
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {


    $orderData = $observer->getEvent()->getOrder()->getData();

    $items= $observer->getEvent()->getOrder()->getItems();

    $orderId = $orderData['increment_id'];
    $quoteId = $orderData['quote_id'];
    // $products = $order->getAllItems();
    
    // $orderId = $this->_order->load($order);
     //$order = $this->_objectManager->create('Magento\Sales\Model\Order')->load($orderId);
    // $orderItems = $order->getAllItems();
         
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);

    // $order2 = $this->_orderFactory->create()->loadByIncrementId($orderId);

    // $a=$order2->getData();

    $entityId=$order->getEntityId();

    $orderData2=$order->getData();

    $orderItems = $order->getAllItems();

    $orderState = $order->getState();

    $orderTotalItems = $orderData['total_item_count'];

    $orderDate = $order->getCreatedAt();

    $orderUpdate = $order->getUpdatedAt();
    // $o = $order->getIncrementId();




    $avcCookie = $this->_cookieManager->getCookie('aspire_token');
    $session_id = $this->_cookieManager->getCookie('session_id');

    $cyaOrderHold = $this->_checkoutSession->getCyaOrderHold();

    $this->_getSessionData($order);
    //error_log(print_r($this->_session_data,true));
    $res = $this->_apiManagement->change_state($this->_session_data);

    //$resx = json_decode($res);
    //error_log($resx);
    error_log($res);

    //$quote = $this->quoteFactory->create()->load($quoteId);

    // $q=$quote->getEntityId();
    // $d=$quote->getData();
    // $n=$quote->getAllItems();
    // $orderItems= array();
    // $orderItems[] = $order->getAllItems();



//     $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/orderPlaced.log');
//     $logger = new \Zend\Log\Logger();
//     $logger->addWriter($writer);
    
//     // $logger->info(json_encode($observer));
//     $logger->info('---------------------------------------------------------------------------------------------');
    
//     if($orderState=="new"){
//         $logger->info('New order placed successfully, Status is "Pending" !!');
//     }
//     if($orderState=="processing"){
//         $logger->info('Order shipment submited, Status is "Processing" !!');
//     }
//     if($orderState=="complete"){
//         $logger->info('Order invoice submited, Status is "Completed" !!');
//     }
//     if($orderState=="canceled"){
//         $logger->info('Order canceled successfully, Status is "Canceled" !!');
//     }
//     if($orderState=="closed"){
//         $logger->info('Order refunded successfully, Status is "Closed" !!');
//     }
//     if($orderState=="holded"){
//         $logger->info('Order holded successfully, Status is "Holded" !!');
//     }
   
   
//     $logger->info('Order ID = '. json_encode( $orderId));

//     //$logger->info(json_encode($quote));

//     //$logger->info(json_encode($orderItems));
//     $logger->info('Entity ID = '.json_encode($entityId));
//     $logger->info('Total Items = '.json_encode($orderTotalItems));
//     //$logger->info(json_encode($q));
//     //$logger->info(json_encode($o));
//    // $logger->info(json_encode($d));
//    // $logger->info(json_encode($n));
//    //**** */ $logger->info(json_encode($orderData2));

//     //$logger->info(json_encode($a));

//    //*** */ $logger->info(json_encode($items));
//     //** */$logger->info(json_encode($orderItems));

//     $counter=1;
//     foreach ($items as $item) {

//         $logger->info(json_encode('Item '.$counter.' :'));
        
        
//         $itemSku = $item->getSku();
        
//         $logger->info('      SKU:'.json_encode($itemSku));

//         $itemName = $item->getName();
//         $logger->info('      Product Name:'.json_encode($itemName));

//         $itemProductId = $item->getProductId();
//         $logger->info('      Product ID:'.json_encode($itemProductId));

//         $itemPrice = $item->getPrice();
//         $logger->info('      Price:'.json_encode($itemPrice));

//         $itemQty = $item->getQtyOrdered();
//         $logger->info('      Qty:'.json_encode($itemQty));
       
//         $itemArray['itemID']=$itemProductId;
//         $itemArray['itemSKU']=$itemSku;
//         $itemArray['itemQty']=$itemQty;
//         $itemArray['itemName']=$itemName;        
//         $itemArray['itemPrice']=$itemPrice;
//         $itemsArray[]=$itemArray;
//         $counter++;

// }
// $logger->info('ALL ORDER DATA :');
// $logger->info(json_encode($orderData));

// $logger->info('ALL ITEMS DATA :');
// foreach ($items as $item) {
//     $itemData=$item->getData();
//     $logger->info(json_encode($itemData));

// }

//    // $logger->info(json_encode($order));
// //     $ProductIds = array();

// //    foreach( $orderItems as $item ) {
// //        $ProductIds[] = 'quantity'=>$item->getQtyOrdered();

// //  //$logger->info(print_r($order,true));
// //  //$logger->info($order);
// // //     // $logger->info(100,$ProductIds[]);
// // //     // $logger->info(100,$ProductIds);
// // //     // $logger->info('test');

// //  } 
// //  $qty=$orderItems[0]->getQtyOrdered();
// //  $logger->info(json_encode($qty));
//    // $logger->info($p);
//     //sleep ( 10 );
//      //echo "<script type='text/javascript'>alert('tesssst');</script>";
//     // return $this;
//   //  $orders= array();

//   //$itemsOrdered['items']=$itemsArray;

//     $orderArray[]=array(
//         'orderID' => $orderId ,
//         'orderDate' => $orderDate ,
//         'orderUpdate' => $orderUpdate ,
//         'orderState' => $orderState,
//         'orderItems'=>$itemsArray

//     );

//   //  );
//     $orders['orders']=$orderArray;
//     //$orderJSON = json_encode($orders). PHP_EOL;
//     $orderJSON = json_encode($orders);

//     if(file_exists("order.json")){
//         $fileContents=file_get_contents('order.json');
//         $jsonDecodedVar = json_decode($fileContents,TRUE);
//         //$jsonDecodedVar[]  = $orderArray;
//         array_push($jsonDecodedVar['orders'],array(
//             'orderID' => $orderId ,
//             'orderDate' => $orderDate ,
//             'orderUpdate' => $orderUpdate ,
//             'orderState' => $orderState,
//             'orderItems'=>$itemsArray
    
//         ));
//         $jsonTemp = json_encode($jsonDecodedVar);
//         file_put_contents('order.json',$jsonTemp);

//         //  $orderFile = fopen("order.json","w");
//         //  fwrite($orderFile,$jsonTemp);
//         //  fclose($orderFile);

//     }else{
//         $orderFile = fopen("order.json","w");
//         fwrite($orderFile,$orderJSON);
//         fclose($orderFile);
//     }    */
    }
}