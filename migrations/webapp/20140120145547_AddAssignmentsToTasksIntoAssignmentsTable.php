<?php

class AddAssignmentsToTasksIntoAssignmentsTable extends Ruckusing_Migration_Base
{
    public function up()
    {
        $tasks = $this->create_table('assignments', ['id' => false, 'options' => 'Engine=InnoDB']);
        $tasks->column(
            'id',
            'integer',
            [
                'primary_key' => true,
                'auto_increment' => true,
                'null' => false
            ]
        );
        $tasks->column('task_id','integer');
        $tasks->column('message_id','text');
        $tasks->column('created_at','datetime');
        $tasks->column('response_at','datetime');
        $tasks->column('response','text');
        $tasks->finish();
    }//up()

    public function down()
    {
        $this->drop_table("assignments");
    }//down()
}
