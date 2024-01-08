<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TransactionController extends Controller
{
    /**
     * @inheritDoc
     */
    public function callAction(mixed $method, mixed $parameters): SymfonyResponse
    {
        return DB::connection()->transaction(fn (): SymfonyResponse => parent::callAction($method, $parameters));
    }
}
