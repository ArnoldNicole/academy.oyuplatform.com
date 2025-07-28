<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\School;
use App\Models\Students;
use Illuminate\Console\Command;

class FixStudentAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $students = Students::whereHas('user', function ($query) {
            $query->whereNull('school_id');
        })->get();

        foreach ($students as $student) {
            $student->user->school_id = $student->school_id;
            $student->user->save();

            $school = School::find($student->school_id);

            $student_role = Role::whereName('Student')->first();


            if (is_null($student_role)) {
                $student_role = new Role();
                $student_role->name = 'Student';
                $student_role->guard_name = 'web';
                $student_role->save();
            }

            $user = $student->user;

            $user_has_role = $user->hasRole('Student');

            if (!$user_has_role) {
                $user->assignRole($student_role);
            }

            $this->info('Student: ' . $student->id . ' has been fixed');

        }
    }
}
