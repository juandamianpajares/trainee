<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Trainee;
class DemoSeeder extends Seeder{ public function run(){ Trainee::create(['name'=>'Demo User','email'=>'demo@bitnet.local','github'=>'https://github.com/demo','motivation'=>'Test','progress'=>100]); }}
