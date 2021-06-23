<?php

use App\Paper;
use Illuminate\Database\Seeder;

class PapersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mig = Paper::create(['name' => 'Migrasiýa gullugy']);
        $mig->root = true;

        $takePassport = Paper::create(['name' => 'Passport almak']);
        $changePassport = Paper::create(['name' => 'Passport çalyşmak']);

        $takePassport->parent_id = $mig->id;
        $changePassport->parent_id = $mig->id;

        $requirements = [
            'Şahadatnama nusgasy',
            '3x4 surat',
            'Arza',
            'Kepilnama',
            'Rugsatnama',
            'Degişli ýerine blanka doldurmaly'
        ];

        for($i=0; $i<4; $i++) {
            $p = Paper::create(['name' => $requirements[$i]]);
            $p->parent_id = $takePassport->id;
            $p->leaf = true;
            $p->save();
        }

        for($i=0; $i<6; $i++) {
            $p = Paper::create(['name' => $requirements[$i]]);
            $p->parent_id = $changePassport->id;
            $p->leaf = true;
            $p->save();
        }

        $mig->save();
        $takePassport->save();
        $changePassport->save();

        // factory(Paper::class, 20)->create();
    }
}
