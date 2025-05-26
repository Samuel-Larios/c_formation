<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $password;

    public function __construct(Student $student, $password)
    {
        $this->student = $student;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Votre compte étudiant a été créé')
                    ->view('emails.student_password');
    }
}
