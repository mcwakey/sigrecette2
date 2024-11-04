<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Taxpayer;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

class Geolocation extends Controller
{
    public function zones(Request $request)
    {
        $taxpayers = Taxpayer::query()->with([
            'taxpayer_taxables',
            'town',
            'town.canton',
            'erea',
            'zone',
            'invoices'
        ]);

        if ($request->has('zone')) {
            $taxpayers->where('zone_id', $request->zone);
        }

        if ($request->has('status')) {
            $taxpayers->whereHas('invoices', function ($invoiceQuery) use ($request) {
                $invoiceQuery->where('pay_status', $request->invoice_status);
            });
        }

        if ($request->has('taxpayer')) {
            $taxpayers->where('name', 'like', '%' . $request->taxpayer . '%');
        }

        $taxpayers = $taxpayers->get();

        $zones = Zone::all();

        $commune = Commune::getFirstCommune();

        return View('pages.geolocation.taxpayers', ['taxpayers' => $taxpayers, 'zones' => $zones, 'commune' => $commune]);
    }

    public function users()
    {
        $users = User::all();
        return View('pages.geolocation.users', ['users' => $users]);
    }
}
