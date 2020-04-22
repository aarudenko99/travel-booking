<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDefaultStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:default-states';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix items default states';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*---------------------------------------------------------------------------------------------------------10101
         *
         * Find Tours
         *
         -------------------------------------------------------------------------------------------------------------*/
        $this->info('Resolving bravo_tours');
        foreach(DB::table('bravo_tours')->get() as $item)
        {
            if(intval($item->price) === 0)
            {
                DB::table('bravo_tours')->where('id', $item->id)->update(['price' => 111]);
            }

            if(!$item->default_state)
            {
                DB::table('bravo_tours')->where('id', $item->id)->update(['default_state' => true]);
            }
        }

        /*---------------------------------------------------------------------------------------------------------10101
         *
         * Find Cruises
         *
         -------------------------------------------------------------------------------------------------------------*/
        $this->info('Resolving bravo_cruises');
        foreach(DB::table('bravo_cruises')->get() as $item)
        {
            if(intval($item->price) === 0)
            {
                DB::table('bravo_cruises')->where('id', $item->id)->update(['price' => 111]);
            }

            if(!$item->default_state)
            {
                DB::table('bravo_cruises')->where('id', $item->id)->update(['default_state' => true]);
            }
        }

        /*---------------------------------------------------------------------------------------------------------10101
         *
         * Find Spaces
         *
         -------------------------------------------------------------------------------------------------------------*/
        $this->info('Resolving bravo spaces');
        for($i = 0; $i <= 1000; $i++)
        {
            $item = DB::table('bravo_spaces')->where('price', 0)->orWhereNull('price')->first();

            if($item)
            {
                if(intval($item->price) === 0)
                {
                    DB::table('bravo_spaces')->where('id', $item->id)->update(['price' => 111]);
                }

                if(!$item->default_state)
                {
                    DB::table('bravo_spaces')->where('id', $item->id)->update(['default_state' => true]);
                }
            }
            else {
                dd(' No more');
            }
        }
    }
}
