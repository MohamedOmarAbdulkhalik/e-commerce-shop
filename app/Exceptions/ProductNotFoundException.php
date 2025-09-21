<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductNotFoundException extends Exception
{
    protected $code = 404;
    protected $message = 'Product not found.';
    protected $productId;

    public function getProductId()
    {
        return $this->productId;
    }   
public function __construct($productId = null ,$message = null)
{
    parent::__construct($message ?? 'Product not found.');
    
    $this->productId = $productId;
}

public function render(Request $request)
{
    return response()->view('errors.product_not_found', [
        'message' => $this->getMessage(),
        'productId' => $this->productId,
        'errorCode' => 'PRODUCT_NOT_FOUND_404',
        'timestamp' => now()->toDateTimeString()
    ], 404);
}
}