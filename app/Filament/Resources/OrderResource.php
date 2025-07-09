<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationGroup = 'Orders';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        TextInput::make('customer_name')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('customer_email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('customer_phone')
                            ->required()
                            ->maxLength(255)
                            ->rules([
                                'regex:/^.{7,20}$/'
                            ])
                            ->helperText('Minimal 7 karakter, maksimal 20 karakter'),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Order Details')
                    ->schema([
                        TextInput::make('order_number')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(false),
                            
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                            
                        TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),
                            
                        TextInput::make('shipping_cost')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                            
                        TextInput::make('tax_amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                            
                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Shipping Information')
                    ->relationship('shippingInfo')
                    ->schema([
                        TextInput::make('recipient_name')
                            ->label('Nama Penerima')
                            ->maxLength(255)
                            ->required(),
                            
                        TextInput::make('phone')
                            ->label('Telepon Penerima')
                            ->tel()
                            ->maxLength(255)
                            ->required(),
                            
                        Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->columnSpanFull()
                            ->required(),
                            
                        TextInput::make('city')
                            ->label('Kota')
                            ->maxLength(255)
                            ->required(),
                            
                        TextInput::make('province')
                            ->label('Provinsi')
                            ->maxLength(255)
                            ->required(),
                            
                        TextInput::make('postal_code')
                            ->label('Kode Pos')
                            ->maxLength(10)
                            ->required(),
                            
                        Select::make('shipping_method')
                            ->label('Metode Pengiriman')
                            ->options([
                                'Standard' => 'Standard',
                                'JNE' => 'JNE',
                                'TIKI' => 'TIKI',
                                'POS' => 'POS Indonesia',
                                'J&T' => 'J&T Express',
                                'SiCepat' => 'SiCepat',
                                'AnterAja' => 'AnterAja',
                            ])
                            ->default('Standard'),
                            
                        TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->maxLength(255),
                            
                        TextInput::make('shipping_cost')
                            ->label('Biaya Pengiriman')
                            ->numeric()
                            ->prefix('Rp')
                            ->step(0.01)
                            ->default(0),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('customer_email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('customer_phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'primary' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),
                    
                TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('orderItems_count')
                    ->counts('orderItems')
                    ->label('Items'),
                    
                TextColumn::make('shippingInfo.city')
                    ->label('Kota')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('shippingInfo.province')
                    ->label('Provinsi')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('shippingInfo.shipping_method')
                    ->label('Metode Pengiriman')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('shippingInfo.tracking_number')
                    ->label('Nomor Resi')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Order Date'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('print_invoice')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Order $record): string => route('orders.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    protected static ?string $recordTitleAttribute = 'order_number';
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['shippingInfo', 'orderItems']);
    }
}