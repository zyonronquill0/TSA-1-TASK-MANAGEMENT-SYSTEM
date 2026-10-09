# Build an upload folder for Apache shared hosting with a fixed htdocs root.
# Run from the project: powershell -ExecutionPolicy Bypass -File scripts\prepare-hosting.ps1
$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$bundleRoot = Join-Path $projectRoot ('dist\hosting-' + (Get-Date -Format 'yyyyMMdd-HHmmss'))
$webRoot = Join-Path $bundleRoot 'htdocs'
$privateRoot = Join-Path $webRoot 'private'
$utf8 = New-Object System.Text.UTF8Encoding($false)

if (Test-Path -LiteralPath $bundleRoot) {
    throw 'This output folder already exists. Wait a second and run again.'
}

New-Item -ItemType Directory -Path $privateRoot -Force | Out-Null
Get-ChildItem -LiteralPath (Join-Path $projectRoot 'public') -Force | Copy-Item -Destination $webRoot -Recurse -Force
Copy-Item -LiteralPath (Join-Path $projectRoot 'app') -Destination $privateRoot -Recurse
Copy-Item -LiteralPath (Join-Path $projectRoot 'composer.json'), (Join-Path $projectRoot 'composer.lock'), (Join-Path $projectRoot 'LICENSE') -Destination $privateRoot

# Create clean writable folders without copying local sessions or logs.
foreach ($directory in @('cache', 'debugbar', 'logs', 'session', 'uploads')) {
    $directoryPath = Join-Path $privateRoot ('writable\' + $directory)
    New-Item -ItemType Directory -Path $directoryPath -Force | Out-Null
    [System.IO.File]::WriteAllText((Join-Path $directoryPath 'index.html'), '<!DOCTYPE html><title>Private directory</title>', $utf8)
}

# A shared host may only allow files within htdocs. Block the entire private
# folder at Apache level, and load those files from PHP by filesystem path.
$denyAccess = @'
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order allow,deny
    Deny from all
</IfModule>
'@
[System.IO.File]::WriteAllText((Join-Path $privateRoot '.htaccess'), $denyAccess, $utf8)

$indexPath = Join-Path $webRoot 'index.php'
$index = [System.IO.File]::ReadAllText($indexPath)
$index = $index.Replace("'../app/Config/Paths.php'", "'private/app/Config/Paths.php'")
[System.IO.File]::WriteAllText($indexPath, $index, $utf8)

$productionEnvironment = @'
CI_ENVIRONMENT = production
app.baseURL = 'https://YOUR-SITE.example/'
app.indexPage = ''
app.appTimezone = 'Asia/Manila'
database.default.hostname = YOUR_MYSQL_HOST
database.default.database = YOUR_DATABASE_NAME
database.default.username = YOUR_DATABASE_USER
database.default.password = 'YOUR_DATABASE_PASSWORD'
database.default.DBDriver = MySQLi
database.default.port = 3306
database.default.DBDebug = false
'@
[System.IO.File]::WriteAllText((Join-Path $privateRoot '.env'), $productionEnvironment, $utf8)

# Install only runtime dependencies in the copy, leaving local development intact.
& composer install --working-dir=$privateRoot --no-dev --prefer-dist --optimize-autoloader --no-interaction
if ($LASTEXITCODE -ne 0) {
    throw 'Composer failed. Check the message above before uploading this folder.'
}

Write-Host ''
Write-Host 'Hosting folder is ready:'
Write-Host $webRoot
Write-Host '1. Edit private\.env in this generated folder with your hosting details.'
Write-Host '2. Import database\hosting_seed.sql into your empty hosting database.'
Write-Host '3. Upload the CONTENTS of this htdocs folder, including hidden .htaccess files.'
Write-Host '4. Follow the hosting verification steps in README.md.'
