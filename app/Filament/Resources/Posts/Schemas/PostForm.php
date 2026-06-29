<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ── Kolom Kiri: Konten Utama (2/3 lebar) ──
                \Filament\Schemas\Components\Group::make()->schema([

                    \Filament\Schemas\Components\Section::make('Konten Artikel')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Judul Artikel')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) =>
                                    $set('slug', Str::slug($state ?? ''))
                                ),

                            Forms\Components\TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(Post::class, 'slug', ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('Otomatis dari judul. Bisa diubah manual.'),

                            Forms\Components\RichEditor::make('content')
                                ->label('Isi Artikel')
                                ->required()
                                ->toolbarButtons([
                                    'attachFiles',
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'h2', 'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'underline',
                                    'undo',
                                ])
                                ->fileAttachmentsDirectory('posts/attachments')
                                ->columnSpanFull(),
                        ])->columns(2),

                    // SEO Section
                    \Filament\Schemas\Components\Section::make('SEO')
                        ->description('Optimasi mesin pencari untuk artikel ini')
                        ->collapsed()
                        ->schema([
                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Maks 160 karakter. Ditampilkan di hasil Google.')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('meta_keywords')
                                ->label('Meta Keywords')
                                ->helperText('Pisahkan dengan koma: teknologi, berita, indonesia')
                                ->placeholder('keyword1, keyword2, keyword3')
                                ->columnSpanFull(),
                        ]),

                ])->columnSpan(['lg' => 2]),

                // ── Kolom Kanan: Sidebar (1/3 lebar) ──
                \Filament\Schemas\Components\Group::make()->schema([

                    \Filament\Schemas\Components\Section::make('Publikasi')
                        ->schema([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft'     => 'Draft',
                                    'published' => 'Published',
                                ])
                                ->default('draft')
                                ->required()
                                ->live(),

                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('Tanggal Publikasi')
                                ->visible(fn (Get $get) => $get('status') === 'published')
                                ->default(now())
                                ->seconds(false),

                            Forms\Components\Select::make('author_id')
                                ->label('Penulis')
                                ->relationship('author', 'name')
                                ->default(fn () => auth()->id())
                                ->required()
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->placeholder('Pilih kategori...'),

                        ]),

                    \Filament\Schemas\Components\Section::make('Gambar Utama')
                        ->schema([
                            Forms\Components\FileUpload::make('featured_image')
                                ->label('Upload Gambar')
                                ->image()
                                ->directory('posts/featured')
                                ->visibility('public')
                                ->maxSize(2048)
                                ->helperText('Format: JPG/PNG/WebP. Maks 2MB.'),
                        ]),

                ])->columnSpan(['lg' => 1]),

            ])->columns(3);
    }
}
