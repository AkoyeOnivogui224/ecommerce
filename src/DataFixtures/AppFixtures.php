<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new  PicsumPhotosProvider($faker));
        // Création utilisateur Admin.
        $admin = new User;

        $hash = $this->encoder->encodePassword($admin, "password");
        $admin->setEmail("admin@gmail.com")
            ->setPassword($hash)
            ->setFullNme("Admin")
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        //Création utilisateur 
        $users = [];
        for ($u = 0; $u < 5; $u++) {
            $user = new User;
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail("user$u@gmail.com")
                ->setFullNme($faker->name())
                ->setPassword($hash);

            $users[] = $user;

            $manager->persist($user);
        }

        $products = [];
        // Création catégorie
        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category
                ->setName($faker->department)
                ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);
            // Création produit
            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product;
                $product
                    ->setName($faker->ProductName)
                    ->setPrice(mt_rand(4000, 20000))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true));

                $products[] = $product;

                $manager->persist($product);
            }
        }
        // Création Commande.
        for ($p = 0; $p < mt_rand(20, 40); $p++) {
            $purchase = new Purchase;

            $purchase->setFullName($faker->name)
                ->setAddress($faker->streetAddress)
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000))
                ->setPurchasedAt($faker->DateTimeBetween('-6 Months'));

            $selectedProducts = $faker->randomElements($products, mt_rand(3, 5));
            foreach ($selectedProducts as $product) {

                $purchaseItem = new PurchaseItem;
                $purchaseItem->setProduct($product)
                    ->setQuantity(mt_rand(1, 3))
                    ->setProductName($product->getName())
                    ->setProductPrice($product->getPrice())
                    ->setTotal(
                        $purchaseItem->getProductPrice() * $purchaseItem->getQuantity()
                    )
                    ->setPurchase($purchase);

                $manager->persist($purchaseItem);
            }

            if ($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }

            $manager->persist($purchase);
        }

        $manager->flush();
    }
}
