#!/bin/bash

# Railway Deployment Setup Script
# Run this before deploying to Railway

echo "🚂 Railway Deployment Setup"
echo "=============================="
echo ""

# 1. Check if composer is installed
if ! command -v composer &> /dev/null
then
    echo "❌ Composer is not installed. Please install it first."
    exit 1
fi

echo "✅ Composer found"

# 2. Install dependencies
echo ""
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Generate APP_KEY
echo ""
echo "🔑 Generating APP_KEY..."
APP_KEY=$(php artisan key:generate --show)
echo "Your APP_KEY: $APP_KEY"
echo "👉 Copy this and add to Railway Variables!"

# 4. Generate Passport Keys
echo ""
echo "🔐 Generating Passport keys..."
php artisan passport:keys --force

# 5. Check if keys exist
if [ -f "storage/oauth-private.key" ] && [ -f "storage/oauth-public.key" ]; then
    echo "✅ Passport keys generated successfully"
else
    echo "❌ Failed to generate Passport keys"
    exit 1
fi

# 6. Create .env.example if not exists
if [ ! -f ".env.example" ]; then
    echo ""
    echo "📝 Creating .env.example..."
    cp .env .env.example
    echo "✅ .env.example created"
fi

# 7. Check required files
echo ""
echo "📋 Checking required files..."
required_files=("Procfile" "nixpacks.toml" ".env.example" "composer.json")
for file in "${required_files[@]}"
do
    if [ -f "$file" ]; then
        echo "✅ $file exists"
    else
        echo "❌ $file is missing"
    fi
done

# 8. Git status
echo ""
echo "📊 Git Status:"
if git rev-parse --git-dir > /dev/null 2>&1; then
    echo "✅ Git repository initialized"
    
    # Show untracked/modified files
    if [[ -n $(git status -s) ]]; then
        echo ""
        echo "📝 Files to commit:"
        git status -s
        echo ""
        echo "Run these commands to commit:"
        echo "  git add ."
        echo "  git commit -m 'Prepare for Railway deployment'"
        echo "  git push"
    else
        echo "✅ All files committed"
    fi
else
    echo "⚠️  Git not initialized"
    echo "Run: git init"
fi

# 9. Final checklist
echo ""
echo "=============================="
echo "✅ Setup Complete!"
echo "=============================="
echo ""
echo "📋 Railway Deployment Checklist:"
echo ""
echo "1. [ ] Copy APP_KEY above and add to Railway Variables"
echo "2. [ ] Add MySQL database in Railway"
echo "3. [ ] Configure database variables (DB_HOST, DB_PORT, etc)"
echo "4. [ ] Push code to GitHub"
echo "5. [ ] Deploy from GitHub in Railway"
echo "6. [ ] Run migrations: railway run php artisan migrate --force"
echo "7. [ ] Run seeder: railway run php artisan db:seed --force"
echo "8. [ ] Install Passport: railway run php artisan passport:install --force"
echo "9. [ ] Generate domain in Railway"
echo "10. [ ] Test API endpoints"
echo ""
echo "📖 Full tutorial: DEPLOY_RAILWAY_TUTORIAL.md"
echo ""

