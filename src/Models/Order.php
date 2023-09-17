<?php

namespace Eleven59\BackpackShop\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use CrudTrait, HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'orders';
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];
    protected $casts = [
        'payment_info' => 'object',
        'order_summary' => 'array',
    ];

    public $statuses = [
        'paid' => 'Paid',
        'new' => 'New',
        'cancelled' => 'Cancelled',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->statuses = [
            'new' => __('backpack-shop::order.statuses.new'),
            'cancelled' => __('backpack-shop::order.statuses.cancelled'),
            'paid' => __('backpack-shop::order.statuses.paid'),
        ];
    }

    /**
     * Find the order using the payment provider's payment Id
     * Useful especially when payment providers don't allow sending addional meta data to the return or webhook urls
     * @param $payment_id
     * @return bool|Order
     */
    public static function getByPaymentId($payment_id) :bool|Order
    {
        return Order::where('payment_info', 'like', "%\"id\":\"{$payment_id}\"%")->first();
    }

    /**
     * Find the next unoccupied invoice number for the current bookyear (for generating the next invoice Id)
     * @return int
     */
    public static function getNextInvoiceNo()
    {
        $year = (int) date('Y');
        $invoiced = Order::orderBy('invoice_no', 'desc')->where('invoice_year', '=', $year)->where('invoice_no', '>', 0)->get();
        if (!empty ($invoiced)) {
            foreach ($invoiced as $order) {
                return ($order->invoice_no + 1);
            }
        }
        return 1;
    }

    /**
     * Generates the invoice
     * @param $save if false, the PDF is streamed directly to the browser; future implementation will use this to download it from the admin panel
     * @param $html if true, the view is rendered as Html; only used in the test urls for now (see routes)
     * @return false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function makePdfInvoice($save = true, $html = false)
    {
        if($html) {
            return view(config('eleven59.backpack-shop.invoice-pdf-view', 'backpack-shop::pdf.invoice'), [
                'order' => $this
            ]);
        }

        $pdf = Pdf::getFacadeRoot();
        if(env('APP_DEBUG')) {
            $dompdf = $pdf->getDomPDF();
            $dompdf->setHttpContext(stream_context_create([
                'ssl' => [
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed'=> TRUE
                ],
            ]));
        }

        $pdf->loadView(config('eleven59.backpack-shop.invoice-pdf-view', 'backpack-shop::pdf.invoice'), [
            'order' => $this
        ], [], "UTF-8")->setOptions([
            'isRemoteEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultMediaType' => 'all',
        ]);

        if(!$save) {
            return $pdf->stream("{$this->fancy_invoice_no}.pdf");
        }

        if(!is_dir(storage_path('app/tmp'))) {
            mkdir(storage_path('app/tmp'), 0770, true);
        }
        if($pdf->save(storage_path("app/tmp/{$this->fancy_invoice_no}.pdf"))) {
            return storage_path("app/tmp/{$this->fancy_invoice_no}.pdf");
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFancyInvoiceNoAttribute() :string
    {
        if (!empty($this->invoice_year)) {
            $pad_length = config('eleven59.backpack-shop.invoice_no_pad_len', 4);
            return Str::swap([
                ":year" => Str::of($this->invoice_year),
                ":number" => Str::padLeft($this->invoice_no, $pad_length, "0"),
            ], config('eleven59.backpack-shop.invoice_no_format', "W:year:number"));
        }
        return '';
    }

    public function getFullAddressAttribute() :string
    {
        return "<strong>{$this->name}</strong><br>{$this->address}<br>{$this->zipcode} {$this->city}<br>{$this->country}";
    }

    public function getOrderItemsAttribute() :string
    {
        $return = [];
        if(!empty($this->order_summary['products'])) {
            foreach($this->order_summary['products'] as $product) {
                $return[] = "{$product['quantity']}x {$product['description']}";
            }
        }
        return implode('<br>', $return);
    }

    public function getFancyStatusAttribute() :string
    {
        return empty ($this->statuses[$this->status]) ? '' : $this->statuses[$this->status];
    }

    public function getOrderTotalAttribute() :float
    {
        if(config('eleven59.backpack-shop.prices_include_vat')) {
            return $this->order_summary['totals']['total_incl_vat'];
        }
        return $this->order_summary['totals']['total_excl_vat'];
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
