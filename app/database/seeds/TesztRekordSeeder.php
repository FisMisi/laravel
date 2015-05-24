<?php


class TesztRekordSeeder extends Seeder
{
 
    public function run()
    {
        $faker = Faker\Factory::create('hu_HU');
        
        
        //Felhasznalok tábla feltöltése teszt adatokkkal
        for ($i = 0; $i < 6; $i++) {
            $user = User::create(array(
                'first_name'     => $faker->firstName,
                'last_name'      => $faker->lastName,
                'username'       => $faker->username,
                'password'       => Hash::make('teszt'),
                'remember_token' => str_random(50)
          ));
        }
        
        //Vasarlok tábla feltöltése teszt adatokkkal
        for ($i = 0; $i < 6; $i++) {
            $customer = Customer::create(array(
                'first_name'  => $faker->firstName,
                'last_name'   => $faker->lastName,
                'email'       => $faker->email,
                'postal_code' => $faker->postcode,
                'city'        => $faker->city,
                'address'     => $faker->streetAddress,
                'phone'       => $faker->phoneNumber
          ));
        }
        
        //Étlap tábla feltöltése teszt adatokkkal
        for ($i = 0; $i < 10; $i++) {
            $menu     = Menuitem::create(array(
                'name'   => $faker->word . " pizza",
                'price'  => $faker->numberBetween($min = 800, $max = 2300),
          ));
        }
        
        for ($i = 0; $i < 6; $i++) {
            $menuItal    = Menuitem::create(array(
                'name'   => $faker->word . " ital",
                'price'  => $faker->numberBetween($min = 150, $max = 600),
                'type'   => "ital"
          ));
        }
        
        //Futárok tábla feltöltése teszt adatokkkal
        for ($i = 0; $i < 6; $i++) {
            $pDelivery = Pizzadelivery::create(array(
                'first_name'=> $faker->firstName,
                'last_name' => $faker->lastName,
                'nickname'  => $faker->userName,
                'phone'     => $faker->phoneNumber
          ));
        }
        
        //Rendelesek tábla feltöltése teszt adatokkkal
        for ($i = 0; $i < 6; $i++) {
            $order = Order::create(array(
                'customer_id'          => $faker->numberBetween($min = 1, $max = 6),
                'pizzadelivery_id'    => $faker->numberBetween($min = 1, $max = 6),
                'statusz'              => $faker->numberBetween($min = 1, $max = 5)
          ));
        }
        
        //Rendelei tetelek tábla feltöltése teszt adatokkkal
        for ($i = 0; $i < 6; $i++) {
            $item = MenuitemOrder::create(array(
                'menuitem_id'    => $faker->numberBetween($min = 1, $max = 16),
                'order_id'   => $faker->numberBetween($min = 1, $max = 6)               
          ));
        }
    }
    
}
