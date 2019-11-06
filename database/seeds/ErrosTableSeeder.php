<?php

use App\Models\Sys\ErrorModel;
use Illuminate\Database\Seeder;

class ErrosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ErrorModel $error)
    {
        $errs = [
            'code' => '100001',
            'msg' => '密码错误！',
        ];
        $error->insert($errs);
    }
}
