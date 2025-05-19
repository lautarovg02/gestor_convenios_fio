<?php

use Illuminate\Database\Migrations\Migration;

class CreateTeacherRectorCheckTrigger extends Migration
{
   public function up()
    {
        // Solo crear los triggers si la base de datos es MySQL
        if (DB::getDriverName() === 'mysql') {  // Este if para que no den error tests, que son en SQlite
            // Trigger BEFORE INSERT
            DB::unprepared("
                CREATE TRIGGER check_teacher_is_rector_before_insert
                BEFORE INSERT ON contracts
                FOR EACH ROW
                BEGIN
                    DECLARE isRector BOOLEAN;

                    SELECT is_rector INTO isRector FROM teachers WHERE id = NEW.rector;

                    IF isRector != 1 THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'El docente asignado no es rector';
                    END IF;
                END;
            ");

            // Trigger BEFORE UPDATE
            DB::unprepared("
                CREATE TRIGGER check_teacher_is_rector_before_update
                BEFORE UPDATE ON contracts
                FOR EACH ROW
                BEGIN
                    DECLARE isRector BOOLEAN;

                    SELECT is_rector INTO isRector FROM teachers WHERE id = NEW.rector;

                    IF isRector != 1 THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'El docente asignado no es rector';
                    END IF;
                END;
            ");
        }
    }

    public function down()
    {
        // Eliminar los triggers solo si estás usando MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared("DROP TRIGGER IF EXISTS check_teacher_is_rector_before_insert;");
            DB::unprepared("DROP TRIGGER IF EXISTS check_teacher_is_rector_before_update;");
        }
    }
}