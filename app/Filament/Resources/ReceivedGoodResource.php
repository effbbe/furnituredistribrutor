<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceivedGoodResource\Pages;
use App\Filament\Resources\ReceivedGoodResource\RelationManagers;
use App\Models\ReceivedGood;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Set;
use Illuminate\Support\Str;


class ReceivedGoodResource extends Resource
{
    protected static ?string $model = ReceivedGood::class;
    
    protected static ?string $navigationGroup = 'Warehouse';

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
                    Forms\Components\DatePicker::make('received_date')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ])
                    ->label(__('Tanggal Terima'))
                    ->format('Y-m-d')
                    ->displayFormat('d/m/Y')                    
                    ->closeOnDateSelection()
                    ->required(), 

                    Forms\Components\TextInput::make('po_number')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4
                    ])
                    ->label(__('Nomor PO'))
                    ->columnSpan(['2xl' => 4]),

                    Forms\Components\TextInput::make('company_name')
                    ->label('Nama Supplier')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ])
                    ->required(),                      
                ]),

                TableRepeater::make('items')
                ->label('Detail Barang')              
                ->schema([                    
                    Forms\Components\Select::make('category')
                    ->label(__('Category'))
                    ->options(\App\Models\Category::all()->pluck('category'))
                    ->required(),

                    Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required(),

                    Forms\Components\TextInput::make('product_name')
                    ->label('Nama Barang')
                    ->reactive()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required(),

                    Forms\Components\Hidden::make('slug'),

                    Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->default(0)
                    ->step(1)
                    ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('received_date')
                    ->label('Tanggal Diterima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('po_number')
                    ->label('Nomor Po')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Nama Supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('items.0.sku')
                    ->label('SKU'),                    
                Tables\Columns\TextColumn::make('items.0.product_name')
                    ->label('Nama Barang'),
                Tables\Columns\TextColumn::make('items.0.quantity')
                    ->label('QTY'), 
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
            'index' => Pages\ListReceivedGoods::route('/'),
            'create' => Pages\CreateReceivedGood::route('/create'),
            'edit' => Pages\EditReceivedGood::route('/{record}/edit'),
        ];
    }
}
