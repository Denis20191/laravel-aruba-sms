<?php

namespace OfflineAgency\ArubaSms\Support;

class PhoneNumberFormatter
{
    /**
     * Format an Italian phone number: strip spaces and add +39 prefix if missing.
     *
     * This replicates the formatting logic used throughout the main app
     * (e.g., ArubaSmsCommand, SendOrderCreatedNotification, OtpNotification).
     */
    public static function format(string $phoneNumber): string
    {
        $phoneNumber = self::stripSpaces($phoneNumber);

        if ($phoneNumber === '') {
            return '';
        }

        if(str_starts_with($phoneNumber, '00')) {
            $phoneNumber = str_replace('00', '+', $phoneNumber);
        }

        if(str_starts_with($phoneNumber, '39')) {
            $phoneNumber = '+'.$phoneNumber;
        }

        if (! str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+39'.$phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Remove all spaces from a phone number.
     */
    public static function stripSpaces(string $phoneNumber): string
    {
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        $phoneNumber = str_replace("\t", '', $phoneNumber);
        $phoneNumber = str_replace("\n", '', $phoneNumber);
        return $phoneNumber;
    }
}
