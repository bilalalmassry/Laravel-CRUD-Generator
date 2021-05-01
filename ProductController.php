<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(Product::class,
            StoreProductRequest::class,
            UpdateProductRequest::class);
    }

}
