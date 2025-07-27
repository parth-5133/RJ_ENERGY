<?php

namespace App\Enums;

enum DocumentType: int
{
    case resume_cv = 1;
    case offer_letter = 2;
    case contracts = 3;
    case id_proofs = 4;
    case work_permits_visa = 5;
    case project_document = 6;
    case task_document = 7;
    case policy_document = 8;
    case resignation_letter = 9;
}
