<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\AbstractResponse;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\PostageRateRequestResponse;

class PostageRateRequestResponseTest extends TestCase
{
    public function testFromXMLSucceedsWhenPostageAndPostagePriceExists()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '0';
        $errorMessage   = 'Very bad things here';
        $requestId      = str_repeat('a', 50);
        $mailService    = 'Mail Service';
        $rate           = 10.0;
        $xml            =
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<PostageRateRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
        '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
        '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
        '<Status>' . $status . '</Status>' .
        '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
        '<Postage>' .
        '<MailService>' . $mailService . '</MailService>' .
        '<Rate>' . $rate . '</Rate>' .
        '</Postage>' .
        '<PostagePrice TotalAmount="10.0"></PostagePrice>' .
        '</PostageRateRequestResponse>';

        $response = PostageRateRequestResponse::fromXml($xml);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response->getPostage(), null);
        $this->assertNotEquals($response->getPostagePrice(), null);
    }

    public function testParsesAllValidPostagePriceElements()
    {
        $requesterId                 = str_repeat('4', 4);
        $status                      = '0';
        $errorMessage                = 'Very bad things here';
        $requestId                   = str_repeat('a', 50);
        $postagePriceTotal           = 30.0;
        $postageTotal                = 14.0;
        $feesTotal                   = 16.0;
        $groupedServicesTotal        = 0.0;
        $certOfMailingFee            = 1.0;
        $certifiedMailFee            = 1.0;
        $codFee                      = 1.0;
        $deliveryConfirmationFee     = 1.0;
        $electronicReturnFee         = 1.0;
        $insuredMailFee              = 1.0;
        $registeredMailFee           = 1.0;
        $restrictedDeliveryFee       = 1.0;
        $returnReceiptFee            = 1.0;
        $merchandiseReturnFee        = 1.0;
        $signatureConfirmationFee    = 1.0;
        $specialHandlingFee          = 1.0;
        $adultSignatureFee           = 1.0;
        $adultSignatureRestrictedFee = 1.0;
        $liveAnimalFee               = 1.0;
        $amDeliveryFee               = 1.0;
        $mailService                 = 'Mail Service';
        $zone                        = 3;
        $intraNDC                    = true;
        $pricing                     = 'Retail';
        $services                    = 'CertifiedMail;AdultSignature;RestrictedDelivery';
        $deliveryTimeDays            = '2';
        $estimatedDeliveryDate       = '20181031';
        $xml                         =
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<PostageRateRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
        '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
        '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
        '<Status>' . $status . '</Status>' .
        '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
        '<Zone>' . $zone . '</Zone>' .
        '<PostagePrice TotalAmount="' . $postagePriceTotal . '">' .
            '<Postage TotalAmount="' . $postageTotal . '">' .
                '<MailService>' . $mailService . '</MailService>' .
                '<Zone>' . $zone . '</Zone>' .
                '<IntraNDC>' . $intraNDC . '</IntraNDC>' .
                '<Pricing>' . $pricing . '</Pricing>' .
            '</Postage>' .
            '<Fees TotalAmount="' . $feesTotal . '">'.
                '<CertificateOfMailing>' . $certOfMailingFee .'</CertificateOfMailing>' .
                '<CertifiedMail>' . $certifiedMailFee .'</CertifiedMail>' .
                '<CollectOnDelivery>' . $codFee .'</CollectOnDelivery>' .
                '<DeliveryConfirmation>' . $deliveryConfirmationFee .'</DeliveryConfirmation>' .
                '<ElectronicReturnReceipt>' . $electronicReturnFee .'</ElectronicReturnReceipt>' .
                '<InsuredMail>' . $insuredMailFee .'</InsuredMail>' .
                '<RegisteredMail>' . $registeredMailFee .'</RegisteredMail>' .
                '<RestrictedDelivery>' . $restrictedDeliveryFee .'</RestrictedDelivery>' .
                '<ReturnReceipt>' . $returnReceiptFee .'</ReturnReceipt>' .
                '<ReturnReceiptForMerchandise>' . $merchandiseReturnFee .'</ReturnReceiptForMerchandise>' .
                '<SignatureConfirmation>' . $signatureConfirmationFee .'</SignatureConfirmation>' .
                '<SpecialHandling>' . $specialHandlingFee .'</SpecialHandling>' .
                '<AdultSignature>' . $adultSignatureFee .'</AdultSignature>' .
                '<AdultSignatureRestrictedDelivery>' . $adultSignatureRestrictedFee .'</AdultSignatureRestrictedDelivery>' .
                '<LiveAnimalSurcharge>' . $liveAnimalFee .'</LiveAnimalSurcharge>' .
                '<AMDelivery>' . $amDeliveryFee .'</AMDelivery>' .
                '<DeliveryTimeDays>' . $deliveryTimeDays .'</DeliveryTimeDays>' .
                '<EstimatedDeliveryDate>' . $estimatedDeliveryDate .'</EstimatedDeliveryDate>' .
                '<GroupedExtraServices Services="' . $services . '">' .
                    '<FeeAmount>' . $groupedServicesTotal . '</FeeAmount>' .
                '</GroupedExtraServices>' .
            '</Fees>' .
        '</PostagePrice>' .
        '</PostageRateRequestResponse>';

        $response = PostageRateRequestResponse::fromXml($xml);
        $this->assertTrue($response->isSuccessful());

        $responseZone = $response->getZone();
        $postagePrice = $response->getPostagePrice();

        $this->assertEquals($responseZone, $zone);

        $this->assertEquals($postagePrice['totalAmount'], $postagePriceTotal);

        $this->assertEquals($postagePrice['postage']['totalAmount'], $postageTotal);
        $this->assertEquals($postagePrice['postage']['mailService'], $mailService);
        $this->assertEquals($postagePrice['postage']['zone'], $zone);
        $this->assertEquals($postagePrice['postage']['intraNDC'], $intraNDC);
        $this->assertEquals($postagePrice['postage']['pricing'], $pricing);

        $this->assertEquals($postagePrice['fees']['certificateOfMailing'], $certOfMailingFee);
        $this->assertEquals($postagePrice['fees']['certifiedMail'], $certifiedMailFee);
        $this->assertEquals($postagePrice['fees']['collectOnDelivery'], $codFee);
        $this->assertEquals($postagePrice['fees']['deliveryConfirmation'], $deliveryConfirmationFee);
        $this->assertEquals($postagePrice['fees']['electronicReturnReceipt'], $electronicReturnFee);
        $this->assertEquals($postagePrice['fees']['insuredMail'], $insuredMailFee);
        $this->assertEquals($postagePrice['fees']['registeredMail'], $registeredMailFee);
        $this->assertEquals($postagePrice['fees']['restrictedDelivery'], $restrictedDeliveryFee);
        $this->assertEquals($postagePrice['fees']['returnReceipt'], $returnReceiptFee);
        $this->assertEquals($postagePrice['fees']['returnReceiptForMerchandise'], $merchandiseReturnFee);
        $this->assertEquals($postagePrice['fees']['signatureConfirmation'], $signatureConfirmationFee);
        $this->assertEquals($postagePrice['fees']['specialHandling'], $specialHandlingFee);
        $this->assertEquals($postagePrice['fees']['adultSignature'], $adultSignatureFee);
        $this->assertEquals($postagePrice['fees']['adultSignatureRestrictedDelivery'], $adultSignatureRestrictedFee);
        $this->assertEquals($postagePrice['fees']['liveAnimalSurcharge'], $liveAnimalFee);
        $this->assertEquals($postagePrice['fees']['amDelivery'], $amDeliveryFee);

        $this->assertEquals($postagePrice['fees']['groupedExtraServices']['services'], $services);
        $this->assertEquals($postagePrice['fees']['groupedExtraServices']['feeAmount'], $groupedServicesTotal);
    }

    public function testParsesAllValidPostageElements()
    {
        $requesterId    = str_repeat('4', 4);
        $status         = '0';
        $errorMessage   = 'Very bad things here';
        $requestId      = str_repeat('a', 50);
        $mailService    = 'Mail Service';
        $rate           = 10.0;
        $xml            =
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<PostageRateRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
        '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
        '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
        '<Status>' . $status . '</Status>' .
        '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
        '<Postage>' .
        '<MailService>' . $mailService . '</MailService>' .
        '<Rate>' . $rate . '</Rate>' .
        '</Postage>' .
        '</PostageRateRequestResponse>';

        $response = PostageRateRequestResponse::fromXml($xml);
        $this->assertTrue($response->isSuccessful());

        $postage = $response->getPostage();

        $this->assertEquals($postage['mailService'], $mailService);
        $this->assertEquals($postage['rate'], $rate);
    }
}