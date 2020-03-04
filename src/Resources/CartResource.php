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
        parent::__construct($fields);
        $this->setParameter(self::ITEMS_KEY, []);
    }

    /**
     * @param CartItemResource $item
     * @throws Exception
     */
    public function addItem(CartItemResource $item): void
    {
        $items = $this->getParameter(self::ITEMS_KEY);

        $items[] = $item->getParameters();

        $this->setParameter(self::ITEMS_KEY, $items);
    }
}
