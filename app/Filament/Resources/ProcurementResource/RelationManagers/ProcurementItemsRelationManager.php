<?php

namespace App\Filament\Resources\ProcurementResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcurementItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'procurement_items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('is_approved'),
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->required(),
                Forms\Components\Textarea::make('receive_notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('quantity_received')
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity_requested')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item.name')
            ->columns([
                Tables\Columns\TextColumn::make('item.name'),
                Tables\Columns\TextColumn::make('quantity_received'),
                Tables\Columns\TextColumn::make('quantity_received'),
                Tables\Columns\TextColumn::make('quantity_requested'),
                Tables\Columns\IconColumn::make('is_approved')->boolean()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
