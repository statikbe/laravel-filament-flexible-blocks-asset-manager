<?php

use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

// Normale bestandsnamen
it('keeps a simple filename intact', function () {
    expect(Asset::buildSafeFileName('document', 'pdf'))->toBe('document.pdf');
});

it('keeps a filename with spaces', function () {
    expect(Asset::buildSafeFileName('my document', 'pdf'))->toBe('my document.pdf');
});

it('keeps a filename with accents', function () {
    expect(Asset::buildSafeFileName('résumé café', 'pdf'))->toBe('résumé café.pdf');
});

it('keeps a filename with hyphens and underscores', function () {
    expect(Asset::buildSafeFileName('my-file_name', 'pdf'))->toBe('my-file_name.pdf');
});

it('keeps a filename with numbers', function () {
    expect(Asset::buildSafeFileName('report 2024', 'pdf'))->toBe('report 2024.pdf');
});

// Extensie-afhandeling
it('does not duplicate extension when already present', function () {
    expect(Asset::buildSafeFileName('document.pdf', 'pdf'))->toBe('document.pdf');
});

it('adds correct extension when filename has no extension', function () {
    expect(Asset::buildSafeFileName('document', 'jpg'))->toBe('document.jpg');
});

it('keeps both extensions when they differ', function () {
    expect(Asset::buildSafeFileName('document.docx', 'pdf'))->toBe('document.docx.pdf');
});

// Gevaarlijke tekens
it('removes forward slashes', function () {
    expect(Asset::buildSafeFileName('path/to/file', 'pdf'))->toBe('pathtofile.pdf');
});

it('removes backslashes', function () {
    expect(Asset::buildSafeFileName('path\\file', 'pdf'))->toBe('pathfile.pdf');
});

it('removes semicolons', function () {
    expect(Asset::buildSafeFileName('file;name', 'pdf'))->toBe('filename.pdf');
});

it('removes ampersands', function () {
    expect(Asset::buildSafeFileName('file&name', 'pdf'))->toBe('filename.pdf');
});

it('removes backticks', function () {
    expect(Asset::buildSafeFileName('file`name', 'pdf'))->toBe('filename.pdf');
});

it('removes angle brackets', function () {
    expect(Asset::buildSafeFileName('file<script>name', 'pdf'))->toBe('filescriptname.pdf');
});

it('removes pipe characters', function () {
    expect(Asset::buildSafeFileName('file|name', 'pdf'))->toBe('filename.pdf');
});

it('removes dot-dot sequences', function () {
    expect(Asset::buildSafeFileName('..file..name', 'pdf'))->toBe('filename.pdf');
});

it('removes null bytes', function () {
    expect(Asset::buildSafeFileName("file\x00name", 'pdf'))->toBe('filename.pdf');
});

// Combinaties
it('handles a filename with multiple dangerous characters', function () {
    expect(Asset::buildSafeFileName('../../etc/passwd;rm -rf|', 'pdf'))->toBe('etcpasswdrm -rf.pdf');
});

it('handles a filename with accents and dangerous characters', function () {
    expect(Asset::buildSafeFileName('café<script>;alert', 'pdf'))->toBe('caféscriptalert.pdf');
});
