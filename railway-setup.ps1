# Railway Deployment Setup Script (PowerShell)
# Run this before deploying to Railway

Write-Host ""
Write-Host "=============================" -ForegroundColor Cyan
Write-Host "Railway Deployment Setup" -ForegroundColor Cyan
Write-Host "=============================" -ForegroundColor Cyan
Write-Host ""

# 1. Check if composer is installed
Write-Host "Checking Composer..." -ForegroundColor Yellow
$composerExists = Get-Command composer -ErrorAction SilentlyContinue
if (-not $composerExists) {
    Write-Host "ERROR: Composer is not installed." -ForegroundColor Red
    Write-Host "Please install Composer first: https://getcomposer.org" -ForegroundColor Yellow
    exit 1
}
Write-Host "OK: Composer found" -ForegroundColor Green
Write-Host ""

# 2. Install dependencies
Write-Host "Installing dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader
Write-Host ""

# 3. Generate APP_KEY
Write-Host "Generating APP_KEY..." -ForegroundColor Yellow
$APP_KEY = php artisan key:generate --show
Write-Host ""
Write-Host "=============================" -ForegroundColor Green
Write-Host "YOUR APP_KEY:" -ForegroundColor Green
Write-Host $APP_KEY -ForegroundColor White
Write-Host "=============================" -ForegroundColor Green
Write-Host "IMPORTANT: Copy this key and add it to Railway Variables!" -ForegroundColor Yellow
Write-Host ""

# 4. Generate Passport Keys
Write-Host "Generating Passport keys..." -ForegroundColor Yellow
php artisan passport:keys --force
Write-Host ""

# 5. Check if keys exist
if ((Test-Path "storage/oauth-private.key") -and (Test-Path "storage/oauth-public.key")) {
    Write-Host "OK: Passport keys generated successfully" -ForegroundColor Green
} else {
    Write-Host "ERROR: Failed to generate Passport keys" -ForegroundColor Red
    exit 1
}
Write-Host ""

# 6. Create .env.example if not exists
if (-not (Test-Path ".env.example")) {
    Write-Host "Creating .env.example..." -ForegroundColor Yellow
    Copy-Item .env .env.example -ErrorAction SilentlyContinue
    Write-Host "OK: .env.example created" -ForegroundColor Green
} else {
    Write-Host "OK: .env.example exists" -ForegroundColor Green
}
Write-Host ""

# 7. Check required files
Write-Host "Checking required files..." -ForegroundColor Yellow
$allFilesExist = $true
$requiredFiles = @("Procfile", "nixpacks.toml", ".env.example", "composer.json")
foreach ($file in $requiredFiles) {
    if (Test-Path $file) {
        Write-Host "  OK: $file" -ForegroundColor Green
    } else {
        Write-Host "  ERROR: $file is missing" -ForegroundColor Red
        $allFilesExist = $false
    }
}

if (-not $allFilesExist) {
    Write-Host ""
    Write-Host "ERROR: Some required files are missing!" -ForegroundColor Red
    exit 1
}
Write-Host ""

# 8. Git status
Write-Host "Checking Git status..." -ForegroundColor Yellow
$gitExists = Get-Command git -ErrorAction SilentlyContinue
if ($gitExists) {
    $isGitRepo = git rev-parse --git-dir 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "OK: Git repository initialized" -ForegroundColor Green

        $gitStatus = git status -s
        if ($gitStatus) {
            Write-Host ""
            Write-Host "Files to commit:" -ForegroundColor Yellow
            git status -s
            Write-Host ""
            Write-Host "Run these commands to push to GitHub:" -ForegroundColor Cyan
            Write-Host "  git add ." -ForegroundColor White
            Write-Host "  git commit -m 'Prepare for Railway deployment'" -ForegroundColor White
            Write-Host "  git push origin main" -ForegroundColor White
        } else {
            Write-Host "OK: All files committed" -ForegroundColor Green
        }
    } else {
        Write-Host "WARNING: Git not initialized" -ForegroundColor Yellow
        Write-Host "Run: git init" -ForegroundColor White
    }
} else {
    Write-Host "WARNING: Git not installed" -ForegroundColor Yellow
}
Write-Host ""

# 9. Final checklist
Write-Host "=============================" -ForegroundColor Cyan
Write-Host "Setup Complete!" -ForegroundColor Green
Write-Host "=============================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Railway Deployment Checklist:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Copy APP_KEY above"
Write-Host "2. Create GitHub repository and push code"
Write-Host "3. Login to Railway.app with GitHub"
Write-Host "4. Create New Project from GitHub repo"
Write-Host "5. Add MySQL database in Railway"
Write-Host "6. Configure environment variables"
Write-Host "7. Generate domain in Railway Settings"
Write-Host "8. Run migrations and seeder"
Write-Host "9. Test API endpoints"
Write-Host "10. Update Flutter app with production URL"
Write-Host ""
Write-Host "Full tutorial: DEPLOY_RAILWAY_TUTORIAL.md" -ForegroundColor Cyan
Write-Host "Quick start: QUICK_START_RAILWAY.md" -ForegroundColor Cyan
Write-Host ""

