<?php

namespace App\Transformers;

 
use Domain\Grep\StudentLevel;

use League\Fractal\TransformerAbstract;

class StudentSubjectTransformer extends TransformerAbstract
{
 
 

    public function transform(StudentLevel $studentLevel): array
    {
        return [
            'id' => $studentLevel->getTermId(),
            'name' => $studentLevel->getName(),
            'slug' => $studentLevel->getSlug(),
          
        ];
    }
 
}
