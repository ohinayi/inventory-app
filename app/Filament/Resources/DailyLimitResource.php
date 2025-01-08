<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyLimitResource\Pages;
use App\Filament\Resources\DailyLimitResource\RelationManagers;
use App\Models\DailyLimit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique; // <- correct import

use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyLimitResource extends Resource
{
    protected static ?string $model = DailyLimit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->unique(modifyRuleUsing: function (Unique $rule, callable $get) {
                        return $rule
                        ->where('employee_id', $get('employee_id'));
                            // ->where('school_id', $get('school_id')) // get the current value in the 'school_id' field
                            // ->where('year', $get('year'))
                            // ->where('name', $get('name'));
                    })
                    ->validationMessages([
                        'unique' => 'A limit for the employee with the item has already being set',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('limit')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ManageDailyLimits::route('/'),
        ];
    }
}
