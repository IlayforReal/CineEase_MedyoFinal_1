<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create stored procedure to calculate total seats available
        DB::unprepared('
            CREATE PROCEDURE CalculateTotalSeatsAvailable(IN movie_id INT, OUT total_seats INT)
            BEGIN
                DECLARE reserved_seats INT;
                
                SELECT COALESCE(SUM(seats_booked), 0) INTO reserved_seats
                FROM bookings
                WHERE movie_id = movie_id
                AND status IN (\'reserved\', \'confirmed\');
                
                SELECT seats_available - reserved_seats INTO total_seats
                FROM movies
                WHERE id = movie_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop stored procedure if needed
        DB::unprepared('DROP PROCEDURE IF EXISTS CalculateTotalSeatsAvailable');
    }
}