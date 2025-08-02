# 🔐 Tutorial: Generate Bearer Token for Sekaijin API

## Prerequisites
- Production access to Sekaijin
- User account with API access permissions
- SSH access to production server (for method 2)

## Method 1: Via Tinker (Production Server)

### Step 1: SSH into production
```bash
ssh user@your-production-server.com
cd /path/to/sekaijin
```

### Step 2: Open Laravel Tinker
```bash
php artisan tinker
```

### Step 3: Find your user
```php
// By email
$user = \App\Models\User::where('email', 'your-email@example.com')->first();

// Or by ID
$user = \App\Models\User::find(378);
```

### Step 4: Generate token
```php
// Create token with name
$token = $user->createToken('n8n-automation')->plainTextToken;

// Display token (save this!)
echo $token;
```

### Step 5: Exit Tinker
```php
exit
```

## Method 2: Create Token Generation Command

### Step 1: Create command on production
```bash
php artisan make:command GenerateApiToken
```

### Step 2: Edit the command
```php
<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateApiToken extends Command
{
    protected $signature = 'api:generate-token {email}';
    protected $description = 'Generate API token for a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found!');
            return 1;
        }

        $token = $user->createToken('api-access')->plainTextToken;
        
        $this->info('Token generated successfully!');
        $this->line('Bearer Token: ' . $token);
        $this->warn('Save this token securely - it cannot be retrieved again!');
        
        return 0;
    }
}
```

### Step 3: Run the command
```bash
php artisan api:generate-token your-email@example.com
```

## Method 3: Via API Endpoint (if implemented)

### Step 1: Login to get token
```bash
curl -X POST https://sekaijin.com/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "your-email@example.com",
    "password": "your-password"
  }'
```

### Step 2: Response will contain token
```json
{
  "user": {
    "id": 378,
    "name": "Your Name"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz123456789"
}
```

## Using the Token

### In n8n HTTP Request node:
```json
{
  "headers": {
    "Authorization": "Bearer YOUR_TOKEN_HERE",
    "Content-Type": "application/json",
    "Accept": "application/json"
  }
}
```

### In cURL:
```bash
curl -X POST https://sekaijin.com/api/news \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"title": "Test", "content": "..."}'
```

### In Postman:
1. Go to Authorization tab
2. Select "Bearer Token"
3. Paste your token

## Token Management

### List user's tokens (Tinker)
```php
$user = User::find(378);
$user->tokens()->get();
```

### Revoke specific token
```php
$user->tokens()->where('id', $tokenId)->delete();
```

### Revoke all tokens
```php
$user->tokens()->delete();
```

## Security Best Practices

1. **Never share tokens** in logs or commits
2. **Use environment variables** for token storage
3. **Rotate tokens regularly** (monthly recommended)
4. **Use descriptive token names** for easy management
5. **Monitor token usage** in production logs

## Troubleshooting

### Token not working?
- Check if Sanctum is properly configured in production
- Verify the user has proper permissions
- Ensure token hasn't been revoked
- Check for typos (no extra spaces)

### Getting 401 Unauthorized?
- Token might be expired or revoked
- Format must be exactly: `Bearer YOUR_TOKEN`
- Check production `.env` has `SANCTUM_STATEFUL_DOMAINS`

## Example: Complete n8n Workflow

```javascript
// n8n HTTP Request node configuration
{
  "method": "POST",
  "url": "https://sekaijin.com/api/news",
  "authentication": {
    "type": "genericCredentialType",
    "genericAuthType": "httpHeaderAuth"
  },
  "headers": {
    "Authorization": "Bearer 1|abcdefghijklmnopqrstuvwxyz123456789"
  },
  "body": {
    "title": "Automated News from n8n",
    "summary": "This article was created automatically",
    "content": "Full content here...",
    "thumbnail_url": "https://www.youtube.com/watch?v=VIDEO_ID",
    "author_id": 378,
    "status": "published",
    "country_id": 1
  }
}
```

---

💡 **Pro Tip**: Store your token in n8n Credentials for secure reuse across workflows!