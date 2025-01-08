<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Models\DailyLimit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class DailyLimitsRelationManager extends RelationManager
{
    protected static string $relationship = 'daily_limits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->unique(modifyRuleUsing: function (Unique $rule,RelationManager $livewire, callable $get, ?DailyLimit $record) {
                        // dump($record->id);
                        return $rule
                        ->where('employee_id',  $livewire->getOwnerRecord()->id)
                        // ->where('id' , $record->id)
                        ;
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item.name')
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
