<?php

namespace App\Http\Controllers\Card;
use App\Http\Controllers\Controller;
use App\Models\Barcode;
use App\Models\Card;
use App\Models\Price;
use App\Models\Product;
use App\Models\Reversal;
use Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CardController extends Controller
{
    public function create(Request $request, $id){
        $product = Product::with('prices')->where('id', $id)->firstOrFail();
        
        return view('card.create', compact('product'));
    }

    public function store(Request $request){
        $request->validate([
            'price_id' => 'required|exists:prices,id',
        ]);

        $price = Price::findOrFail($request->price_id);
        $product = $price->product;

        $card = Card::create([
            'valid_from' => now(),
            'valid_till' => now()->addDays($product->duration_days),
            'created_by_id' => Auth::id(),
            'price_id' => $price->id,
            'is_generated' => true
        ]);

        $barcode = $this->generateUniqueBarcode();

        Barcode::create([
            'barcode' => $barcode,
            'card_id' => $card->id,
            'is_generated' => true
        ]);

        return redirect("/card/print/view/{$card->id}")->with('success', 'Card created!');
    }

    public function createExternal(Request $request, $id){
        $product = Product::with('prices')->where('id', $id)->firstOrFail();
        
        return view('card.create-external', compact('product'));
    }

    public function storeExternal(Request $request){
        $request->validate([
            'price_id' => 'required|exists:prices,id',
            'barcode' => 'required|digits:13|unique:barcodes,barcode'
        ]);

        $price = Price::findOrFail($request->price_id);
        $product = $price->product;

        $card = Card::create([
            'valid_from' => now(),
            'valid_till' => now()->addDays($product->duration_days),
            'created_by_id' => Auth::id(),
            'price_id' => $price->id,
            'is_generated' => true
        ]);

        Barcode::create([
            'barcode' => $request->barcode,
            'card_id' => $card->id,
            'is_generated' => true
        ]);

        return redirect("/card/print/view/{$card->id}")->with('success', 'Card created!');
    }

    public function print(Request $request, $id){
        $card = Card::findOrFail($id);

        return view('card.print', compact('card'));
    }

    public function printPdf($id){ 
        $card = Card::with(['price.product', 'barcode'])->findOrFail($id); 

        $pdf = Pdf::loadView('card.print-pdf', compact('card')); 

        return $pdf->stream('card-'.$card->id.'.pdf'); 
    }

    public function info(Request $request){
        return view('card.info');
    }

    public function indexInfo(Request $request, $barcodeValue){
        $card = Card::whereHas('barcode', function($query) use ($barcodeValue) {
            $query->where('barcode', $barcodeValue);
        })
        ->with(['barcode', 'price.product'])
        ->firstOrFail();

        $isRevers = Reversal::where('card_id', $card->id)->exists();

        return response()->json([
            'valid_from'  => $card->valid_from,
            'valid_till'  => $card->valid_till,
            'count_usage' => $card->count_usage,
            'max_usage'   => max(0, $card->price?->product?->count_usage - $card->count_usage),
            'id' => $card->id,
            'isRevers' => $isRevers
        ]);
    }

    protected function generateUniqueBarcode(): string{
        do {
            $barcode = mt_rand(100000000000, 999999999999);
        } while (Barcode::where('barcode', $barcode)->exists());

        return $barcode;
    }
}