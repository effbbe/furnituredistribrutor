<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Filament\Forms\Set;
use Filament\Forms\Get;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Supplier')
                ->columns([
                    'sm' => 3,
                    'xl' => 6,
                    '2xl' => 8,
                ])
                ->schema([
                    Forms\Components\TextInput::make('no_invoice')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4
                    ])
                    ->label(__('Nomor Invoice'))                    
                    ->default(fn() => self::generateInvoiceNumber())
                    ->disabled()
                    ->columnSpan(['2xl' => 4]),

                    Forms\Components\Hidden::make('invoice_number')
                    ->default(fn() => self::generateInvoiceNumber()),

                    Forms\Components\DatePicker::make('invoice_date')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ])
                    ->label(__('Tanggal Invoice'))
                    ->format('Y-m-d')
                    ->displayFormat('d/m/Y')                    
                    ->closeOnDateSelection()
                    ->required(),

                    Forms\Components\Select::make('customer_id')
                    ->label('Nama Customer')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]) 
                    ->relationship('customer','company_name')                        
                    ->searchable()
                    ->reactive()                        
                    ->required(),
                ]),

                TableRepeater::make('invoice_detail')
                ->label(__('Detail Pesanan'))
                ->relationship('invoice_detail')
                ->schema([
                    Forms\Components\Select::make('product_name')
                    ->label('Nama Produk')
                    ->options(\App\Models\Product::all()->pluck('product_name','product_name'))
                    ->reactive()
                    ->live(debounce:500)
                    ->searchable()
                    ->afterStateUpdated(function ($state, Callable $set){                       
                        $product = \App\Models\Product::where('product_name', $state)->first();
                        $set('unit_price_view', $product->unit_price);
                        $set('unit_price', $product->unit_price);
                    })
                    ->required(),

                    Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(0)
                    ->step(1)
                    ->label('QTY')
                    ->reactive()
                    ->live(debounce:500)
                    ->afterStateUpdated(function(Callable $get, Set $set){
                        self::calculate($get, $set);
                    })
                    ->required(),

                    Forms\Components\TextInput::make('unit_price_view')
                    ->label('Harga Satuan')
                    ->currencyMask()
                    ->disabled()
                    ->required(),
                    Forms\Components\Hidden::make('unit_price'),

                    Forms\Components\TextInput::make('amount_view')
                    ->label('Jumlah')
                    ->currencyMask()
                    ->disabled(),
                    Forms\Components\Hidden::make('amount')
                                       
                ])
                ->minItems(1)
                ->addActionLabel('Tambah Barang')
                ->columnSpan('full'),

                Forms\Components\Section::make('Total')
                ->columns([
                    'sm' => 3,
                    'xl' => 6,
                    '2xl' => 8,
                ])
                ->schema([
                    Forms\Components\TextInput::make('subtotal_view')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 3,
                    ])    
                    ->label('subtotal')              
                    ->currencyMask()
                    ->disabled(),
                    Forms\Components\Hidden::make('subtotal'),

                    Forms\Components\TextInput::make('tax')
                    ->label(__('PPN'))
                    ->reactive()
                    ->live(debounce:500)
                    ->afterStateUpdated(function($state, Get $get, Set $set){
                        $ppn = ($state/100) * $get('subtotal');
                        $total = $ppn + $get('subtotal');
                        $set('total_view', $total);
                        $set('total', $total);
                    })
                    ->required(),

                    Forms\Components\TextInput::make('total_view')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ])    
                    ->label(__('Total'))              
                    ->currencyMask()
                    ->disabled(),
                    Forms\Components\Hidden::make('total'),

                    
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Nomor Invoice')->searchable(),
                Tables\Columns\TextColumn::make('invoice_date')->label('Tanggal Invoice'),
                Tables\Columns\TextColumn::make('customer.company_name')->label('Nama Customer')->searchable(),        
                Tables\Columns\TextColumn::make('subtotal')->label('subtotal'),
                Tables\Columns\TextColumn::make('tax')->label('PPN'),
                Tables\Columns\TextColumn::make('total')->label('total'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    protected static function generateInvoiceNumber()
    {
        // Get the last record
        $lastRecord = Invoice::query()->latest('invoice_number')->first();

        // Extract the last number (if your number has a specific format)
        if ($lastRecord && $lastRecord->invoice_number) {
            $lastNumber = $lastRecord->invoice_number + 1;
            return $lastNumber;
        }

        // Start with an initial value if no record exists
        return '1';
    }

    protected static function calculate(Get $get, Set $set)
    {
       $formProduct = $get("../../");
              
       $allProduct = $formProduct['invoice_detail'] ?? [];      
       $subtotal = 0; 
        foreach ($allProduct as $product)
        {
            $qty = $product['quantity'] ?? 0;
            $price = $product['unit_price'] ?? 0;
            $amount = $qty * $price;
            $subtotal += $amount;                      
        }

        $qty = $get('quantity');
        $price = $get('unit_price');
        $amount = $qty * $price;         
        $set('amount_view', $amount); 
        $set('amount', $amount);
        $set("../../subtotal_view", $subtotal); 
        $set("../../subtotal", $subtotal);
    }
}
