<?php

namespace App\Console\Commands;

use App\Models\ClassSchool;
use App\Models\ClassSection;
use App\Models\ClassSubject;
use App\Models\Mediums;
use App\Models\School;
use App\Models\Section;
use App\Models\SessionYear;
use App\Models\Students;
use App\Models\StudentSubject;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MigrateDataFromVersionOne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The School admin to use as default
     */

    private $school_admin = null;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $old_connection_name = 'version_1';
        $new_connection_name = 'mysql';

        DB::transaction(function () use ($old_connection_name, $new_connection_name) {
            $old_schools = DB::connection($old_connection_name)->table('schools')->get();

            foreach ($old_schools as $old_school) {
                $this->info("Migration started for school: " . $old_school->title . ".... \n");
                $school_exists = DB::connection($new_connection_name)->table('schools')->where('name', $old_school->title)->first();
                if (is_null($school_exists)) {
                    $old_school_admin = DB::connection($old_connection_name)->table('users')->where('school_id', $old_school->id)->where('role_id', 2)->first();

                    $admin_names = explode(' ', $old_school_admin->name);

                    $this->info("Creating school admin....");

                    $admin_exists = User::where('email', $old_school_admin->email)->first();

                    if (!is_null($admin_exists)) {
                        $new_school_admin = $admin_exists;
                    } else {
                        $new_school_admin = User::create([
                            'first_name' => $admin_names[0],
                            'last_name' => $admin_names[1] ?? '-',
                            'email' => $old_school_admin->email,
                            'mobile' => $old_school_admin->phone_number,
                            'password' => Hash::make('password'),
                        ]);
                    }

                    $this->school_admin = $new_school_admin;

                    if (is_null($admin_exists)) {
                        $this->info("Giving permissions...");
                        DB::connection($new_connection_name)->table('model_has_roles')->insert([
                            'role_id' => 2,
                            'model_type' => 'App\Models\User',
                            'model_id' => $new_school_admin->id,
                        ]);
                    }

                    $this->info("Creating school...");
                    $new_school = $this->createSchool(old_school: $old_school, new_school_admin: $new_school_admin);

                    $session_year = SessionYear::create([
                        "id" => 1,
                        "name" => "2023-24",
                        "default" => 1,
                        "start_date" => "2024-01-01",
                        "end_date" => "2024-12-31",
                        "school_id" => $new_school->id,
                    ]);

                    $this->info("Adding settings for school...");
                    $this->createSchoolSettings($new_school);

                    $old_classes = DB::connection($old_connection_name)->table('classes')->where('school_id', $old_school->id)->get();

                    $this->info("Creating classes...");
                    $this->createClasses(new_school: $new_school, old_classes: $old_classes, old_connection_name: $old_connection_name, session_year_id: $session_year->id, old_school_id: $old_school->id);
                }
            }
        });
    }

    private function createSchool($old_school, $new_school_admin): School
    {
        return School::create([
            'name' => $old_school->title,
            'address' => $old_school->address,
            'support_phone' => $old_school->phone,
            'support_email' => $old_school->email,
            'tagline' => $old_school->school_info,
            'logo' => 'https://v2.oyuacademy.qtechafrica.com/storage/6680054b284d83.821879391719665995.png',
            'admin_id' => $new_school_admin->id,
            'status' => 1,
        ]);
    }

    private function createSchoolSettings(School $school)
    {
        $settings = [["name" => "school_name", "data" => $school->name, "type" => "string",], ["name" => "school_email", "data" => $school->support_email, "type" => "string",], ["name" => "school_phone", "data" => $school->support_phone, "type" => "number",], ["name" => "school_tagline", "data" => $school->tagline, "type" => "string",], ["name" => "school_address", "data" => $school->address, "type" => "string",], ["name" => "session_year", "data" => "1", "type" => "number",], ["name" => "horizontal_logo", "data" => "https://oyuacademy.qtechafrica.com/storage/horizontal_logo.png", "type" => "file",], ["name" => "vertical_logo", "data" => "https://oyuacademy.qtechafrica.com/storage/vertical_logo.png", "type" => "file",], ["name" => "timetable_start_time", "data" => "08:00:00", "type" => "time",], ["name" => "timetable_end_time", "data" => "16:20:00", "type" => "time",], ["name" => "timetable_duration", "data" => "00:40:00", "type" => "time",], ["name" => "auto_renewal_plan", "data" => "1", "type" => "integer",], ["name" => "currency_code", "data" => "KES", "type" => "string",], ["name" => "currency_symbol", "data" => "Ksh.", "type" => "string",],];
        foreach ($settings as $setting) {
            DB::table('school_settings')->insert([
                'name' => $setting['name'],
                'data' => $setting['data'],
                'type' => $setting['type'],
                'school_id' => $school->id,
            ]);
        }
    }

    private function createClasses($new_school, $old_classes, $old_connection_name, $session_year_id, $old_school_id)
    {
        foreach ($old_classes as $old_class) {
            $this->info("Seeding grade " . $old_class->name . "....");
            $old_sections = DB::connection($old_connection_name)->table('sections')->where('class_id', $old_class->id)->get();
            foreach ($old_sections as $old_section) {
                if (is_null($old_section)) {
                    $section = Section::create(['name' => 'Normal', 'school_id' => $new_school->id]);
                } else {
                    $section = Section::create(['name' => $old_section->name, 'school_id' => $new_school->id]);
                }

                $medium = Mediums::create([
                    'name' => 'Physical',
                    'school_id' => $new_school->id
                ]);

                $class_ = ClassSchool::create([
                    'name' => $old_class->name,
                    'include_semesters' => 0,
                    'school_id' => $new_school->id,
                    'medium_id' => $medium->id
                ]);

                $class_section = ClassSection::create([
                    'class_id' => $class_->id,
                    'section_id' => $section->id,
                    'medium_id' => $medium->id,
                    'school_id' => $new_school->id
                ]);

                $old_class_subjects = DB::connection($old_connection_name)->table('subjects')->where('class_id', $old_class->id)->get();

                $student_subjects = [];

                foreach ($old_class_subjects as $old_subject) {
                    $code = abbreviate_subject($old_subject->name);
                    $subject = Subject::where('code', $code)->where('school_id', $new_school->id)->first();
                    if (is_null($subject)) {
                        $subject = Subject::create([
                            'name' => $old_subject->name,
                            'code' => $code,
                            'school_id' => $new_school->id,
                            'bg_color' => random_color_code(),
                            'type' => 'Theory',
                            'medium_id' => $medium->id
                        ]);
                    }

                    $class_subject = ClassSubject::where([
                        'class_id' => $class_->id,
                        'subject_id' => $subject->id,
                        'virtual_semester_id' => 0,
                    ])->first();

                    if (is_null($class_subject)) {
                        $class_subject = ClassSubject::create([
                            'class_id' => $class_->id,
                            'subject_id' => $subject->id,
                            'type' => 'Compulsory',
                            'virtual_semester_id' => 0,
                            'school_id' => $new_school->id
                        ]);
                    }

                    $student_subjects[] = $class_subject;
                }

                $enrollments = DB::connection($old_connection_name)
                    ->table('enrollments')
                    ->where('class_id', $old_class->id)
                    ->where('section_id', $old_section->id)
                    ->where('school_id', $old_school_id)
                    ->get();

                $student_ids = $enrollments->pluck('user_id');

                $class_old_students = DB::connection($old_connection_name)->table('users')->whereIn('id', $student_ids)->get();

                $this->createStudents(
                    new_school: $new_school,
                    old_students: $class_old_students,
                    old_connection_name: $old_connection_name,
                    year_id: $session_year_id,
                    class_section_id: $class_section->id,
                    class_subjects: $student_subjects
                );
            }
        }
    }

    private function createStudents($new_school, $old_students, string $old_connection_name, int $year_id, int $class_section_id, array $class_subjects)
    {
        foreach ($old_students as $old_student) {
            $old_guardian = DB::connection($old_connection_name)->table('users')->where('id', $old_student->parent_id)->first();

            if (is_null($old_guardian)) {
                $new_parent = $this->school_admin;
            } else {
                $new_parent = User::where('email', $old_guardian->email)->first();
            }


            if (is_null($new_parent)) {
                $new_parent = User::create([
                    'first_name' => $old_guardian->name,
                    'last_name' => '-',
                    'email' => $old_guardian->email,
                    'mobile' => $old_guardian->phone_number,
                    'password' => Hash::make('password'),
                ]);
            }

            $user = User::where('email', $old_student->email)->first();

            if (is_null($user)) {
                $user = User::create([
                    'first_name' => $old_student->name,
                    'last_name' => '-',
                    'email' => $old_student->email,
                    'mobile' => $old_student->phone_number,
                    'password' => Hash::make('password'),
                ]);

                $user->assignRole('Student');
            }

            $student = Students::where('user_id', $user->id)->first();

            if (is_null($student)) {
                Students::create([
                    'class_section_id' => $class_section_id,
                    'admission_no' => $old_student->code,
                    'roll_number' => 1,
                    'admission_date' => $old_student->created_at,
                    'guardian_id' => $new_parent->id,
                    'school_id' => $new_school->id,
                    'session_year_id' => $year_id,
                    'user_id' => $user->id
                ]);

                foreach ($class_subjects as $class_subject) {
                    $d = [
                        'student_id' => $user->id,
                        'class_subject_id' => $class_subject->id,
                        'class_section_id' => $class_section_id,
                        'session_year_id' => $year_id,
                        'school_id' => $new_school->id
                    ];
                    StudentSubject::create($d);
                }
            }
        }
        $this->info("Students created successfully");
    }
}
