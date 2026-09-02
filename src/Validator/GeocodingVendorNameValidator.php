<?php

namespace OneToMany\Geocoder\Validator;

use OneToMany\Geocoder\Contract\Exception\ExceptionInterface as GeocoderExceptionInterface;
use OneToMany\Geocoder\GeocodingVendor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use function is_string;

final class GeocodingVendorNameValidator extends ConstraintValidator
{
    /**
     * @see Symfony\Component\Validator\ConstraintValidator
     *
     * @throws UnexpectedTypeException when the constraint is not a {@see GeocodingVendorName} object
     * @throws UnexpectedValueException when the value is not null or a string
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof GeocodingVendorName) {
            throw new UnexpectedTypeException($constraint, GeocodingVendorName::class);
        }

        if (null === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            GeocodingVendor::create($value);
        } catch (GeocoderExceptionInterface $e) {
            $this->context->buildViolation($e->getMessage())->addViolation();
        }
    }
}
