<?php
namespace App\Enum;
use BenSampo\Enum\Enum;

final class TaskStatusEnum extends Enum{

    const TODO = 'todo';
    const INPROGRESS = 'inprogress';
    const COMPLETED = 'completed';
}
