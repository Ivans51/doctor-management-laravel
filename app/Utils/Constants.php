<?php

namespace App\Utils;

class Constants
{
    public static $ADMIN = 'admin';
    public static $DOCTOR = 'doctor';
    public static $PATIENT = 'patient';
    public static $ACTIVE = 'active';
    public static $INACTIVE = 'inactive';
    public static $PAYMENT_STATUS_PAID = 'paid';
    public static $PAYMENT_STATUS_UNPAID = 'unpaid';
    public static $PAYMENT_STATUS_PENDING = 'pending';
    public static $PAYMENT_STATUS_CANCEL = 'cancel';
    public static $PAYMENT_STATUS_REFUND = 'refund';
    public static $PAYMENT_STATUS_FAILED = 'failed';
    public static $MALE = 'Male';
    public static $FEMALE = 'Female';
    public static $PAYMENT_METHODS = [
        'paypal',
        'stripe',
    ];
    public static $PENDING = 'pending';
    public static $APPROVED = 'approved';
    public static $REJECTED = 'rejected';

    public static $STRIPE = 'stripe';
    public static $PAYPAL = 'paypal';
    public static $CURRENCY_USD = 'USD';
}
