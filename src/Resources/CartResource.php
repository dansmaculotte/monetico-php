<?php


namespace DansMaCulotte\Monetico\Resources;

use DansMaCulotte\Monetico\Exceptions\Exception;

class CartResource extends Ressource
{
    const ITEMS_KEY = 'shoppingCartItems';

    /** @var array */
    protected $keys = [
        'giftCardAmount',
        'giftCardCount',
        'giftCardCurrency',
        'preOrderDate',
        'preorderIndicator',
        'shoppingCartItems',
    ];

    /**
     * Client constructor.
     *
     * @param array $fields
     * @throws Exception
     */
    public function __construct(array $fields = [])
    {
        parent::__construct(array_merge(
            $fields,
            [
                self::ITEMS_KEY => [],
            ]
        ));
    }

    /**
     * @param CartItemResource $item
     */
    public function addItem(CartItemResource $item): void
    {
        array_push($this->parameters[self::ITEMS_KEY], $item->getParameters());
    }
}
