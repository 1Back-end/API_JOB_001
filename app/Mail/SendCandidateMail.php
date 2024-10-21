<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCandidateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $candidate_name;

    public $subject;

    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($candidate_name, $subject, $body)
    {
        $this->candidate_name = $candidate_name;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Obtient le sujet et le message formatés
        $data = $this->getFormattedTextByType('new_candidate', $this->candidate_name);

        // Envoie l'email avec les informations formatées
        return $this->from('laurentalphonsewilfried@gmail.com', 'ONG JOB') // Spécifiez l'expéditeur
                    ->subject($data['subject']) // Utilise le sujet formaté
                    ->markdown('mails.candidate-email') // Vue Markdown de l'email
                    ->with([
                        'body' => $this->body,
                        'candidate_name' => $this->candidate_name,
                    ]);
    }

    /**
     * Example method to get formatted text by type
     */
    protected function getFormattedTextByType($type, $name)
    {
        // Mockup of the getFormattedTextByType function
        return [
            'subject' => "New Candidate: $name",
            'message' => "Hello $name, welcome to our platform.",
        ];
    }
}
