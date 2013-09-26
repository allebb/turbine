<?php

class RulesTableSeeder extends Illuminate\Database\Seeder
{

    public function run()
    {
        $examplerule = new Rule(array(
            'hostheader' => 'bassrocket.com www.bassrocket.com',
            'enabled' => true,
            'nlb' => false,
        ));
       $examplerule->save();
       $examplerule = new Rule(array(
            'hostheader' => 'blog.bassrocket.com',
            'enabled' => false,
            'nlb' => false,
        ));
       $examplerule->save();
       $examplerule = new Rule(array(
            'hostheader' => 'mail.bassrocket.com',
            'enabled' => true,
            'nlb' => false,
        ));
       $examplerule->save();
       $examplerule = new Rule(array(
            'hostheader' => 'example.another.net',
            'enabled' => true,
            'nlb' => true,
        ));
       $examplerule->save();
    }

}
?>
