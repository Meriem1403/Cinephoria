Write-Host "?? Cleaning environment..."
Remove-Item -Recurse -Force node_modules, public\build, var

Write-Host "?? Reinstalling dependencies..."
yarn install

Write-Host "?? Building front assets..."
yarn build

Write-Host "?? Clearing Symfony cache..."
php bin/console cache:clear

Write-Host "?? Warming up Symfony cache..."
php bin/console cache:warmup

Write-Host "? Project reset completed!"
