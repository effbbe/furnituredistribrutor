<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Set;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Unit;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Warehouse';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sku')
                ->label('Kode SKU')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('product_name')
                ->label('Nama Produk')
                ->required()
                ->maxLength(255)
                ->reactive()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\Hidden::make('slug'),

                Forms\Components\Textarea::make('description')
                ->label('Deskripsi Produk')
                ->columnSpanFull()
                ->required(),

                Select::make('unit')
                ->label('Satuan')
                ->options(Unit::all()->pluck('unit_symbols', 'unit_symbols'))
                ->searchable(),

                Forms\Components\TextInput::make('unit_price')
                ->label('Harga Satuan')
                ->currencyMask()               
                ->maxLength(50)
                ->required(),

                Select::make('category')
                ->label('Kategori Produk')
                ->options(Category::all()->pluck('category', 'category'))
                ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                ->label('SKU')                
                ->searchable(),                
                Tables\Columns\TextColumn::make('product_name')
                ->label('Nama Produk')                               
                ->searchable(),                
                Tables\Columns\TextColumn::make('description')
                ->label('Deskripsi Produk'),               
                Tables\Columns\TextColumn::make('unit')
                ->label('Unit'),             
                Tables\Columns\TextColumn::make('unit_price')
                ->label('Harga Satuan'),                               
                Tables\Columns\TextColumn::make('current_stock')
                ->label('Total Stok'),
                Tables\Columns\TextColumn::make('category')
                ->label('Kategori')
                ->searchable(),                
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
