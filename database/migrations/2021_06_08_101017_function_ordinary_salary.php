<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class FunctionOrdinarySalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql =

        'DROP FUNCTION IF EXISTS ordinary_salary;

        CREATE FUNCTION ordinary_salary(employee INT, start_date VARCHAR(20), final_date VARCHAR(20)) returns double
            deterministic
        BEGIN 
            DECLARE finished INTEGER DEFAULT 0;
            DECLARE total_producido DOUBLE;
            DECLARE precio_vitola DOUBLE;
            DECLARE total_a_pagar DOUBLE DEFAULT 0;
            DECLARE cur1 CURSOR FOR SELECT t1.total_producido, t1.precio_vitola
            FROM 
            (
                SELECT SUM(amount_of_cigars) total_producido, categories.price_hundred precio_vitola
                FROM tickets_employees
                INNER JOIN tickets ON ticket_id = tickets.id
                INNER JOIN employees ON employee_id = employees.id
                INNER JOIN products ON tickets.product_id = products.id
                INNER JOIN categories ON products.category_id = categories.id
                WHERE employees.id = employee 
                AND Date(tickets.created_at) BETWEEN start_date AND final_date
                AND tickets.`status` = "finalizado"
                GROUP BY products.id
            ) t1;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
            
            OPEN cur1;
            
            read_loop: LOOP
                FETCH cur1 INTO total_producido, precio_vitola;	
                IF finished = 1 THEN
                  LEAVE read_loop;
                END IF;
                SET total_a_pagar = total_a_pagar + (total_producido * precio_vitola);
              END LOOP;
        
            CLOSE cur1;
        
           return total_a_pagar;
            
        END';
        
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $sql = 'DROP FUNCTION IF EXISTS ordinary_salary';
       DB::unprepared($sql);
    }
}
