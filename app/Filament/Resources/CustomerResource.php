<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                ->label('Nama Perusahan')
                ->required()
                ->columnSpanFull()
                ->maxLength(255),

                Forms\Components\TextArea::make('company_address')
                ->label('Alamat Perusahaan')
                ->columnSpanFull()
                ->required(),

                Forms\Components\TextInput::make('company_phone')
                ->label('Telepon Perusahan')
                ->tel()
                ->required()
                ->columnSpanFull()
                ->maxLength(255),

                Forms\Components\TextInput::make('contact_name')
                ->label('Nama PIC'),             

                Forms\Components\TextInput::make('contact_phone')
                ->label('Telepon PIC'),                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                ->label('Nama Perusahaan')                
                ->searchable(),

                Tables\Columns\TextColumn::make('company_address')
                ->label('Alamat Perusahaan'),
                
                Tables\Columns\TextColumn::make('company_phone')
                ->label('Telepon Perusahaan'),

                Tables\Columns\TextColumn::make('contact_name')
                ->label('Nama PIC'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
