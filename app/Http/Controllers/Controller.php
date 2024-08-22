<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function removeItem(Closure $closure, $redirect_route)
    {
        DB::beginTransaction();

        try {

            $closure();
            if (is_array($redirect_route)) {
                $redirect = redirect()->route($redirect_route[0], $redirect_route[1]);
            } else {
                $redirect = redirect()->route($redirect_route);
            }
            $redirect = $redirect->withSuccess(__('Successfully deleted'));
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect = $redirect->withFail(__('You cannot delete the record as it is associated with one or multiple other records'));
            DB::rollback();
        } catch (\Exception $e) {            
            $redirect = $redirect->withFail(__('Could not perform the requested action'));
            DB::rollback();
        }

        return $redirect;
    }

    protected function getQueryParameterPreviousUrl()
    {
        $parsed_url = parse_url(url()->previous());
        if (isset($parsed_url['query']) && $parsed_url['query']) {
            return $parsed_url['query'];
        };
    }
}
