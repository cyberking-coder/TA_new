<?php

namespace App\Filament\Resources\HeroMedia\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroMediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Image')
                ->schema([
                    FileUpload::make('image_path')
                        ->label('Slide image')
                        ->image()
                        ->disk('public')
                        ->directory('hero')
                        ->imagePreviewHeight('150')
                        ->maxSize(5 * 1024)
                        ->helperText('JPG, PNG or WebP. 1600–2000 px wide works best. Max 5 MB.'),
                    TextInput::make('alt_text')
                        ->label('Alt text')
                        ->maxLength(200)
                        ->helperText('Describe the image for screen readers. If decorative, leave blank.'),
                ])->columns(1),

            Section::make('Slider timing')
                ->schema([
                    TextInput::make('duration_seconds')
                        ->label('Seconds to show this slide')
                        ->numeric()
                        ->minValue(2)
                        ->maxValue(60)
                        ->default(5)
                        ->required()
                        ->suffix('s'),
                    TextInput::make('display_order')
                        ->label('Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers appear first. Ties break by most recently updated.'),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Deactivate to hide this slide without deleting it.'),
                ])->columns(3),

            Section::make('Overlay copy (optional)')
                ->description('Shown on top of the slide image. Leave empty to keep the default home-page copy.')
                ->schema([
                    TextInput::make('headline')->maxLength(200),
                    TextInput::make('subhead')->maxLength(500),
                    TextInput::make('action_label')->maxLength(60),
                    TextInput::make('action_url')->url()->maxLength(2048),
                    Toggle::make('is_featured')
                        ->label('Featured (legacy — not used by the new slider)'),
                ])->columns(2),
        ]);
    }
}
