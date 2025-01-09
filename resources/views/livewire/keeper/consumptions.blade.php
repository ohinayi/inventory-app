<?php

use App\Models\Consumption;
use App\Rules\AvailableQuantityRule;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;


use Livewire\Volt\Component;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Livewire\WithPagination;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use App\Rules\ExceedLimitRule;
use Filament\Forms\Get;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Contracts\Validation\ValidationRule;

// use function Livewire\Volt\{state, layout, usesPagination, mount, with, action, computed, on};

new  class extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        // $userTypes = [];

        // foreach (UserType::cases() as $value) {
        //     // dump($value);
        //     $userTypes[$value->value] = $value->name;
        // }

        return $table
            ->query(Consumption::query())
            ->defaultGroup('user.name')
            ->groups(['user.name', 'item.name'])
             ->headerActions([
            CreateAction::make()
                ->form([
                    Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                        ->required(),
                    Forms\Components\Select::make('item_id')
                        ->relationship('item', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('quantity')
                        ->required()
                        ->rules(
                            [
                            fn (Get $get): ValidationRule =>
                             new ExceedLimitRule(
                                $get('user_id'),
                                $get('item_id'),
                                date: now()->toDateString()
                            ),
                            function (Get $get): ValidationRule {

                                return new AvailableQuantityRule(
                                    $get('item_id'),
                                   // date: now()->toDateString()
                               );
                            }
                        ]
                        )
                        ->numeric(),
                    Forms\Components\DateTimePicker::make('consumed_at')
                    // ->required()
                    ->disabled()
                        ->dehydrated()
                        ->dehydrateStateUsing(fn()=>Carbon::now())
                        ->default(Carbon::now())

                    // ...
                ]),
        ])
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
                    ->summarize(Sum::make())
                    ,
                    Tables\Columns\TextColumn::make('daily_limit.limit')
                    ->numeric()
                    ->state(function (Consumption $record): int {
                        return $record->daily_limit->limit?? $record->item->default_limit;
                    })
                    ,
                Tables\Columns\TextColumn::make('consumed_at')
                    ->dateTime()
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
                Filter::make('consumed_today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('consumed_at', Carbon::today()))
                    ->default()
                    ->label('Consumed Today')
                    ,
                    Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('consumed_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('consumed_at', '<=', $date),
                            );
                    })
                    ->label('Date Range'),
                    // TernaryFilter::make('exceeded_limit')
                    // ->label('Exceeded Limit')->queries(true: function(Builder $query){
                    //     return $query->whereHas('daily_limit', function(Builder $query){
                    //         // Debugbar::info($query->toRawSql());

                    //         return $query->whereColumn('consumptions.quantity', '>' ,'daily_limits.limit');
                    //     })->orWhereDoesntHave('daily_limit', function(Builder $query){
                    //         // Debugbar::info($query->toRawSql());
                    //     });
                    // }, false: function(Builder $query){
                    //     return $query;
                    // })
                    // ,
                    // TernaryFilter::make('exceeded_limit')
                    // ->label('Exceeded Limit')
                    // ->queries(
                    //     true: function (Builder $query) {
                    //         return $query->where(function ($query) {
                    //             $query->whereHas('daily_limit', function ($query) {
                    //                 $query->whereColumn('consumptions.quantity', '>', 'daily_limits.limit');
                    //             })->orWhereDoesntHave('daily_limit')
                    //               ->whereColumn('consumptions.quantity', '>', 'items.default_limit');
                    //         })->join('items', 'consumptions.item_id', '=', 'items.id');
                    //     },
                    //     false: function (Builder $query) {
                    //         return $query->where(function ($query) {
                    //             $query->whereHas('daily_limit', function ($query) {
                    //                 $query->whereColumn('consumptions.quantity', '<=', 'daily_limits.limit');
                    //             })->orWhereDoesntHave('daily_limit')
                    //               ->whereColumn('consumptions.quantity', '<=', 'items.default_limit');
                    //         })->join('items', 'consumptions.item_id', '=', 'items.id');
                    //     }
                    // ),

                SelectFilter::make('user')
                    ->relationship('user', 'name'),

                SelectFilter::make('item')
                    ->relationship('item', 'name'),
                // SelectFilter::make('user_type')->options($userTypes)->multiple()
                // TrashedFilter::make()
            ])
            ->actions([
                // Action::make('view')->action(function(Model $record){
                //     // $this->route
                //     return $this->redirect(route('moderator.users-user', $record), navigate: true);
                // })
            ])
            // ->recordUrl(
            //     fn (Model $record): string => route('moderator.users-user', $record),
            // )
            ->bulkActions([
                // ...
            ]);
    }

    // #[Url]
    // public $search = '';
    // public ?Consumption $model=null;
    // public $creating = false;
    // public $sortBy = ['column' => 'id', 'direction' => 'asc'];


    // #[Computed]
    // public function hasModel()
    // {
    //     return $this->model != null;
    // }

    // public function resetQueries()
    // {
    //     $this->search = null;
    //     $this->resetPage();
    // }

    // public function setItem(Consumption $consumption){
    //     $this->model = $consumption;
    //     $this->dispatch('consumption-changed', consumption: $consumption);
    //     $this->dispatch('open-modal', 'model-edit');

    // }

    // protected function getQuery(): Builder
    // {
    //     return Consumption::query()
    //         ->where('name', 'like', '%' . $this->search . '%')
    //         ->orderBy(...array_values($this->sortBy));
    // }

    // #[On('consumption-created')]
    // public function handleItemCreated($consumption)
    // {
    //     $this->resetQueries();
    //     $this->closeCreateModal($consumption);
    // }

    // #[On('consumption-updated')]
    // public function handleItemUpdated($consumption)
    // {
    //     $this->resetQueries();
    //     if ($consumption !== null) {
    //         $this->search = $consumption['name'];
    //     }
    // }

    // #[On('consumption-create-cancelled')]
    // public function handleItemCreateCancelled()
    // {
    //     $this->closeCreateModal();
    // }

    // public function openCreateModal()
    // {
    //     $this->creating = true;
    //     $this->dispatch('open-modal', 'model-create');
    // }

    // public function closeCreateModal($model = null)
    // {
    //     $this->dispatch('close-modal', 'model-create');
    //     if ($model !== null) {
    //         $this->search = $model['name'];
    //     }
    // }

    // public function with(): array
    // {
    //     return [
    //         'consumptions' => $this->getQuery()->paginate(),
    //     ];
    // }

};






?>
<div class="">
{{ $this->table }}
</div>
