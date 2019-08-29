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

    /**
     * CartItemResource constructor.
     * @param int $unitPrice
     * @param int $quantity
     * @throws Exception
     */
    public function __construct(int $unitPrice, int $quantity)
    {
        parent::__construct([
            'unitPrice' => $unitPrice,
            'quantity' => $quantity,
        ]);
    }
}
