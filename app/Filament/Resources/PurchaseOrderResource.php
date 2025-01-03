<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Filament\Resources\PurchaseOrderResource\RelationManagers;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Filament\Support\RawJs;
use Filament\Tables\Columns\TextColumn;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static ?string $navigationGroup = 'Purcashing';

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
                   Forms\Components\TextInput::make('NomorPO')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4
                    ])
                    ->label(__('Nomor PO'))                    
                    ->default(fn() => self::generateCustomNumber())
                    ->disabled()
                    ->columnSpan(['2xl' => 4]),                  

                    Forms\Components\DatePicker::make('po_date')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ])
                    ->label(__('Tanggal PO'))
                    ->format('Y-m-d')
                    ->displayFormat('d/m/Y')                    
                    ->closeOnDateSelection()
                    ->required(), 

                    Forms\Components\Hidden::make('po_number')
                    ->default(fn() => self::generateCustomNumber()),

                    Forms\Components\Select::make('supplier_id')
                        ->label('Nama Supplier')
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]) 
                        ->relationship('supplier','name')                        
                        ->searchable()
                        ->reactive()                        
                        ->required(),
                ]),
            
                TableRepeater::make('po_number_purchase_order_detail')
                ->label('Detail Pesanan')
                ->relationship('po_number_purchase_order_detail')
                ->schema([                    
                    Forms\Components\TextInput::make('product_name')
                    ->label('Nama Barang')
                    ->required(),
                    Forms\Components\TextInput::make('quantity')
                    ->label('QTY')
                    ->numeric()
                    ->step(1)
                    ->default(0)
                    ->reactive()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function(Callable $get, Set $set){
                        self::updateAmountValue($get, $set);
                    })  
                    ->required(),
                    Forms\Components\Select::make('unit')
                        ->label('Satuan')
                        ->options(\App\Models\Unit::all()->pluck('unit_symbols','unit_symbols'))
                        ->required(),
                    Forms\Components\TextInput::make('unit_price')
                    ->label('Harga Satuan')
                    ->currencyMask()
                    ->default(0)
                    ->reactive()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function(Callable $get, Set $set){
                        self::updateAmountValue($get, $set);
                    })                                  
                    ->required(),
                    
                    Forms\Components\TextInput::make('amount_display')
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
                Forms\Components\TextInput::make('total_amount_display')
                ->columnSpan([
                    'sm' => 2,
                    'xl' => 3,
                    '2xl' => 4,
                ])    
                ->label('Total')              
                ->currencyMask()
                ->disabled(),

                Forms\Components\Hidden::make('total_amount'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('po_number')->label('Nomor PO'),
                TextColumn::make('po_date')->label('Tanggal PO'),
                TextColumn::make('supplier.name')->label('Nama Supplier'),        
                TextColumn::make('total_amount')->label('Total'),
                
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
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
        ];
    }

    public static function generateCustomNumber()
    {
        // Get the last record
        $lastRecord = PurchaseOrder::query()->latest('po_number')->first();

        // Extract the last number (if your number has a specific format)
        if ($lastRecord && $lastRecord->po_number) {
            $lastNumber = $lastRecord->po_number + 1;
            return $lastNumber;
        }

        // Start with an initial value if no record exists
        return '1';
    }

    public static function updateAmountValue(Get $get, Set $set)
    {
       $formProduct = $get("../../");       
       $allPODetails = $formProduct['po_number_purchase_order_detail'] ?? [];      
       $total = 0; 
        foreach ($allPODetails as $PODetail)
        {
            $qty = $PODetail['quantity'] ?? 0;
            $price = $PODetail['unit_price'] ?? 0;
            $amount = $qty * $price;
            $total += $amount;                      
        }

        $qty = $get('quantity');
        $price = $get('unit_price');
        $amount = $qty * $price;         
        $set('amount_display', $amount); 
        $set('amount', $amount);
        $set("../../total_amount_display", $total); 
        $set("../../total_amount", $total);
    }
}
