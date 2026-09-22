<#
PowerShell script to create DB dump and optional zip of files.
Usage: .\backup_windows.ps1 -Path storage\\app\\backups -Compress
#>
param(
    [string]$Path = "storage\\app\\backups",
    [switch]$Compress
)

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $projectRoot

$envFile = Join-Path $projectRoot ".env"
if (-not (Test-Path $envFile)) {
    Write-Error ".env not found in project root"
    exit 1
}

# Read .env simple parser
function Get-EnvValue($key) {
    $line = Select-String -Path $envFile -Pattern "^$key=" -SimpleMatch | Select-Object -Last 1
    if ($null -eq $line) { return "" }
    $val = $line -replace "^$key=", ""
    $val = $val.Trim().Trim("\"")
    return $val
}

$dbConn = Get-EnvValue "DB_CONNECTION"
$dbHost = Get-EnvValue "DB_HOST"
$dbPort = Get-EnvValue "DB_PORT"
$dbName = Get-EnvValue "DB_DATABASE"
$dbUser = Get-EnvValue "DB_USERNAME"
$dbPass = Get-EnvValue "DB_PASSWORD"

$fullOut = Join-Path $projectRoot $Path
if (-not (Test-Path $fullOut)) { New-Item -ItemType Directory -Path $fullOut -Force | Out-Null }

$ts = Get-Date -Format "yyyyMMdd_HHmmss"

if ($dbConn -eq "sqlite") {
    $sqlitePath = Join-Path $projectRoot (Get-EnvValue "DB_DATABASE")
    if (-not (Test-Path $sqlitePath)) { Write-Error "SQLite DB not found: $sqlitePath"; exit 1 }
    $dest = Join-Path $fullOut "sqlite_backup_$ts.sqlite"
    Copy-Item -Path $sqlitePath -Destination $dest -Force
    Write-Output "SQLite backup copied to: $dest"
} elseif ($dbConn -eq "mysql") {
    $mysqldump = "mysqldump"
    if (-not (Get-Command $mysqldump -ErrorAction SilentlyContinue)) {
        Write-Warning "mysqldump not found in PATH. Install MySQL client or add to PATH.";
    } else {
        $outFile = Join-Path $fullOut "mysql_backup_$ts.sql"
        $env:MYSQL_PWD = $dbPass
        & $mysqldump --single-transaction --quick --skip-lock-tables -h $dbHost -P $dbPort -u $dbUser $dbName > $outFile
        Remove-Item env:MYSQL_PWD
        Write-Output "MySQL dump written to: $outFile"
        if ($Compress) { Compress-Archive -Path $outFile -DestinationPath ($outFile + '.zip') -Force; Remove-Item $outFile }
    }
} elseif ($dbConn -eq "pgsql") {
    $pgdump = "pg_dump"
    if (-not (Get-Command $pgdump -ErrorAction SilentlyContinue)) { Write-Warning "pg_dump not found in PATH." } else {
        $outFile = Join-Path $fullOut "pg_backup_$ts.sql"
        $env:PGPASSWORD = $dbPass
        & $pgdump -h $dbHost -p $dbPort -U $dbUser -F p -f $outFile $dbName
        Remove-Item env:PGPASSWORD
        Write-Output "Postgres dump written to: $outFile"
        if ($Compress) { Compress-Archive -Path $outFile -DestinationPath ($outFile + '.zip') -Force; Remove-Item $outFile }
    }
} else {
    Write-Warning "Unsupported DB_CONNECTION: $dbConn"
}

# optional: zip storage/app and public/uploads
if ($Compress) {
    $zipFile = Join-Path $fullOut ("files_backup_$ts.zip")
    $pathsToZip = @()
    if (Test-Path (Join-Path $projectRoot 'storage\app')) { $pathsToZip += (Join-Path $projectRoot 'storage\app') }
    if (Test-Path (Join-Path $projectRoot 'public\uploads')) { $pathsToZip += (Join-Path $projectRoot 'public\uploads') }
    if ($pathsToZip.Count -eq 0) { Write-Output "No file folders to compress." } else {
        Compress-Archive -Path $pathsToZip -DestinationPath $zipFile -Force
        Write-Output "Files zip created: $zipFile"
    }
}

Write-Output "Backup script completed."