<?php
namespace XCode\PaymentAgeVerification\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use XCode\AgeVerificationCheckout\Helper\Data;
use XCode\Api\Api\ApiManagementInterface;
use \Magento\Framework\App\Filesystem\DirectoryList;
use \Magento\Framework\Stdlib\CookieManagerInterface;
use \Magento\Checkout\Model\Session;

class AgeVerify implements ObserverInterface
{
    protected $_apiManagement;
    protected $_cookieManager;
    protected $helper;
    protected $directory_list;
    protected $custmperSession;
    protected $_checkoutSession;
    

    public function __construct(
        Session $checkoutSession,
        ApiManagementInterface $apiManagement,
        CookieManagerInterface $cookieManager,
        DirectoryList $directory_list,
        Data $helper,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\ResponseFactory $responseFactory

    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_apiManagement = $apiManagement;
        $this->checkoutSession = $checkoutSession;
        $this->_cookieManager = $cookieManager;
        $this->helper = $helper;
        $this->directory_list = $directory_list;
        $this->_url = $url;
        $this->_responseFactory = $responseFactory;

        // Observer initialization code...
        // You can use dependency injection to get any class this observer may need.
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $avcCookie = $this->_cookieManager->getCookie('aspire_token');
        $session_id = $this->_cookieManager->getCookie('session_id');


        
        // if (true) {
        //     $session_data = [
        //         "person"=>[],
        //         "profile"=>[],
        //         "session_id"=> $session_id,
        //         "aspire_token" => $avcCookie
        //     ];




        //    $res = $this->_apiManagement->validate($session_data);
        //    $resx = json_decode($res);

        //    if($resx->cya_code == 200){
        //     $this->_checkoutSession->setCyaOrderHold(false); 
        //    }else if ($resx->cya_code == 201){
        //     $this->_checkoutSession->setCyaOrderHold(true);
        //    } else {
        //         //Print out the contents.
        //         // echo $contents;
        //         //  $this->helper->setCyaOrderId($cyaOrderId);
        //         $basePath = $this->directory_list->getPath('base');
        //         $CustomRedirectionUrl = $this->_url->getUrl();
        //         $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl . 'age-verification-failed')->sendResponse();
        //         exit();
        //     }
        // } else {
        //     //echo '<script src="https://dev.aspirevapeco.com/cache/'. $key .'.js" type="text/javascript"></script>';
        //     exit; //popup
        // }

    }
}
