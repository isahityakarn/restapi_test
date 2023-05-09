<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price'
    ];
    public function productFile()
    {
        return $this->hasMany(ProductFile::class,  'product_id', 'id');
    }


    public function getSaveData()
    {

        $this->productFile;
        if (count($this->productFile)) {

            foreach ($this->productFile as $key => $detail) {
                $this->productFile[$key]->file_name = $detail->file_name;
            }
        }
        return $this;
    }
}
