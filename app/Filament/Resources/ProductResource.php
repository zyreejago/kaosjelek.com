<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Catalog';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Information')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                            
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                        
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(255)
                            ->unique(Product::class, 'sku', ignoreRecord: true),
                        
                        Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Pricing & Inventory')
                    ->schema([
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->step(0.01),
                            
                        TextInput::make('sale_price')
                            ->numeric()
                            ->prefix('Rp')
                            ->step(0.01)
                            ->label('Sale Price (Optional)'),
                            
                        TextInput::make('stock_quantity')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                            
                        TextInput::make('weight')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('kg')
                            ->label('Weight (for shipping)'),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Product Details')
                    ->schema([
                        TextInput::make('material')
                            ->maxLength(255)
                            ->placeholder('e.g., Cotton, Polyester'),
                            
                        TagsInput::make('sizes')
                            ->placeholder('Add sizes (S, M, L, XL, XXL)')
                            ->suggestions(['S', 'M', 'L', 'XL', 'XXL', '3XL'])
                            ->columnSpanFull(),
                            
                        TagsInput::make('colors')
                            ->placeholder('Add available colors')
                            ->suggestions(['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Pink', 'Purple'])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Images')
                    ->schema([
                        FileUpload::make('images')
                            ->image()
                            ->multiple()
                            ->directory('products')
                            ->maxFiles(5)
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->default(true),
                            
                        Toggle::make('is_featured')
                            ->default(false),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->getStateUsing(fn ($record) => $record->images[0] ?? null)
                    ->circular()
                    ->size(50),
                    
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                    
                TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('sale_price')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('No sale'),
                    
                TextColumn::make('stock_quantity')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    }),
                    
                BooleanColumn::make('is_active')
                    ->sortable(),
                    
                BooleanColumn::make('is_featured')
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
                    
                Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->label('Active Only'),
                    
                Filter::make('featured')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
                    ->label('Featured Only'),
                    
                Filter::make('in_stock')
                    ->query(fn (Builder $query): Builder => $query->where('stock_quantity', '>', 0))
                    ->label('In Stock Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
