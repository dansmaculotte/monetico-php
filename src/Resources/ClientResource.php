<?php

namespace DansMaCulotte\Monetico\Resources;

class ClientResource extends Ressource
{
    protected $keys = [
        'civility',
        'name',
        'firstName',
        'lastName',
        'middleName',
        'address',
        'addressLine1',
        'addressLine2',
        'addressLine3',
        'city',
        'postalCode',
        'country',
        'stateOrProvince',
        'countrySubdivision',
        'email',
        'birthLastName',
        'birthCity',
        'birthPostalCode',
        'birthStateOrProvince',
        'birthdate',
        'phone',
        'nationalIDNumber',
        'suspiciousAccountActivity',
        'authenticationMethod',
        'authenticationTimestamp',
        'priorAuthenticationTimestamp',
        'paymentMeanAge',
        'lastYearTransactions',
        'last24HoursTransactions',
        'addCardNbLast24Hours',
        'last6MonthsPurchase',
        'lastPasswordChange',
        'accountAge',
        'lastAccountModification',
    ];
}
