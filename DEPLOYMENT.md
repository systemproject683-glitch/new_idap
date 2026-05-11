# Vercel Deployment Guide for L&D Plan System

## Overview
This guide will help you deploy the Laravel L&D Plan system to Vercel.

## Important Notes
- **SQLite is not supported on Vercel** - You must use a managed database
- Vercel's PHP runtime is used for Laravel deployment
- File uploads need a cloud storage solution (AWS S3, Cloudflare R2, etc.)

## Prerequisites
1. Vercel account (free at https://vercel.com)
2. GitHub repository with your code
3. Managed database (Vercel Postgres, Supabase, or PlanetScale)

## Step 1: Push Code to GitHub
```bash
git add .
git commit -m "Prepare for Vercel deployment"
git push origin main
```

## Step 2: Set Up Database

### Option A: Vercel Postgres (Recommended)
1. Go to your Vercel project
2. Navigate to Storage → Create Database
3. Select Postgres
4. Copy the connection strings

### Option B: Supabase
1. Create account at https://supabase.com
2. Create new project
3. Get connection details from Settings → Database

### Option C: PlanetScale
1. Create account at https://planetscale.com
2. Create new database
3. Get connection details

## Step 3: Deploy to Vercel

1. **Import Repository**
   - Go to https://vercel.com
   - Click "Add New Project"
   - Import your GitHub repository

2. **Configure Project**
   - Framework Preset: Other
   - Root Directory: ./
   - Build Command: (leave empty)
   - Output Directory: public

3. **Add Environment Variables**
   Go to Settings → Environment Variables and add:

   ```
   APP_NAME=L&D Plan
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=your-generated-app-key
   APP_URL=https://your-app-name.vercel.app
   
   DB_CONNECTION=pgsql
   DB_HOST=your-db-host
   DB_PORT=5432
   DB_DATABASE=your-db-name
   DB_USERNAME=your-db-username
   DB_PASSWORD=your-db-password
   
   SESSION_DRIVER=cookie
   CACHE_DRIVER=redis
   QUEUE_CONNECTION=redis
   FILESYSTEM_DISK=local
   ```

   **Generate APP_KEY:**
   ```bash
   php artisan key:generate --show
   ```

   **For Google OAuth:**
   ```
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   GOOGLE_REDIRECT_URI=https://your-app-name.vercel.app/auth/google/callback
   ```

4. **Deploy**
   - Click "Deploy"
   - Wait for deployment to complete

## Step 4: Run Database Migrations

Since Vercel doesn't have SSH access, you need to run migrations locally connected to the remote database:

```bash
# Set your remote database credentials in .env
php artisan migrate --force
php artisan db:seed --force
```

Or use Vercel's CLI:
```bash
vercel env pull .env.production
php artisan migrate --force
```

## Step 5: Configure File Storage (Optional)

If your app handles file uploads, you need cloud storage:

### AWS S3 Setup
```
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
FILESYSTEM_DISK=s3
```

### Cloudflare R2 (Free Alternative)
Similar setup to S3 but with free tier.

## Step 6: Update APP_URL

After deployment, update the APP_URL in Vercel environment variables to your actual domain:
```
APP_URL=https://your-app-name.vercel.app
```

Or if using custom domain:
```
APP_URL=https://your-custom-domain.com
```

## Common Issues & Solutions

### Issue: Database Connection Failed
- Verify database credentials in environment variables
- Check if database allows connections from Vercel's IP ranges
- Ensure DB_CONNECTION matches your database type (pgsql for Postgres, mysql for MySQL)

### Issue: Storage/Uploads Not Working
- Vercel's file system is ephemeral
- Use AWS S3, Cloudflare R2, or similar for persistent storage
- Update FILESYSTEM_DISK to s3

### Issue: Sessions Not Persisting
- Change SESSION_DRIVER to cookie
- Or use Redis for session storage

### Issue: Cache Not Working
- Use Redis for cache (Vercel provides Redis add-on)
- Set CACHE_DRIVER=redis

## Post-Deployment Checklist

- [ ] Database migrations run successfully
- [ ] Environment variables configured
- [ ] File uploads working (if applicable)
- [ ] Google OAuth configured (if needed)
- [ ] Custom domain set up (optional)
- [ ] SSL certificate active (automatic on Vercel)
- [ ] Test all user flows

## Monitoring & Logs

- View deployment logs in Vercel dashboard
- Monitor application logs
- Set up error tracking (Sentry, Bugsnag, etc.)

## Custom Domain (Optional)

1. Go to project Settings → Domains
2. Add your custom domain
3. Update DNS records as instructed
4. Update APP_URL environment variable

## Support

For issues:
- Vercel Documentation: https://vercel.com/docs
- Laravel on Vercel: https://vercel.com/guides/deploying-laravel-with-vercel
- Vercel PHP Runtime: https://github.com/vercel/vercel-php
