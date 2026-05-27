<?php
 
namespace OfflineAgency\ArubaSms\Support;
 
use InvalidArgumentException;
 
/**
 * Formats and validates phone numbers according to the E.164 standard.
 * @see https://en.wikipedia.org/wiki/E.164
 */
final class PhoneNumberFormatter
{
    /**
     * Prevent instantiation — this class is a pure static helper.
     */
    private function __construct() {}
 
    /**
     * Format a phone number to E.164 format.
     * @param  string  $phoneNumber        Raw phone number in any common format.
     * @param  string  $defaultCountryCode ITU-T country code without '+' (default: '39' for Italy).
     * @return string                      E.164-formatted number, or empty string for blank input.
     */
    public static function format(string $phoneNumber, string $defaultCountryCode = '39'): string
    {
        $phoneNumber = self::stripNoise($phoneNumber);
 
        if ($phoneNumber === '') {
            return '';
        }
 
        // Already fully qualified — nothing to do.
        if (str_starts_with($phoneNumber, '+')) {
            return $phoneNumber;
        }
 
        // Has country code digits but is missing the leading '+' (e.g. '39331234567').
        if (str_starts_with($phoneNumber, $defaultCountryCode)) {
            return '+'.$phoneNumber;
        }
 
        // Bare local number — prepend the full international prefix.
        return '+'.$defaultCountryCode.$phoneNumber;
    }
 
    /**
     * Format a phone number and throw if the result is not E.164-valid.
     * @param  string  $phoneNumber        Raw phone number.
     * @param  string  $defaultCountryCode ITU-T country code without '+' (default: '39').
     * @return string                      Valid E.164 number.
     *
     * @throws InvalidArgumentException    If the result fails E.164 validation.
     */
    public static function formatOrFail(string $phoneNumber, string $defaultCountryCode = '39'): string
    {
        $formatted = self::format($phoneNumber, $defaultCountryCode);
 
        if (! self::isValid($formatted)) {
            throw new InvalidArgumentException(
                "The phone number \"{$phoneNumber}\" could not be formatted to a valid E.164 number."
            );
        }
 
        return $formatted;
    }
 
    /**
     * Validate that a phone number conforms to E.164.
     * @param  string  $phoneNumber Phone number to validate (formatted or raw).
     * @return bool                 True if the number is E.164-compliant.
     */
    public static function isValid(string $phoneNumber): bool
    {
        return (bool) preg_match('/^\+[1-9]\d{6,14}$/', $phoneNumber);
    }
 
    /**
     * Remove noise characters from a phone number.
     * @param  string  $phoneNumber Raw phone number.
     * @return string               Phone number with noise characters removed.
     */
    public static function stripNoise(string $phoneNumber): string
    {
        return preg_replace('/[\s\-().\x{00A0}]+/u', '', $phoneNumber);
    }
}