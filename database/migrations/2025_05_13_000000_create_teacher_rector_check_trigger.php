<?php

use Illuminate\Database\Migrations\Migration;

class CreateTeacherRectorCheckTrigger extends Migration
{
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER check_teacher_is_rector_before_insert
            BEFORE INSERT ON contracts
            FOR EACH ROW
            BEGIN
                DECLARE isRector BOOLEAN;
                SELECT is_rector INTO isRector FROM teachers WHERE id = NEW.rector;

                IF isRector != 1 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El docente asignado no es rector';
                END IF;
            END;
        ");

  DB::unprepared("
            CREATE TRIGGER check_teacher_is_rector_before_update
            BEFORE UPDATE ON contracts
            FOR EACH ROW
            BEGIN
                DECLARE isRector BOOLEAN;
                SELECT is_rector INTO isRector FROM teachers WHERE id = NEW.rector;

                IF isRector != 1 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El docente asignado no es rector';
                END IF;
            END;
        ");
    }

    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS check_teacher_is_rector_before_insert;");
        DB::unprepared("DROP TRIGGER IF EXISTS check_teacher_is_rector_before_update;");
    }
}