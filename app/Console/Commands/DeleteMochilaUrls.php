<?php declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteMochilaUrls extends Command
{
    protected $signature = 'delete:mochila-urls';
    protected $description = 'Delete Mochila gallery images with external URLs';

    public function handle(): int
    {
        $deleted = DB::table('product_images')
            ->whereIn('product_id', function($q) {
                $q->select('id')
                  ->from('products')
                  ->where('tenant_id', 17)
                  ->where('name', 'like', '%Mochila%');
            })
            ->where('image_filename', 'like', 'https://%')
            ->delete();

        $this->info("Deleted: {$deleted} images");
        return 0;
    }
}
