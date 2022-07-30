## Setup

1. Copy .env.example to .env
2. Run ```$ composer install```
3. Run ```$ sail up```
4. Run ```$ sail artisan migrate --seed```
4. APIs will be available at http://localhost:9088

## Data seeded
- Use test@moneylion.com for email
- Available features: loan,ewallet,salary
- All features are disable for this user by default

## Testing
- Run ```$ sail artisan test```
