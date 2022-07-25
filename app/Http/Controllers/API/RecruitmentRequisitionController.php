<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecruitmentRequisitionController extends ExperienceCertController
{
    public function store(Request $request)
    {
         return parent::store($request);
    }
}
