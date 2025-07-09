<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    protected static ?string $navigationLabel = 'Banner Hero';
    
    protected static ?string $modelLabel = 'Banner';
    
    protected static ?string $pluralModelLabel = 'Banner Hero';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Banner')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('subtitle')
                            ->label('Sub Judul')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('button_text')
                                    ->label('Teks Tombol')
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('button_link')
                                    ->label('Link Tombol')
                                    ->url()
                                    ->maxLength(255),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),
                
                Forms\Components\Section::make('Gambar Banner')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('banner_image')
                            ->label('Foto Model')
                            ->collection('banner_images')  // Pastikan sama dengan model
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3', 
                                '1:1',
                            ])
                            ->required()
                            ->helperText('Upload foto model untuk banner hero. Ukuran optimal: 1920x800px'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('banner_image')
                    ->label('Gambar')
                    ->collection('banner_images')  // Pastikan sama dengan model
                    ->conversion('thumb')
                    ->size(80),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Sub Judul')
                    ->searchable()
                    ->limit(50),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->native(false),
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}