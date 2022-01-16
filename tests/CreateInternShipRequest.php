<?php

namespace Tests;

use App\Rules\TrainingApprovalDateRule;
use App\Rules\TrainingStartDateRule;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateInternShipRequest extends TestCase
{
    /** @test */
   public function testCreateInternShipRequest(){
       $data = [
           'full_name' => 'Omar Garana',
           'id_number' => '123456',
           'gender' => '1',
           'phone' => '123456',
           'email' => 'omar.coder007@gmail.com',
           'city' => 'Riyadh',
           'address' => 'Corniche Rd, Alkurnaish, Al Khobar 34412',
           'interested_in' => '1',
           'university_name' => 'Helwan',
           'degree_type' => '1',
           'degree_name' => 'CS',
           'discipline'=> 'Civil',
           'expected_graduation_year'=> '2022-02',
           'duration' => '10',
           'start_date' => '2022-02-25',
           'end_date' => '2022-03-24',
           'deadline' => '2022-03-18',
           'pref_city' => ["Dammam"],
           'pref_company' => ["Al-Kifah Ready Mix & Blocks (KRB)"],
       ];

//       $header = ["User-Agent" => ];
       $response = $this->json('POST', route('internship.post'),$data);

       $response->assertStatus(200);
       // you can even dump response
       $response->dump();
   }
}
