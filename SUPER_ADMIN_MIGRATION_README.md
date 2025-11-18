# Super Admin Role Migration Guide

## Overview

This migration updates the role system to introduce a **Super Admin** role (with no activity logging) and keeps the **Admin** role (with full activity logging).

## Changes Made

### 1. Database Changes

- Updated `users` table `role` enum from `('Admin','Manager','Staff')` to `('Super Admin','Admin','Manager','Staff')`
- Existing Admin users are converted to Super Admin via migration script

### 2. Role Hierarchy

- **Super Admin**: Full system access, NO activity logging
- **Admin**: Full system access, ALL actions logged
- **Manager**: Branch-restricted access, actions logged
- **Staff**: Limited access, actions logged

### 3. Key Features

#### Super Admin

- ✅ All Admin privileges
- ✅ Can create/assign Super Admin role (only Super Admin can do this)
- ✅ Can delete Super Admin users
- ❌ **NO activity logs** - all actions are untracked
- ❌ Login/logout not logged

#### Admin

- ✅ All previous Admin privileges
- ✅ Can create/assign Admin, Manager, Staff roles
- ✅ Can manage branches
- ✅ Can view all users across all branches
- ✅ **ALL actions logged** - full audit trail
- ✅ Login/logout logged

## Migration Steps

### Step 1: Run the Migration SQL

Execute the migration script on your database:

```sql
-- Run migration_add_super_admin.sql
USE `gaming_cafe_db`;

-- Step 1: Update existing Admin users to Super Admin
UPDATE `users` SET `role` = 'Super Admin' WHERE `role` = 'Admin';

-- Step 2: Modify the enum to include Super Admin and Admin
ALTER TABLE `users` MODIFY COLUMN `role` ENUM('Super Admin','Admin','Manager','Staff') NOT NULL DEFAULT 'Staff';
```

### Step 2: Verify Migration

Check that your admin user is now Super Admin:

```sql
SELECT id, username, role FROM users WHERE username = 'admin';
```

Expected result: `role` should be `'Super Admin'`

### Step 3: Create New Admin Users

After migration, you can create new Admin users through the User Management page (as Super Admin). These new Admin users will have all privileges but their actions will be logged.

## Files Modified

### Core Files

- `config/auth.php` - Updated role checks and logging logic
- `database.sql` - Updated schema
- `migration_add_super_admin.sql` - Migration script (NEW)

### API Files

- `api/branches.php` - Super Admin and Admin can manage branches
- `api/users.php` - Role restrictions for Super Admin creation
- `api/dashboard.php` - Multi-branch access for Super Admin and Admin
- `api/transactions.php` - Data isolation updates
- `api/coupons.php` - Branch selection updates

### Page Files

- `includes/header.php` - Branch selector for Super Admin and Admin
- `pages/users.php` - Super Admin role selection
- `pages/dashboard.php` - JavaScript role checks
- `pages/pricing.php` - Role-based UI updates
- `pages/fandd-management.php` - Role checks
- `pages/games.php` - Role checks
- `pages/coupons.php` - Role checks

## Important Notes

1. **Activity Logging**: Super Admin actions are completely excluded from activity logs. This includes:

   - Login/logout events
   - All CRUD operations
   - Any system actions

2. **Role Assignment**: Only Super Admin can:

   - Create new Super Admin users
   - Assign Super Admin role to existing users
   - Delete Super Admin users

3. **Backward Compatibility**: All existing `hasRole('Admin')` checks now return true for both Super Admin and Admin, ensuring backward compatibility.

4. **Branch Management**: Both Super Admin and Admin can:
   - View all branches
   - Select any branch from dropdown
   - Manage branches (Super Admin and Admin only)

## Testing Checklist

After migration, verify:

- [ ] Super Admin can login (no log entry created)
- [ ] Super Admin can create Admin users
- [ ] Admin users can login (log entry created)
- [ ] Admin actions are logged in activity logs
- [ ] Super Admin actions are NOT in activity logs
- [ ] Branch selector appears for Super Admin and Admin
- [ ] Only Super Admin can create/assign Super Admin role
- [ ] Managers cannot edit Super Admin or Admin users

## Rollback (if needed)

If you need to rollback:

```sql
-- Convert Super Admin back to Admin
UPDATE `users` SET `role` = 'Admin' WHERE `role` = 'Super Admin';

-- Revert enum (if needed)
ALTER TABLE `users` MODIFY COLUMN `role` ENUM('Admin','Manager','Staff') NOT NULL DEFAULT 'Staff';
```

Then restore the previous version of the code files from your version control.
