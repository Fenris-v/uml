<?php

interface HasMoney
{
    public function getMoney();
}

class Shop implements HasMoney
{
    private array $products;
    private int $money = 0;
    private bool $sorted = false;

    public function getMoney()
    {
        return $this->money;
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    /**
     * Здесь я намеренно сортирую по убыванию
     */
    public function getProductsSortedByPrice(): array
    {
        if (!$this->sorted) {
            usort(
                $this->products,
                function ($a, $b) {
                    return $b->getPrice() <=> $a->getPrice();
                }
            );

            $this->sorted = true;
        }

        return $this->products;
    }

    public function sellTheMostExpensiveProduct(Client $client)
    {
        foreach ($this->getProductsSortedByPrice() as $product) {
            if ($client->canBuyProduct($product)) {
                $productToBuy = $product;
                break;
            }
        }

        if (isset($productToBuy)) {
            $client->buyProduct($productToBuy);
            $this->sellProduct($productToBuy);
        }
    }

    public function sellProduct(Product $product)
    {
        $this->money += $product->getPrice();
    }
}

class Product
{
    protected string $name;
    protected int $price;

    public function __construct(string $name, int $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

class Client implements HasMoney
{
    protected int $money;
    private ?Product $product = null;
    private string $name;
    private ?array $canBuyProducts = null;

    public function __construct(int $money, string $name)
    {
        $this->money = $money;
        $this->name = $name;
    }

    public function getMoney(): int
    {
        return $this->money;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function canBuyProduct(Product $product): bool
    {
        return $product->getPrice() <= $this->getMoney();
    }

    public function buyProduct(Product $product)
    {
        $this->money -= $product->getPrice();
        $this->product = $product;
    }

    public function getBoughtProduct()
    {
        return $this->product;
    }
}

$shop = new Shop();

$products[] = new Product('продукт 1', 10);
$products[] = new Product('продукт 2', 99999);
$products[] = new Product('продукт 3', 250);

foreach ($products as $product) {
    $shop->addProduct($product);
}

$clients[] = new Client(100, 'клиент 1');
$clients[] = new Client(1, 'клиент 2');
$clients[] = new Client(10, 'клиент 3');
$clients[] = new Client(100000, 'клиент 4');
$clients[] = new Client(300, 'клиент 5');

echo '<pre>';
foreach ($clients as $client) {
    echo $client->getName() . ' встал в очередь, У него было денег: ' . $client->getMoney() . PHP_EOL;

    $canBuyProducts = [];
    $shop->sellTheMostExpensiveProduct($client);

    if ($client->getBoughtProduct()) {
        echo $client->getName() . ' купил товар ' . $client->getBoughtProduct()->getName()
            . ' за ' . $client->getBoughtProduct()->getPrice() . '. У него осталось денег: ' .
            $client->getMoney() . PHP_EOL . PHP_EOL;
    } else {
        echo $client->getName() . ' ничего не купил. У него осталось денег: ' .
            $client->getMoney() . PHP_EOL . PHP_EOL;
    }
}

echo 'Товаров куплено на сумму: ' . $shop->getMoney();

echo '</pre>';
