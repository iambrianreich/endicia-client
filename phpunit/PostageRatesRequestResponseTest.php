<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\AbstractResponse;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\PostageRatesRequestResponse;

class PostageRateRequestResponseTest extends TestCase
{
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
        '<PostageRatesRequestResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="www.envmgr.com/LabelService">' .
        '<RequesterID>' . htmlentities($requesterId) . '</RequesterID>' .
        '<RequestID>' . htmlentities($requestId) . '</RequestID>' .
        '<Status>' . $status . '</Status>' .
        '<ErrorMessage>' . $errorMessage . '</ErrorMessage>' .
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
                '<GroupedExtraServices Services="' . $services . '">' .
                    '<FeeAmount>' . $groupedServicesTotal . '</FeeAmount>' .
                '</GroupedExtraServices>' .
            '</Fees>' .
            '<DeliveryTimeDays>' . $deliveryTimeDays .'</DeliveryTimeDays>' .
            '<EstimatedDeliveryDate>' . $estimatedDeliveryDate .'</EstimatedDeliveryDate>' .
        '</PostagePrice>' .
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
                '<GroupedExtraServices Services="' . $services . '">' .
                    '<FeeAmount>' . $groupedServicesTotal . '</FeeAmount>' .
                '</GroupedExtraServices>' .
            '</Fees>' .
            '<DeliveryTimeDays>' . $deliveryTimeDays .'</DeliveryTimeDays>' .
            '<EstimatedDeliveryDate>' . $estimatedDeliveryDate .'</EstimatedDeliveryDate>' .
        '</PostagePrice>' .
        '</PostageRatesRequestResponse>';

        $response = PostageRatesRequestResponse::fromXml($xml);
        $this->assertTrue($response->isSuccessful());

        $postagePrices = $response->getPostagePrices();

        $this->assertEquals($postagePrices[0]['totalAmount'], $postagePriceTotal);

        $this->assertEquals($postagePrices[0]['postage']['totalAmount'], $postageTotal);
        $this->assertEquals($postagePrices[0]['postage']['mailService'], $mailService);
        $this->assertEquals($postagePrices[0]['postage']['zone'], $zone);
        $this->assertEquals($postagePrices[0]['postage']['intraNDC'], $intraNDC);
        $this->assertEquals($postagePrices[0]['postage']['pricing'], $pricing);

        $this->assertEquals($postagePrices[0]['fees']['certificateOfMailing'], $certOfMailingFee);
        $this->assertEquals($postagePrices[0]['fees']['certifiedMail'], $certifiedMailFee);
        $this->assertEquals($postagePrices[0]['fees']['collectOnDelivery'], $codFee);
        $this->assertEquals($postagePrices[0]['fees']['deliveryConfirmation'], $deliveryConfirmationFee);
        $this->assertEquals($postagePrices[0]['fees']['electronicReturnReceipt'], $electronicReturnFee);
        $this->assertEquals($postagePrices[0]['fees']['insuredMail'], $insuredMailFee);
        $this->assertEquals($postagePrices[0]['fees']['registeredMail'], $registeredMailFee);
        $this->assertEquals($postagePrices[0]['fees']['restrictedDelivery'], $restrictedDeliveryFee);
        $this->assertEquals($postagePrices[0]['fees']['returnReceipt'], $returnReceiptFee);
        $this->assertEquals($postagePrices[0]['fees']['returnReceiptForMerchandise'], $merchandiseReturnFee);
        $this->assertEquals($postagePrices[0]['fees']['signatureConfirmation'], $signatureConfirmationFee);
        $this->assertEquals($postagePrices[0]['fees']['specialHandling'], $specialHandlingFee);
        $this->assertEquals($postagePrices[0]['fees']['adultSignature'], $adultSignatureFee);
        $this->assertEquals($postagePrices[0]['fees']['adultSignatureRestrictedDelivery'], $adultSignatureRestrictedFee);
        $this->assertEquals($postagePrices[0]['fees']['liveAnimalSurcharge'], $liveAnimalFee);
        $this->assertEquals($postagePrices[0]['fees']['amDelivery'], $amDeliveryFee);

        $this->assertEquals($postagePrices[0]['fees']['groupedExtraServices']['services'], $services);
        $this->assertEquals($postagePrices[0]['fees']['groupedExtraServices']['feeAmount'], $groupedServicesTotal);

        $this->assertEquals($postagePrices[0]['deliveryTimeDays'], $deliveryTimeDays);
        $this->assertEquals($postagePrices[0]['estimatedDeliveryDate'], $estimatedDeliveryDate);


        $this->assertEquals($postagePrices[1]['totalAmount'], $postagePriceTotal);

        $this->assertEquals($postagePrices[1]['postage']['totalAmount'], $postageTotal);
        $this->assertEquals($postagePrices[1]['postage']['mailService'], $mailService);
        $this->assertEquals($postagePrices[1]['postage']['zone'], $zone);
        $this->assertEquals($postagePrices[1]['postage']['intraNDC'], $intraNDC);
        $this->assertEquals($postagePrices[1]['postage']['pricing'], $pricing);

        $this->assertEquals($postagePrices[1]['fees']['certificateOfMailing'], $certOfMailingFee);
        $this->assertEquals($postagePrices[1]['fees']['certifiedMail'], $certifiedMailFee);
        $this->assertEquals($postagePrices[1]['fees']['collectOnDelivery'], $codFee);
        $this->assertEquals($postagePrices[1]['fees']['deliveryConfirmation'], $deliveryConfirmationFee);
        $this->assertEquals($postagePrices[1]['fees']['electronicReturnReceipt'], $electronicReturnFee);
        $this->assertEquals($postagePrices[1]['fees']['insuredMail'], $insuredMailFee);
        $this->assertEquals($postagePrices[1]['fees']['registeredMail'], $registeredMailFee);
        $this->assertEquals($postagePrices[1]['fees']['restrictedDelivery'], $restrictedDeliveryFee);
        $this->assertEquals($postagePrices[1]['fees']['returnReceipt'], $returnReceiptFee);
        $this->assertEquals($postagePrices[1]['fees']['returnReceiptForMerchandise'], $merchandiseReturnFee);
        $this->assertEquals($postagePrices[1]['fees']['signatureConfirmation'], $signatureConfirmationFee);
        $this->assertEquals($postagePrices[1]['fees']['specialHandling'], $specialHandlingFee);
        $this->assertEquals($postagePrices[1]['fees']['adultSignature'], $adultSignatureFee);
        $this->assertEquals($postagePrices[1]['fees']['adultSignatureRestrictedDelivery'], $adultSignatureRestrictedFee);
        $this->assertEquals($postagePrices[1]['fees']['liveAnimalSurcharge'], $liveAnimalFee);
        $this->assertEquals($postagePrices[1]['fees']['amDelivery'], $amDeliveryFee);

        $this->assertEquals($postagePrices[1]['fees']['groupedExtraServices']['services'], $services);
        $this->assertEquals($postagePrices[1]['fees']['groupedExtraServices']['feeAmount'], $groupedServicesTotal);

        $this->assertEquals($postagePrices[1]['deliveryTimeDays'], $deliveryTimeDays);
        $this->assertEquals($postagePrices[1]['estimatedDeliveryDate'], $estimatedDeliveryDate);
    }
}