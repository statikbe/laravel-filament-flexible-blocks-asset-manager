# Upgrades

# v4.1.0

Due to the addition of custom file names, we needed to add a new migration.
Publish the migration [`add_custom_file_name_to_assets_table.php.stub`](database/migrations/add_custom_file_name_to_assets_table.php) 
and run `php artisan migrate`.
