<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;
use App\Models\Employees;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use App\Exports\EmployeesExport;
use App\Models\User;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Collection;

class EmployeesResource extends Resource
{
    protected static ?string $model = Employees::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Employee Management';

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship(name: 'user', titleAttribute: 'name')
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->type('text'),
                TextInput::make('email')
                    ->type('email')
                    ->unique()
                    ->required(),
                TextInput::make('phone')
                    ->type('number')
                    ->unique()
                    ->required(),
                TextInput::make('dob')
                    ->type('date'),
                TextInput::make('position')
                    ->type('text'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('position'),
                TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->iconColor('primary')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('phone')
                    ->copyable()
                    ->copyMessage('Phone number copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('dob'),
                // TextColumn::make(takeNameOfUserId('user_id'))->badge(),
                TextColumn::make('user_id')->badge()->label('Role'),
                // TextInput::make('user_id')->badge()->options(
                //     User::pluck('name', 'id')->toArray(),
                // ),
            ])
            ->filters([
                Filter::make('user-employee-filter')
                    ->form([
                        Select::make('user_id')
                            ->label('Filter by Role')
                            ->placeholder('Select a Role')
                            ->options(
                                User::pluck('name', 'id')->toArray(),
                            )
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['user_id'], function ($query) use ($data) {
                            return $query->where('user_id', $data['user_id']);
                        });
                    }),
            ])
            ->actions([
                Action::make('downloadPdf')
                    ->url(function (Employees $employee) {
                        return route('employee.invoice.generate', $employee);
                    }),
                Action::make('qrCode')
                    ->url(function (Employees $record) {
                        return static::getUrl('qrCode', ['record' => $record]);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\ACtions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('export')
                        ->label('Export Records')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return (new EmployeesExport($records))
                                ->download('employees.xlsx');
                        })
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployees::route('/create'),
            'edit' => Pages\EditEmployees::route('/{record}/edit'),
            'qrCode' => Pages\GenerateQrCode::route('/{record}/qrcode'),
        ];
    }
}
