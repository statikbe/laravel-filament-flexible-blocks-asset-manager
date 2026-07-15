# Upgrades

# v4.1.0

Due to the addition of custom file names, we needed to add a new migration.
Publish the migration [`add_custom_file_name_to_assets_table`](database/migrations/add_custom_file_name_to_assets_table.php):

```
php artisan vendor:publish --tag="filament-flexible-blocks-asset-manager-migrations"
php artisan migrate
```
