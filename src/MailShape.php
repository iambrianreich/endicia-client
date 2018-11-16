<?php

/**
 * This file contains the RWC\Endicia\MailShape enum
 *
 * @author     Joshua Stroup <josh.stroup@reich-consulting.net>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */

namespace RWC\Endicia;

final class MailShape
{
    /**
     * Used to set the "Card" mail shape.
     */
    const CARD = 'Card';

    /**
     * Used to set the "Letter" mail shape.
     */
    const LETTER = 'Letter';

    /**
     * Used to set the "Flat" mail shape.
     */
    const FLAT = 'Flat';

    /**
     * Used to set the "Parcel" mail shape.
     */
    const PARCEL = 'Parcel';

    /**
     * Used to set the "Large Parcel" mail shape.
     */
    const LARGEPARCEL = 'LargeParcel';

    /**
     * Used to set the "Irregular Parcel" mail shape.
     * IrregularParcel is used for First-Class Package Service (if applicable)
     * and Parcel Select Lightweight only.
     */
    const IRREGULARPARCEL = 'IrregularParcel';

    /**
     * Used to set the "Flat Rate Envelope" mail shape.
     */
    const FLATRATEENVELOPE = 'FlatRateEnvelope';

    /**
     * Used to set the "Flat Rate Legal Envelope" mail shape.
     */
    const FLATRATELEGALENVELOPE = 'FlatRateLegalEnvelope';

    /**
     * Used to set the "Flat Rate Padded Envelope" mail shape.
     */
    const FLATRATEPADDEDENVELOPE = 'FlatRatePaddedEnvelope';

    /**
     * Used to set the "Flat Rate Gift Card Envelope" mail shape.
     */
    const FLATRATEGIFTCARDENVELOPE = 'FlatRateGiftCardEnvelope';

    /**
     * Used to set the "Flat Rate Window Envelope" mail shape.
     */
    const FLATRATEWINDOWENVELOPE = 'FlatRateWindowEnvelope';

    /**
     * Used to set the "Flat Rate Cardboard Envelope" mail shape.
     */
    const FLATRATECARDBOARDENVELOPE = 'FlatRateCardboardEnvelope';

    /**
     * Used to set the "Small Flat Rate Envelope" mail shape.
     */
    const SMALLFLATRATEENVELOPE = 'SmallFlatRateEnvelope';

    /**
     * Used to set the "Small Flat Rate Box" mail shape.
     */
    const SMALLFLATRATEBOX = 'SmallFlatRateBox';

    /**
     * Used to set the "Medium Flat Rate Box" mail shape.
     */
    const MEDIUMFLATRATEBOX = 'MediumFlatRateBox';

    /**
     * Used to set the "Large Flat Rate Box" mail shape.
     */
    const LARGEFLATRATEBOX = 'LargeFlatRateBox';

    /**
     * Used to set the "DVD Flat Rate Box" mail shape.
     */
    const DVDFLATRATEBOX = 'DVDFlatRateBox';

    /**
     * Used to set the "Large Video Flat Rate Box" mail shape.
     */
    const LARGEVIDEOFLATRATEBOX = 'LargeVideoFlatRateBox';

    /**
     * Used to set the "Regional Rate Box A" mail shape.
     */
    const REGIONALRATEBOXA = 'RegionalRateBoxA';

    /**
     * Used to set the "Regional Rate Box B" mail shape.
     */
    const REGIONALRATEBOXB = 'RegionalRateBoxB';

    /**
     * Used to set the "Large Flat Rate Board Game" mail shape.
     */
    const LARGEFLATRATEBOARDGAME = 'LargeFlatRateBoardGame';

    /**
     * Used to set the "Box" mail shape.
     */
    const BOX = 'Box';

    /**
     * Used to set the "Half Tray Box" mail shape.
     */
    const HALFTRAYBOX = 'HalfTrayBox';

    /**
     * Used to set the "Full Tray Box" mail shape.
     */
    const FULLTRAYBOX = 'FullTrayBox';

    /**
     * Used to set the "EMM Tray Box" mail shape.
     */
    const EMMTRAYBOX = 'EMMTrayBox';

    /**
     * Used to set the "Flat Tub Tray Box" mail shape.
     */
    const FLATTUBTRAYBOX = 'FlatTubTrayBox';
    
    private const ALLOWED_MAIL_SHAPES = array(self::CARD, self::LETTER, self::FLAT, self::PARCEL, self::LARGEPARCEL, self::IRREGULARPARCEL, self::FLATRATEENVELOPE, self::FLATRATELEGALENVELOPE, self::FLATRATEPADDEDENVELOPE, self::FLATRATEGIFTCARDENVELOPE, self::FLATRATEWINDOWENVELOPE, self::FLATRATECARDBOARDENVELOPE, self::SMALLFLATRATEENVELOPE, self::SMALLFLATRATEBOX, self::MEDIUMFLATRATEBOX, self::LARGEFLATRATEBOX, self::DVDFLATRATEBOX, self::LARGEVIDEOFLATRATEBOX, self::REGIONALRATEBOXA, self::REGIONALRATEBOXB, self::LARGEFLATRATEBOARDGAME, self::BOX, self::HALFTRAYBOX, self::FULLTRAYBOX, self::EMMTRAYBOX, self::FLATTUBTRAYBOX);

    /**
     * Checks to ensure that the selected mail shape is valid
     * against options in this class
     *
     * @param string $mailShape The mail shape to validate
     *
     * @return bool Returns true if the given value is one of the valid mail shapes in this class
     */
    public static function is_valid(?string $mailShape) : bool
    {
        return in_array($mailShape, self::ALLOWED_MAIL_SHAPES);
    }
}