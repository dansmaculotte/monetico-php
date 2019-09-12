<?php


namespace DansMaCulotte\Monetico\Resources;

use DansMaCulotte\Monetico\Exceptions\Exception;

class CartItemResource extends Ressource
{
    protected $keys = [
        'name',
        'description',
        'productCode',
        'imageURL',
        'unitPrice',
        'quantity',
        'productSKU',
        'productRisk',
    ];
}
