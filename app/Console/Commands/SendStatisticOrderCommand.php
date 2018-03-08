<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use Mail;

class SendStatisticOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendStatisticOrder:sendmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Statistic Order';

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
        $sellingProducts = DB::table('products')
            ->select(DB::raw('products.name, count(products.name) as count'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->whereMonth('order_items.created_at', Carbon::now()->month)
            ->groupBy('products.name')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        $mailTo = ENV('MAIL_USERNAME');
        Mail::send('emails.statistic', [
            'sellingProducts' => $sellingProducts,
        ], function ($message) use ($mailTo) {
            $message->to($mailTo);
        });
    }
}
