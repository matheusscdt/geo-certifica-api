<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:finalizar-assinatura-com-prazo')->daily();
Schedule::command('app:enviar-lembrete-assinatura')->daily();
