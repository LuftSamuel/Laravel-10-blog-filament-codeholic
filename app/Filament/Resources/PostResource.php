<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-add';

    protected static ?string $navigationGroup = 'Contenido';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('titulo')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state) {
                                        $set('descripcion', Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('descripcion')
                                    ->required()
                                    ->maxLength(255)
                            ]),

                        Forms\Components\RichEditor::make('post')
                            ->required(),
                        Forms\Components\TextInput::make('meta_title'),
                        Forms\Components\TextInput::make('meta_description'),
                        Forms\Components\Toggle::make('activo')
                            ->required(),
                        Forms\Components\DateTimePicker::make('publicarse_en'),

                    ])->columnspan(8),

                    Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\FileUpload::make('miniatura'),
                        Forms\Components\Select::make('id_categoria')
                            ->multiple()
                            ->relationship('categorias', 'titulo')
                            ->required(),
                        // al principio no me creo la relacion y cuando intentaba
                        // crearla yo me decia que el metodo no existe, despues 
                        // cambie TextInput por Select y entonces si pude agregar
                        // la relacion, pero todavia no se por que no me lo creo
                        // asi desde el comienzo (min 29 masomenos)

                    ])->columnspan(4),

            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('miniatura'),
                Tables\Columns\TextColumn::make('titulo'),
                Tables\Columns\IconColumn::make('activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('publicarse_en')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('nombre'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
