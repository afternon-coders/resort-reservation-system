# Database Seeding Scripts

## seed_rooms.php

Adds the default rooms to your database.

### Usage

**Via Terminal (Recommended):**
```bash
cd /home/davon/resort-reservation-system
php scripts/seed_rooms.php
```

**Via Browser:**
Navigate to: `http://localhost/resort-reservation-system/scripts/seed_rooms.php`

### What it does

Inserts 4 rooms into the database:
- **A Frame** (Room #101) - ₱6,000/night
- **Cottage** (Room #102) - ₱2,000/night
- **Bird House** (Room #103) - ₱1,500/night
- **Tree House** (Room #104) - ₱1,500/night

All rooms are set to "available" status.

### Notes

- The script uses the RoomModel to insert data, so it respects any validation in the model
- If a room already exists, it will show an error but continue with the next room
- Run this script only once to avoid duplicate entries

### Clearing rooms

If you want to delete all test rooms and start fresh, you can use:
```sql
DELETE FROM rooms;
```
