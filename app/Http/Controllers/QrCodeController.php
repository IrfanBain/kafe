<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function show($qrCode)
    {
        $table = Table::where('qr_code', $qrCode)->firstOrFail();

        $url = route('table.menu', $qrCode);

        return QrCode::format('svg')
            ->size(300)
            ->color(40, 40, 40)
            ->backgroundColor(255, 255, 255)
            ->margin(2)
            ->generate($url);
    }
}
