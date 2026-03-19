<?php

class Human{
    public string $name = '';
    public string $job = '';
    public int $age = 0;
    protected string $document = '';
    protected string $pasport = '';
    protected string $ticket = '';

    use Hello;

    public function Document() : string{
        return $this->document;
    }
    public function Pasport() : string{
        return $this->pasport;
    }
    public function SetTicket(string $t){
        $this->ticket = $t;
    } 
    public function GiveTicket() : string{
        return $this->ticket;
    }
}

trait Hello{
    public function Hello() : string{
        return " - Hello";
    }
}

interface Worker{
    public function TellAJob() : string;
}

interface IWorkerMFC{
    public function ShowTicket() : string;
    public function WhatYouWant() : string;
    public function WhereDoYouWork() : string;
    public function Ready() : string;
}

class WorkerMFC extends Human implements IWorkerMFC{
    public function ShowTicket() : string{
        return " - Can you show ticket?";
    }
    public function WhatYouWant() : string{
        return "- What type document do you want to design?";
    }
    public function WhereDoYouWork() : string{
        return " - Give your document and job.";
    }
    public function Ready() : string{
        return "- Documents are being prepared, goodbye.";
    }
}

class TicketTerminal{
    use Hello;
    public function GiveTicket() : string{
        return 'K' . rand();
    }
}


class MFC{
    public array $applicants;
    public array $NewApp;
    public TicketTerminal $terminal;
    public array $records;
    public WorkerMFC $employee;
    public function __construct(array $apps, Human $emp, TicketTerminal $ter){
        $this->applicants = $apps;
        $this->employee = $emp;
        $this->terminal = $ter;
    }

    public function Render() : string{
        
        $this->records[] = 'Queue formation at the MFC';
        foreach($this->applicants as $index => $app){
            $this->records[] =  $index+1 . ' the applicant approaches the terminal';
            $this->records[] = $this->terminal->Hello();
            if (is_a($app,Worker::class)){
                $ticket = $this->terminal->GiveTicket();
                $app->SetTicket($ticket);
                $this->records[] = 'Ticket ' . $ticket;
                $this->NewApp[] = $app;
            } else{
                $this->records[] = 'Refusal.';
            }
            
        }
        $this->records[] = 'Queue have formated.';

        
        foreach($this->NewApp as $app){
            $this->records[] = "\nNew application.";
            $this->records[] = $this->employee->Hello();
            $this->records[] = $app->Hello();
            $this->records[] = $this->employee->ShowTicket();
            $this->records[] = $app ->GiveTicket();
            $this->records[] = $this->employee->WhatYouWant();
            $this->records[] = $app->Document();
            $this->records[] = $this->employee->WhereDoYouWork();
            $this->records[] = $app->TellAJob();
            $this->records[] = $app->Pasport();
            $this->records[] = $this->employee->Ready();
        }


        return implode("\n",$this->records);
    }
}

class Egor extends Human implements Worker{
    public string $name = 'Egor';
    public string $job = 'DevOps';
    public int $age = 30;
    protected string $document = 'New pasport.';
    protected string $pasport = '2025 910349';
    public function TellAJob() : string{
        return $this->job;
    }
}

class Dima extends Human implements Worker{
    public string $name = 'Dima';
    public string $job = 'Frontend';
    public int $age = 45;
    protected string $document = 'Application for inheritance.';
    protected string $pasport = '1902 489178';
    public function TellAJob() : string{
        return $this->job;
    }
}

class Ilya extends Human{
    public string $name = 'Ilya';
    public string $job = 'Unempoyed';
    public int $age = 20;
    protected string $document = 'Apply for social benefits.';
    protected string $pasport = '3591 481218';
}

$tale = new MFC([new Egor, new Dima,new Ilya],
                new WorkerMFC, 
                new TicketTerminal);

echo '<pre>';
echo $tale->render();
echo '</pre>';
?>