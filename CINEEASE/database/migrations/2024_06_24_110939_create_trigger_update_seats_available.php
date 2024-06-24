<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerUpdateSeatsAvailable extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER update_seats_available AFTER INSERT ON bookings
            FOR EACH ROW
            BEGIN
                UPDATE movies
                SET seats_available = seats_available - NEW.seats_booked
                WHERE id = NEW.movie_id;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_seats_available');
    }
}